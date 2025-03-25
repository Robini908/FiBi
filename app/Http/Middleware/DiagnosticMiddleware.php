<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

class DiagnosticMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Skip diagnostics for asset requests to reduce log spam
        if ($this->isAssetRequest($request)) {
            return $next($request);
        }
        
        // Get cookies from the request
        $cookieNames = [];
        $cookieCount = 0;
        
        // Try multiple ways to access cookies
        try {
            if (method_exists($request, 'cookie')) {
                $cookies = $request->cookie();
                if (is_array($cookies)) {
                    $cookieNames = array_keys($cookies);
                    $cookieCount = count($cookies);
                }
            }
        } catch (\Exception $e) {
            // Silently handle cookie access issues
        }
        
        $diagnostics = [
            'request_path' => $request->path(),
            'request_method' => $request->method(),
            'request_ajax' => $request->ajax(),
            'request_secure' => $request->secure(),
            'has_session' => $request->hasSession(),
            'session_started' => Session::isStarted(),
            'cookie_jar_bound' => App::bound('cookie'),
            'cookie_count' => $cookieCount,
            'cookie_names' => $cookieNames,
            'middleware' => $this->getCurrentMiddleware(),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
        ];
        
        // Append diagnostics for development environment, but only for 
        // important routes to avoid overwhelming logs
        if (config('app.debug') && $this->isImportantRoute($request)) {
            Log::debug('Session and Cookie Diagnostics', $diagnostics);
        }

        try {
            $response = $next($request);
            
            // Only check response cookies for important routes
            if (config('app.debug') && $this->isImportantRoute($request)) {
                if (method_exists($response, 'headers') && method_exists($response->headers, 'getCookies')) {
                    $responseCookies = $response->headers->getCookies();
                    
                    // Log only if we have cookies in the response
                    if (count($responseCookies) > 0) {
                        Log::debug('Response Cookies', [
                            'cookie_count' => count($responseCookies),
                            'path' => $request->path()
                        ]);
                    }
                }
            }
            
            return $response;
        } catch (\Exception $e) {
            Log::error('Error in request pipeline', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'diagnostics' => $diagnostics
            ]);
            
            throw $e;
        }
    }
    
    /**
     * Get current middleware stack for diagnostics.
     *
     * @return array
     */
    protected function getCurrentMiddleware()
    {
        $routeMiddleware = [];
        
        if (app('router')->current()) {
            $routeMiddleware = app('router')->current()->gatherMiddleware();
        }
        
        return $routeMiddleware;
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
    
    /**
     * Check if this is an important route that we want to log
     */
    protected function isImportantRoute($request)
    {
        $path = $request->path();
        $importantRoutes = ['login', 'logout', 'dashboard', 'register', 'password/reset'];
        
        foreach ($importantRoutes as $route) {
            if ($path === $route || str_starts_with($path, $route)) {
                return true;
            }
        }
        
        // For root path
        if ($path === '/' || $path === '') {
            return true;
        }
        
        return false;
    }
} 