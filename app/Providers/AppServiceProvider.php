<?php

namespace App\Providers;

use App\State\RestaurantSearchStateProcessor;
use Illuminate\Support\ServiceProvider;
use Monolog\Processor\ProcessorInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->tag([RestaurantSearchStateProcessor::class], ProcessorInterface::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
