<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Cookie\CookieJar;
use Illuminate\Contracts\Encryption\Encrypter;

class FixCookieJarMiddleware
{
    /**
     * The cookie jar instance.
     *
     * @var \Illuminate\Cookie\CookieJar
     */
    protected $cookies;

    /**
     * The encrypter instance.
     *
     * @var \Illuminate\Contracts\Encryption\Encrypter
     */
    protected $encrypter;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Cookie\CookieJar  $cookies
     * @param  \Illuminate\Contracts\Encryption\Encrypter  $encrypter
     * @return void
     */
    public function __construct(CookieJar $cookies, Encrypter $encrypter)
    {
        $this->cookies = $cookies;
        $this->encrypter = $encrypter;
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
        try {
            // Force cookie jar to be available
            if (!app()->bound('cookie') || !app()->make('cookie')) {
                $cookieJar = new CookieJar();
                $cookieJar->setDefaultPathAndDomain(
                    config('session.path', '/'),
                    config('session.domain', null),
                    config('session.secure', false),
                    config('session.same_site', 'lax')
                );
                app()->instance('cookie', $cookieJar);
                
                Log::info('Cookie jar was not set, created a new one', [
                    'path' => $request->path(),
                ]);
            }
            
            // Ensure session is started
            if (!Session::isStarted()) {
                Session::start();
            }
        } catch (\Exception $e) {
            Log::error('Error initializing cookie handling in FixCookieJarMiddleware', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'path' => $request->path(),
            ]);
        }

        $response = $next($request);

        try {
            // Apply all queued cookies to the response
            $cookies = Cookie::getQueuedCookies();
            if (!empty($cookies)) {
                foreach ($cookies as $cookie) {
                    if (method_exists($response, 'withCookie')) {
                        $response = $response->withCookie($cookie);
                    } elseif (method_exists($response->headers, 'setCookie')) {
                        $response->headers->setCookie($cookie);
                    }
                }
                
                Log::debug('Cookies were manually set on response', [
                    'cookie_count' => count($cookies),
                    'path' => $request->path(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error applying cookies to response', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'path' => $request->path(),
            ]);
        }

        return $response;
    }
} 