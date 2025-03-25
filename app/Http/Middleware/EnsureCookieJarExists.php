<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cookie\CookieJar;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class EnsureCookieJarExists
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
        try {
            // Check if the cookie jar is bound and accessible
            if (!App::bound('cookie') || !App::make('cookie')) {
                // Create a new cookie jar with default configuration
                $cookieJar = new CookieJar();
                $cookieJar->setDefaultPathAndDomain(
                    config('session.path', '/'),
                    config('session.domain', null),
                    config('session.secure', false),
                    config('session.same_site', 'lax')
                );
                
                // Bind the cookie jar to the application container
                App::instance('cookie', $cookieJar);
                
                Log::info('Created new cookie jar instance in EnsureCookieJarExists middleware', [
                    'path' => $request->path(),
                ]);
            }
            
            // Ensure session is started
            if (!Session::isStarted()) {
                Session::start();
            }
        } catch (\Exception $e) {
            Log::error('Failed to ensure cookie jar exists', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'path' => $request->path(),
            ]);
            
            // Do a last attempt using a simplified approach
            try {
                $cookieJar = new CookieJar();
                App::instance('cookie', $cookieJar);
                Log::warning('Used fallback cookie jar initialization');
            } catch (\Exception $fallbackError) {
                Log::critical('All cookie jar initialization attempts failed', [
                    'error' => $fallbackError->getMessage()
                ]);
            }
        }
        
        return $next($request);
    }
} 