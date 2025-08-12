<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use App\View\Components\Sidebar;
use App\View\Components\SidebarMenu;
use App\Services\MenuService;
use App\Services\ComponentRegistry;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register VisitTrackingService as singleton
        $this->app->singleton(\App\Services\VisitTrackingService::class);
        
        // Register ComponentRegistry as singleton
        $this->app->singleton(ComponentRegistry::class);
        $this->app->alias(ComponentRegistry::class, 'component-registry');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Blade components
        Blade::component('sidebar', Sidebar::class);
        Blade::component('sidebar-menu', SidebarMenu::class);

        // Share navbar menus with all views
        View::composer('*', function ($view) {
            $menuService = app(MenuService::class);
            $navbarMenus = $menuService->getNavbarMenus();
            $view->with('navbarMenus', $navbarMenus);
        });

        // Share global settings with all views
        View::composer('*', \App\View\Composers\SettingComposer::class);
    }
}
