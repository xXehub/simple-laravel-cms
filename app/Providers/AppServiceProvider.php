<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use App\View\Components\Sidebar;
use App\View\Components\SidebarMenu;
use App\Services\MenuService;

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
        // Register Blade components
        Blade::component('sidebar', Sidebar::class);
        Blade::component('sidebar-menu', SidebarMenu::class);

        // Share navbar menus with all views
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $menuService = app(MenuService::class);
                $navbarMenus = $menuService->getNavbarMenus();
                $view->with('navbarMenus', $navbarMenus);
            } else {
                $view->with('navbarMenus', []);
            }
        });
    }
}
