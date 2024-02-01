<?php

namespace App\Providers;

use App\Models\ProductType;
use App\Observers\ProductTypeObserver;
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
        ProductType::observe(ProductTypeObserver::class);
    }
}
