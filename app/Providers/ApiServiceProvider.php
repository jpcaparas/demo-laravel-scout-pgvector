<?php

namespace App\Providers;

use ApiPlatform\State\ProviderInterface;
use App\State\Provider\RestaurantSearchProvider;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(RestaurantSearchProvider::class, function () {
            return new RestaurantSearchProvider;
        });

        $this->app->tag([RestaurantSearchProvider::class], ProviderInterface::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
