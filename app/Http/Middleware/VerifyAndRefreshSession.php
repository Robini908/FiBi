<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Log;

class VerifyAndRefreshSession
{
    protected $session;
    
    /**
     * The session lifetime in minutes.
     *
     * @var int
     */
    protected $lifetime;
    
    /**
     * Session activity timeout in minutes (30 minutes by default).
     * If the user is inactive for this amount of time, we'll refresh the session.
     *
     * @var int
     */
    protected $activityTimeout;
    
    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Session\Store  $session
     * @return void
     */
    public function __construct(Store $session)
    {
        $this->session = $session;
        $this->lifetime = config('session.lifetime', 1440); // 24 hours by default
        $this->activityTimeout = 30; // 30 minutes of inactivity
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Skip for asset requests
        if ($this->isAssetRequest($request)) {
            return $next($request);
        }
        
        // Only process authenticated requests
        if (Auth::check()) {
            try {
                // Get the last activity timestamp from the session
                $lastActivity = $this->session->get('last_activity');
                
                // If we have last activity time and the user has been inactive for longer than the timeout
                if ($lastActivity && time() - $lastActivity > $this->activityTimeout * 60) {
                    // Only regenerate token, don't regenerate the whole session which can cause issues
                    $request->session()->regenerateToken();
                    
                    // Log the session refresh for debugging
                    Log::info('Session token refreshed due to inactivity', [
                        'user_id' => Auth::id(),
                        'last_activity' => date('Y-m-d H:i:s', $lastActivity),
                        'timeout_minutes' => $this->activityTimeout,
                    ]);
                }
                
                // Update the last activity timestamp - only if we don't already 
                // have one or it's been more than 5 minutes to avoid constant DB writes
                if (!$lastActivity || time() - $lastActivity > 300) {
                    $this->session->put('last_activity', time());
                }
                
                // Only fix missing session data, don't rewrite it unnecessarily
                if (!$this->session->has('user_id')) {
                    // Re-set important user data in the session if missing
                    $user = Auth::user();
                    $this->session->put('user_id', $user->id);
                    
                    // Only write these if they exist on the user model
                    if (isset($user->name)) {
                        $this->session->put('user_name', $user->name);
                    }
                    
                    if (isset($user->user_type)) {
                        $this->session->put('user_type', $user->user_type);
                    }
                }
            } catch (\Exception $e) {
                // Log errors but don't break the request
                Log::error('Error in VerifyAndRefreshSession middleware', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }
        
        return $next($request);
    }
    
    /**
     * Check if the request is for an asset file
     */
    protected function isAssetRequest($request)
    {
        $path = $request->path();
        $extensions = ['js', 'css', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'woff', 'woff2', 'ttf', 'eot', 'ico'];
        
        // Check file extensions
        foreach ($extensions as $extension) {
            if (str_ends_with($path, '.' . $extension)) {
                return true;
            }
        }
        
        // Check common asset paths
        $assetPaths = ['assets', 'images', 'fonts', 'vendor', 'global_assets'];
        foreach ($assetPaths as $assetPath) {
            if (str_starts_with($path, $assetPath)) {
                return true;
            }
        }
        
        return false;
    }
} 