<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Services\DynamicRouteService;

class DynamicRouteServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(DynamicRouteService::class, function () {
            return new DynamicRouteService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register routes if database is available
        // Allow registration in both web requests and console (for testing)
        if ($this->databaseIsAvailable()) {
            $this->registerDynamicRoutes();
        }
    }

    /**
     * Register dynamic routes from database
     */
    protected function registerDynamicRoutes(): void
    {
        try {
            $dynamicRouteService = $this->app->make(DynamicRouteService::class);
            $dynamicRouteService->registerRoutes();
        } catch (\Exception $e) {
            // Log error but don't break the application
            if (function_exists('logger')) {
                logger()->error('Failed to register dynamic routes: ' . $e->getMessage());
            }
        }
    }

    /**
     * Check if database is available
     */
    protected function databaseIsAvailable(): bool
    {
        try {
            \DB::connection()->getPdo();
            
            // Check if the master_menus table exists
            return \Schema::hasTable('master_menus');
        } catch (\Exception $e) {
            return false;
        }
    }
}
