<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Auth\CustomSessionGuard;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Register custom session guard
        Auth::extend('custom-session', function ($app, $name, array $config) {
            $guard = new CustomSessionGuard(
                $name,
                Auth::createUserProvider($config['provider']),
                $app['session.store'],
                $app['request']
            );
            
            // Explicitly set cookie jar, dispatcher, and request
            if ($app->resolved('cookie')) {
                $guard->setCookieJar($app['cookie']);
            }
            
            if ($app->resolved('events')) {
                $guard->setDispatcher($app['events']);
            }
            
            $guard->setRequest($app->refresh('request', $guard, 'setRequest'));
            
            return $guard;
        });
    }
}
