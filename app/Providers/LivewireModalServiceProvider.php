<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LivewireModalServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Register our Livewire modal components
        Livewire::component('library.authors.author-form', \App\Livewire\Library\Authors\AuthorForm::class);
        Livewire::component('library.authors.author-view', \App\Livewire\Library\Authors\AuthorView::class);
        Livewire::component('library.authors.author-delete', \App\Livewire\Library\Authors\AuthorDelete::class);
    }
} 