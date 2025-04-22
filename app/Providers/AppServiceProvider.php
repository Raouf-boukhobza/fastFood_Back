<?php

namespace App\Providers;

use App\Models\Products;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Products::observe(\App\Observers\ProductsQuantityObserver::class);
    }
}
