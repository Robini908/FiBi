<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use App\Services\SessionGuardService;

class EnsureAuthWorks
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        try {
            // Fix common authentication issues
            $this->fixAuthIssues($request);
            
            // Ensure the XSRF token is set in the cookie
            $this->ensureXsrfTokenCookie($request, $response);
        } catch (\Exception $e) {
            Log::error('Error in EnsureAuthWorks middleware', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'path' => $request->path(),
            ]);
        }
        
        return $response;
    }
    
    /**
     * Fix common authentication issues
     */
    protected function fixAuthIssues(Request $request)
    {
        // Don't run for API or asset requests
        if ($request->is('api/*') || $this->isAssetRequest($request)) {
            return;
        }
        
        // Ensure the session is started
        if (!Session::isStarted()) {
            Session::start();
        }
        
        // If user is logged in, ensure session data is consistent
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user ID in session matches the authenticated user
            if (!Session::has('user_id') || Session::get('user_id') !== $user->id) {
                // Fix the session data
                Session::put('user_id', $user->id);
                Log::info('Fixed inconsistent user_id in session', [
                    'user_id' => $user->id,
                    'path' => $request->path(),
                ]);
            }
            
            // Update last activity
            Session::put('last_activity', now()->timestamp);
            
            // Use the SessionGuardService if available
            if (class_exists(SessionGuardService::class)) {
                try {
                    SessionGuardService::updateLastActivity();
                } catch (\Exception $e) {
                    Log::warning('Failed to update last activity via SessionGuardService', [
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }
    }
    
    /**
     * Ensure the XSRF token cookie is set
     */
    protected function ensureXsrfTokenCookie(Request $request, $response)
    {
        // Skip for AJAX requests and asset requests
        if ($request->ajax() || $this->isAssetRequest($request)) {
            return;
        }
        
        // Check if the response already has the XSRF cookie
        $cookieNames = array_map(function ($cookie) {
            return $cookie->getName();
        }, $response->headers->getCookies());
        
        if (!in_array('XSRF-TOKEN', $cookieNames)) {
            // Add the XSRF cookie
            $config = config('session');
            $cookie = cookie(
                'XSRF-TOKEN',
                $request->session()->token(),
                60, // 1 hour
                $config['path'] ?? '/',
                $config['domain'] ?? null,
                $config['secure'] ?? null,
                false, // HTTP Only must be false for CSRF
                false, // Raw
                $config['same_site'] ?? 'lax'
            );
            
            // Set the cookie on the response
            if (method_exists($response, 'withCookie')) {
                $response->withCookie($cookie);
            } elseif (method_exists($response->headers, 'setCookie')) {
                $response->headers->setCookie($cookie);
            } else {
                // Fallback: Queue cookie for next request
                Cookie::queue($cookie);
            }
            
            Log::debug('Added missing XSRF-TOKEN cookie', [
                'path' => $request->path(),
            ]);
        }
    }
    
    /**
     * Check if the request is for an asset file
     */
    protected function isAssetRequest(Request $request)
    {
        $path = $request->path();
        $extensions = ['js', 'css', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'woff', 'woff2', 'ttf', 'eot', 'ico'];
        
        foreach ($extensions as $extension) {
            if (str_ends_with($path, '.' . $extension)) {
                return true;
            }
        }
        
        return false;
    }
} 