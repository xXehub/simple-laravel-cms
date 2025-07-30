<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Page;
use App\Models\MasterMenu;
use App\Models\Setting;
use Spatie\Permission\Models\Role;

class DashboardCacheServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Clear dashboard stats cache when users are created/updated/deleted
        User::created(function ($user) {
            Cache::forget('dashboard_stats');
            Cache::forget('dashboard_system_overview');
            Cache::forget('dashboard_recent_users');
        });

        User::updated(function ($user) {
            Cache::forget('dashboard_stats');
            Cache::forget('dashboard_system_overview');
            Cache::forget('dashboard_recent_users');
        });

        User::deleted(function ($user) {
            Cache::forget('dashboard_stats');
            Cache::forget('dashboard_system_overview');
            Cache::forget('dashboard_recent_users');
        });

        // Clear dashboard cache when pages are modified
        Page::created(function ($page) {
            Cache::forget('dashboard_stats');
            Cache::forget('dashboard_system_overview');
        });

        Page::updated(function ($page) {
            Cache::forget('dashboard_stats');
            Cache::forget('dashboard_system_overview');
        });

        Page::deleted(function ($page) {
            Cache::forget('dashboard_stats');
            Cache::forget('dashboard_system_overview');
        });

        // Clear dashboard cache when menus are modified
        MasterMenu::created(function ($menu) {
            Cache::forget('dashboard_stats');
            Cache::forget('dashboard_system_overview');
            Cache::forget('dashboard_most_visited');
        });

        MasterMenu::updated(function ($menu) {
            Cache::forget('dashboard_stats');
            Cache::forget('dashboard_system_overview');
            Cache::forget('dashboard_most_visited');
        });

        MasterMenu::deleted(function ($menu) {
            Cache::forget('dashboard_stats');
            Cache::forget('dashboard_system_overview');
            Cache::forget('dashboard_most_visited');
        });

        // Clear dashboard cache when settings are modified
        Setting::created(function ($setting) {
            Cache::forget('dashboard_system_overview');
        });

        Setting::updated(function ($setting) {
            Cache::forget('dashboard_system_overview');
        });

        Setting::deleted(function ($setting) {
            Cache::forget('dashboard_system_overview');
        });

        // Clear dashboard cache when roles are modified
        Role::created(function ($role) {
            Cache::forget('dashboard_stats');
        });

        Role::updated(function ($role) {
            Cache::forget('dashboard_stats');
        });

        Role::deleted(function ($role) {
            Cache::forget('dashboard_stats');
        });
    }
}
