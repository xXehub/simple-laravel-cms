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
     * Handle panel index - redirect to dashboard
     */
    public function index()
    {
        return redirect()->route('panel.dashboard');
    }

    /**
     * Handle panel dashboard - Clean approach
     */
    public function dashboard()
    {
        $stats = [
            'users' => User::count(),
            'pages' => Page::count(),
            'menus' => MasterMenu::count(),
            'roles' => Role::count(),
            'permissions' => Permission::count(),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentPages = Page::latest()->take(5)->get();

        return view('panel.dashboard', compact('stats', 'recentUsers', 'recentPages'));
    }
}
