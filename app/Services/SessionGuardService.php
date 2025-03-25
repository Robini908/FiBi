<?php

namespace App\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Config;
use Jenssegers\Agent\Agent;

class SessionGuardService
{
    /**
     * Start a new authenticated session for the user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  bool  $remember
     * @return void
     */
    public static function startSession(Authenticatable $user, bool $remember = false): void
    {
        // Generate CSRF token to ensure security
        Session::regenerateToken();
        
        // Store user information in session
        Session::put('user_id', $user->getAuthIdentifier());
        Session::put('user_login_timestamp', now()->timestamp);
        
        // Store additional user information if available
        if (property_exists($user, 'name')) {
            Session::put('user_name', $user->name);
        }
        
        if (property_exists($user, 'user_type')) {
            Session::put('user_type', $user->user_type);
        }
        
        if (property_exists($user, 'email')) {
            Session::put('user_email', $user->email);
        }
        
        // Generate and store a session fingerprint to improve security
        self::storeSessionFingerprint();
        
        // Set session expiration based on remember flag
        if ($remember) {
            // Extend session lifetime for "remember me" functionality
            $extendedLifetime = Config::get('session.extended_lifetime', 43200); // Default to 30 days
            Cookie::queue(
                Config::get('session.cookie'),
                Session::getId(),
                $extendedLifetime,
                Config::get('session.path'),
                Config::get('session.domain'),
                Config::get('session.secure', false),
                Config::get('session.http_only', true),
                false,
                Config::get('session.same_site', 'lax')
            );
            
            Session::put('remember_user', true);
        }
        
        Log::info('Session started for user', [
            'user_id' => $user->getAuthIdentifier(),
            'remember' => $remember,
            'session_id' => Session::getId(),
        ]);
    }
    
    /**
     * End the current session.
     *
     * @return void
     */
    public static function endSession(): void
    {
        // Log before invalidating the session
        Log::info('Ending session', [
            'user_id' => Session::get('user_id'),
            'session_id' => Session::getId(),
        ]);
        
        // Invalidate the session
        Session::flush();
        Session::invalidate();
        Session::regenerateToken();
        
        // Remove all session-related cookies
        $cookieNames = [
            Config::get('session.cookie'),
            'XSRF-TOKEN',
            'mbuku_session'
        ];
        
        foreach ($cookieNames as $cookieName) {
            if (!empty($cookieName)) {
                Cookie::queue(Cookie::forget($cookieName));
            }
        }
    }
    
    /**
     * Store a fingerprint of the session to detect session hijacking attempts.
     *
     * @return void
     */
    private static function storeSessionFingerprint(): void
    {
        try {
            $agent = new Agent();
            
            $fingerprint = [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'platform' => $agent->platform(),
                'browser' => $agent->browser(),
                'device' => $agent->device(),
                'timestamp' => now()->timestamp,
            ];
            
            // Add a hash of the fingerprint for quick comparison
            $fingerprintHash = hash('sha256', json_encode($fingerprint));
            
            Session::put('session_fingerprint', $fingerprintHash);
            Session::put('session_fingerprint_data', $fingerprint);
            
            Log::debug('Session fingerprint stored', [
                'fingerprint_hash' => $fingerprintHash,
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to store session fingerprint', [
                'error' => $e->getMessage(),
            ]);
        }
    }
    
    /**
     * Validate the current session fingerprint against the stored one.
     *
     * @return bool
     */
    public static function validateSessionFingerprint(): bool
    {
        if (!Session::has('session_fingerprint')) {
            return false;
        }
        
        try {
            $agent = new Agent();
            
            $currentFingerprint = [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'platform' => $agent->platform(),
                'browser' => $agent->browser(),
                'device' => $agent->device(),
            ];
            
            $currentHash = hash('sha256', json_encode($currentFingerprint));
            $storedHash = Session::get('session_fingerprint');
            
            $isValid = $currentHash === $storedHash;
            
            if (!$isValid) {
                Log::warning('Session fingerprint mismatch - possible session hijacking attempt', [
                    'stored_hash' => $storedHash,
                    'current_hash' => $currentHash,
                    'session_id' => Session::getId(),
                    'user_id' => Session::get('user_id'),
                ]);
            }
            
            return $isValid;
        } catch (\Exception $e) {
            Log::error('Error validating session fingerprint', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
    
    /**
     * Check if the session has expired based on inactivity timeout.
     *
     * @return bool
     */
    public static function hasSessionExpired(): bool
    {
        if (!Session::has('user_login_timestamp')) {
            return true;
        }
        
        $lastActivity = Session::get('user_login_timestamp');
        $sessionLifetime = Session::has('remember_user') 
            ? Config::get('session.extended_lifetime', 43200) * 60 // Convert to seconds
            : Config::get('session.lifetime', 120) * 60; // Convert to seconds
        
        $expired = (time() - $lastActivity) > $sessionLifetime;
        
        if ($expired) {
            Log::info('Session expired due to inactivity', [
                'last_activity' => $lastActivity,
                'current_time' => time(),
                'session_lifetime' => $sessionLifetime,
                'session_id' => Session::getId(),
                'user_id' => Session::get('user_id'),
            ]);
        }
        
        return $expired;
    }
    
    /**
     * Update the last activity timestamp to keep the session alive.
     *
     * @return void
     */
    public static function updateLastActivity(): void
    {
        Session::put('user_login_timestamp', time());
    }
} 