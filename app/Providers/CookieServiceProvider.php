<?php

namespace App\Providers;

use Illuminate\Cookie\CookieJar;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class CookieServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('cookie', function ($app) {
            try {
                $cookieJar = new CookieJar();
                
                return $cookieJar->setDefaultPathAndDomain(
                    $app['config']['session.path'] ?? '/',
                    $app['config']['session.domain'] ?? null,
                    $app['config']['session.secure'] ?? false,
                    $app['config']['session.same_site'] ?? 'lax'
                );
            } catch (\Exception $e) {
                Log::error('Error registering cookie jar singleton', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                // Return a basic cookie jar as fallback
                return new CookieJar();
            }
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            // Force the cookie jar to be resolved early in the application lifecycle
            $cookieJar = $this->app->make('cookie');
            
            // Ensure the cookie jar has been properly initialized
            if (!$cookieJar) {
                // Re-bind the cookie jar if it's not properly set
                $cookieJar = new CookieJar();
                $cookieJar->setDefaultPathAndDomain(
                    config('session.path', '/'),
                    config('session.domain', null), 
                    config('session.secure', false),
                    config('session.same_site', 'lax')
                );
                
                $this->app->instance('cookie', $cookieJar);
                
                Log::warning('Cookie jar had to be recreated during bootstrap');
            }
        } catch (\Exception $e) {
            Log::error('Error bootstrapping cookie jar', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
} 