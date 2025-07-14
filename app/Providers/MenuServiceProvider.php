<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\MasterMenu;
use Illuminate\Support\Facades\Auth;

class MenuServiceProvider extends ServiceProvider
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
        View::composer(['layouts.app', 'layouts.app-with-sidebar', 'components.app', 'components.layout.app'], function ($view) {
            $menus = collect();
            $isAdmin = false;
            
            if (Auth::check()) {
                try {
                    $user = Auth::user();
                    $isAdmin = $user->hasRole('admin');
                    
                    // Get all active root menus with all their children loaded
                    $allMenus = MasterMenu::active()
                        ->rootMenus()
                        ->with(['children' => function ($query) {
                            $query->active()->orderBy('urutan')->with(['children' => function ($subQuery) {
                                $subQuery->active()->orderBy('urutan')->with(['children' => function ($subSubQuery) {
                                    $subSubQuery->active()->orderBy('urutan');
                                }]);
                            }]);
                        }])
                        ->orderBy('urutan')
                        ->get();
                    
                    // Let the helper functions handle all permission filtering
                    // Don't filter here, let the view components handle it
                    $menus = $allMenus;
                    
                } catch (\Exception $e) {
                    // Log error but don't break the page
                    \Log::error('Error loading user menus: ' . $e->getMessage());
                }
            }
            
            $view->with([
                'userMenus' => $menus,
                'isAdmin' => $isAdmin
            ]);
        });
    }
}
