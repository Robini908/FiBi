<?php

namespace App\Auth;

use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\App;
use Illuminate\Cookie\CookieJar;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use App\Services\SessionGuardService;

class CustomSessionGuard extends SessionGuard
{
    /**
     * Create a new authentication guard.
     *
     * @param  string  $name
     * @param  \Illuminate\Contracts\Auth\UserProvider  $provider
     * @param  \Illuminate\Contracts\Session\Session  $session
     * @param  \Illuminate\Http\Request|null  $request
     * @return void
     */
    public function __construct($name, $provider, $session, $request = null)
    {
        parent::__construct($name, $provider, $session, $request);
        
        // Set essential dependencies
        $this->setCookieJar(app('cookie'));
        $this->setDispatcher(app('events'));
        $this->setRequest($request ?: app('request'));
        
        // Ensure cookie jar is available
        $this->ensureCookieJar();
    }

    /**
     * Ensure cookie jar is available before using session.
     *
     * @return void
     */
    protected function ensureCookieJar()
    {
        try {
            // Check if cookie jar is bound and properly initialized
            if (!App::bound('cookie') || !App::make('cookie')) {
                $cookieJar = new CookieJar();
                $cookieJar->setDefaultPathAndDomain(
                    config('session.path', '/'),
                    config('session.domain', null),
                    config('session.secure', false),
                    config('session.same_site', 'lax')
                );
                
                App::instance('cookie', $cookieJar);
                
                Log::info('Cookie jar was initialized in CustomSessionGuard');
            }
        } catch (\Exception $e) {
            Log::error('Failed to ensure cookie jar in CustomSessionGuard', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Log in the user without a password.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  bool  $remember
     * @return void
     */
    public function login(Authenticatable $user, $remember = false)
    {
        try {
            // Ensure cookie jar is available
            $this->ensureCookieJar();
            
            // Ensure the session is properly started
            if (!$this->session->isStarted()) {
                $this->session->start();
            }
            
            // Call the parent login method with proper error handling
            try {
                parent::login($user, $remember);
            } catch (\Exception $e) {
                Log::error('Error in parent login method', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                // Continue execution to at least try to set session variables
            }
            
            // Log the login for auditing purposes
            Log::info('User logged in via CustomSessionGuard', [
                'user_id' => $user->getAuthIdentifier(),
                'remember' => $remember,
                'guard' => $this->name,
            ]);
            
            // Use our SessionGuardService if available
            if (class_exists(SessionGuardService::class)) {
                try {
                    SessionGuardService::startSession($user, $remember);
                    return; // Exit early if successful
                } catch (\Exception $e) {
                    Log::warning('Could not use SessionGuardService, falling back to direct session usage', [
                        'error' => $e->getMessage()
                    ]);
                    // Continue with fallback approach
                }
            }
            
            // Fallback: Store important user data in the session directly
            try {
                $this->session->put('user_id', $user->getAuthIdentifier());
                $this->session->put('user_login_timestamp', now()->timestamp);
                
                // If we have a name property, store it
                if (isset($user->name)) {
                    $this->session->put('user_name', $user->name);
                }
                
                // If we have a user_type property, store it
                if (isset($user->user_type)) {
                    $this->session->put('user_type', $user->user_type);
                }
            } catch (\Exception $e) {
                Log::error('Error storing user data in session during login', [
                    'user_id' => $user->getAuthIdentifier(),
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        } catch (\Exception $e) {
            Log::critical('Critical error in CustomSessionGuard login method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
    
    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout()
    {
        try {
            // Ensure cookie jar is available
            $this->ensureCookieJar();
            
            // Log the logout for auditing purposes
            if ($this->user) {
                Log::info('User logged out via CustomSessionGuard', [
                    'user_id' => $this->user->getAuthIdentifier(),
                    'guard' => $this->name,
                ]);
            }
            
            // Use our SessionGuardService if available
            if (class_exists(SessionGuardService::class)) {
                try {
                    SessionGuardService::endSession();
                    $this->user = null;
                    return; // Exit early if successful
                } catch (\Exception $e) {
                    Log::warning('Could not use SessionGuardService for logout, falling back to default', [
                        'error' => $e->getMessage()
                    ]);
                    // Continue with fallback approach
                }
            }
            
            try {
                // Call the parent logout method
                parent::logout();
            } catch (\Exception $e) {
                Log::error('Error during logout process', [
                    'user_id' => $this->user ? $this->user->getAuthIdentifier() : null,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                
                // Force removal of the session
                if ($this->session) {
                    try {
                        $this->session->flush();
                        $this->session->regenerate(true);
                    } catch (\Exception $sessionError) {
                        Log::error('Error flushing session during logout fallback', [
                            'error' => $sessionError->getMessage(),
                        ]);
                    }
                }
                
                // Force removal of the recaller cookie
                try {
                    Cookie::queue(Cookie::forget($this->getRecallerName()));
                } catch (\Exception $cookieError) {
                    Log::error('Error removing recaller cookie during logout fallback', [
                        'error' => $cookieError->getMessage(),
                    ]);
                }
                
                $this->user = null;
            }
        } catch (\Exception $e) {
            Log::critical('Critical error in CustomSessionGuard logout method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            // Last resort: force user to null
            $this->user = null;
        }
    }
    
    /**
     * Determine if the user matches the credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    protected function hasValidCredentials($user, $credentials)
    {
        if (!$user) {
            return false;
        }
        
        try {
            $valid = parent::hasValidCredentials($user, $credentials);
            
            if (!$valid) {
                // Log the failed login attempt
                Log::warning('Failed login attempt via CustomSessionGuard', [
                    'user_id' => $user ? $user->getAuthIdentifier() : null,
                    'guard' => $this->name,
                    'credential_keys' => array_keys($credentials),
                ]);
            }
            
            return $valid;
        } catch (\Exception $e) {
            Log::error('Error validating credentials', [
                'user_id' => $user ? $user->getAuthIdentifier() : null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }
    
    /**
     * Override to add better handling of "remember me" functionality
     *
     * @param  array  $credentials
     * @param  bool  $remember
     * @return bool
     */
    public function attempt(array $credentials = [], $remember = false)
    {
        try {
            // Ensure cookie jar is available
            $this->ensureCookieJar();
            
            // Ensure the session is properly started
            if (!$this->session->isStarted()) {
                $this->session->start();
            }
            
            $this->fireAttemptEvent($credentials, $remember);
    
            $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);
    
            if ($this->hasValidCredentials($user, $credentials)) {
                $this->login($user, $remember);
                
                return true;
            }
    
            $this->fireFailedEvent($user, $credentials);
    
            return false;
        } catch (\Exception $e) {
            Log::error('Error during login attempt', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }
    
    /**
     * Get the decrypted recaller cookie for the request.
     *
     * @return \Illuminate\Auth\Recaller|null
     */
    protected function recaller()
    {
        try {
            // Ensure cookie jar is available
            $this->ensureCookieJar();
            
            return parent::recaller();
        } catch (\Exception $e) {
            Log::error('Error processing recaller cookie', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }
} 