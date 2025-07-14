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
                    
                    // Get all active root menus and filter by accessibility
                    $allMenus = MasterMenu::active()
                        ->rootMenus()
                        ->with(['children' => function ($query) {
                            $query->active()->orderBy('urutan');
                        }])
                        ->orderBy('urutan')
                        ->get();
                    
                    // Filter menus based on role access AND permission access
                    $menus = $allMenus->filter(function ($menu) {
                        if (!$menu->isAccessible()) {
                            return false;
                        }
                        
                        // Also filter children
                        $menu->setRelation('children', $menu->children->filter(function ($child) {
                            return $child->isAccessible();
                        }));
                        
                        return true;
                    });
                    
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
