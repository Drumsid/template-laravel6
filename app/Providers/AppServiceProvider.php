<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        view()->composer('layouts.backend.partial.sidebar', function ($view) {
            $view->with('postApprove', \App\Post::where('is_approved', false)->get());
        });
        view()->composer('layouts.frontend.partial.footer', function ($view) {
            $view->with('allCategories', \App\Category::all());
        });
    }
}
