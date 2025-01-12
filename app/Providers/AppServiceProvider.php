<?php

namespace App\Providers;

use App\State\RestaurantChatStateProcessor;
use ApiPlatform\State\ProcessorInterface;
use App\State\RestaurantSearchStateProcessor;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->tag([RestaurantSearchStateProcessor::class], ProcessorInterface::class);
		$this->app->tag(RestaurantChatStateProcessor::class, ProcessorInterface::class);
    }
}
