<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterMenu;

class RedirectController extends Controller
{
    /**
     * Redirect to the first accessible child menu
     */
    public function redirectToChild(Request $request)
    {
        // Get current route to find the parent menu
        $currentSlug = $request->path();
        
        $parentMenu = MasterMenu::where('slug', $currentSlug)
            ->where('is_active', true)
            ->first();

        if (!$parentMenu) {
            abort(404, 'Page not found');
        }

        // Check if user has access to this parent menu
        if (!$parentMenu->isAccessible()) {
            if (auth()->check()) {
                abort(403, 'Access denied to this section');
            } else {
                return redirect()->route('login')->with('error', 'Please login to access this section');
            }
        }

        // Find first accessible child
        $firstChild = $parentMenu->children()
            ->where('is_active', true)
            ->orderBy('urutan')
            ->get()
            ->first(function ($child) {
                return $child->isAccessible();
            });

        if ($firstChild) {
            return redirect('/' . ltrim($firstChild->slug, '/'))
                ->with('info', "Redirected to {$firstChild->nama_menu}");
        }

        // If no accessible children, show overview page
        return app(DynamicController::class)->handleDynamicPage($currentSlug);
    }

    /**
     * Redirect to dashboard for admin panel root
     */
    public function redirectToDashboard()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        return redirect()->route('panel.dashboard');
    }
}
