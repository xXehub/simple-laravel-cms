<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Page;
use App\Models\MasterMenu;
use App\Models\Setting;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle panel dashboard - Fully dynamic using database view_path
     */
    public function index(Request $request)
    {
        // Get current menu from request or find by slug
        $currentSlug = $request->route()->uri ?? 'panel/dashboard';
        $menu = MasterMenu::where('slug', $currentSlug)->first();

        // Get dynamic view path from database
        $viewPath = $menu?->getDynamicViewPath() ?? 'panel.dashboard';

        // Prepare dashboard data
        $stats = [
            'users' => User::count(),
            'pages' => Page::count(),
            'menus' => MasterMenu::count(),
            'roles' => Role::count(),
            'permissions' => Permission::count(),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentPages = Page::latest()->take(5)->get();

        // Return dynamic view with all necessary data
        return view($viewPath, compact('stats', 'recentUsers', 'recentPages', 'menu'));
    }

    /**
     * Legacy method for backward compatibility
     */
    public function dashboard()
    {
        return $this->index(request());
    }
}
