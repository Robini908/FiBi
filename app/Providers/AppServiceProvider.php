<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cookie\CookieJar;
use Illuminate\Support\Facades\Cookie;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        
        // Ensure cookie jar is properly configured
        $cookieJar = $this->app->make(CookieJar::class);
        $cookieJar->setDefaultPathAndDomain(
            config('session.path', '/'),
            config('session.domain', null),
            config('session.secure', false),
            config('session.same_site', 'lax')
        );
        
        // Register Blade components
        \Illuminate\Support\Facades\Blade::component('livewire-modal', \App\View\Components\LivewireModal::class);
        
        // Register Livewire components for modals
        if (class_exists(\Livewire\Livewire::class)) {
            \Livewire\Livewire::component('library.authors.author-form', \App\Livewire\Library\Authors\AuthorForm::class);
            \Livewire\Livewire::component('library.authors.author-view', \App\Livewire\Library\Authors\AuthorView::class);
            \Livewire\Livewire::component('library.authors.author-delete', \App\Livewire\Library\Authors\AuthorDelete::class);
            \Livewire\Livewire::component('library.authors.author-management', \App\Http\Livewire\Library\Authors\AuthorManagement::class);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (config('app.env') === 'local') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
        
        // Make sure Cookie singleton is properly bound
        $this->app->singleton('cookie', function ($app) {
            $config = $app->make('config')->get('session');
            
            return (new CookieJar)->setDefaultPathAndDomain(
                $config['path'] ?? '/',
                $config['domain'] ?? null,
                $config['secure'] ?? false,
                $config['same_site'] ?? 'lax'
            );
        });
        
        // Register Agent as a singleton
        $this->app->singleton('agent', function () {
            return new \Jenssegers\Agent\Agent();
        });
    }
}
