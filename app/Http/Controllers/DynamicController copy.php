<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\MasterMenu;
use Illuminate\Support\Facades\Log;

class DynamicController extends Controller
{
    /**
     * Handle welcome page
     */
    public function handleWelcome()
    {
        return view('landing');
    }

    /**
     * Handle user profile page
     */
    public function handleProfile()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        return view('profile.show', ['user' => auth()->user()]);
    }

    /**
     * Handle dynamic CMS pages and menu-based routing
     */
    public function handleDynamicPage($slug)
    {
        // First, try to find a menu with this slug
        $menu = MasterMenu::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if ($menu) {
            return $this->handleMenuRoute($menu);
        }

        // If no menu found, try to find a CMS page
        $page = Page::where('slug', $slug)
            ->where('is_published', true)
            ->first();

        if ($page) {
            return $this->handlePageRoute($page);
        }

        // If neither found, return 404
        abort(404, "Page not found: {$slug}");
    }

    /**
     * Handle menu-based routing
     */
    protected function handleMenuRoute(MasterMenu $menu)
    {
        // Check permissions
        if (!$menu->isAccessible()) {
            if (auth()->check()) {
                abort(403, 'Access denied to this section');
            } else {
                return redirect()->route('login')->with('error', 'Please login to access this section');
            }
        }

        // Dispatch to appropriate handler
        if ($menu->shouldUseController()) {
            return $this->dispatchToController($menu);
        } elseif ($menu->shouldUseView()) {
            return $this->renderMenuView($menu);
        } else {
            // Fallback for parent menus or redirects
            return $this->handleMenuFallback($menu);
        }
    }

    /**
     * Handle CMS page routing
     */
    protected function handlePageRoute(Page $page)
    {
        // Use simple template for all pages
        return view('pages.show', compact('page'));
    }

    /**
     * Dispatch to controller method
     * DEPRECATED: Controller routing sekarang menggunakan auto-scan di DynamicRouteService
     */
    protected function dispatchToController(MasterMenu $menu)
    {
        $controllerClass = $menu->getControllerClass();
        $method = 'index'; // Default method untuk backward compatibility

        if (!class_exists($controllerClass)) {
            Log::error("Controller not found: {$controllerClass}");
            abort(500, 'Controller not found');
        }

        if (!method_exists($controllerClass, $method)) {
            Log::error("Method not found: {$controllerClass}@{$method}");
            abort(500, 'Controller method not found');
        }

        $controller = app($controllerClass);
        
        // Use reflection to check if method expects parameters
        $reflection = new \ReflectionMethod($controllerClass, $method);
        $parameters = $reflection->getParameters();
        
        // If method expects Request parameter, pass it
        if (count($parameters) > 0 && $parameters[0]->getType()?->getName() === 'Illuminate\Http\Request') {
            return $controller->{$method}(request());
        }
        
        return $controller->{$method}();
    }

    /**
     * Render view from database view_path
     */
    protected function renderMenuView(MasterMenu $menu)
    {
        $viewPath = $menu->getDynamicViewPath();
        
        if (!$viewPath) {
            // Fallback to default template
            return view('dynamic.default', [
                'menu' => $menu,
                'title' => $menu->nama_menu,
                'description' => $menu->meta_description,
                'pakai_sidebar' => $menu->route_type === 'admin',
                'pakaiTopBar' => $menu->route_type !== 'admin',
            ]);
        }
        
        // Check if view exists
        if (!view()->exists($viewPath)) {
            Log::warning("View not found: {$viewPath} for menu: {$menu->nama_menu}");
            
            // Fallback to default template
            return view('dynamic.default', [
                'menu' => $menu,
                'title' => $menu->nama_menu,
                'description' => $menu->meta_description,
                'pakai_sidebar' => $menu->route_type === 'admin',
                'pakaiTopBar' => $menu->route_type !== 'admin',
            ]);
        }
        
        // Render the dynamic view with menu context
        return view($viewPath, [
            'menu' => $menu,
            'title' => $menu->meta_title ?? $menu->nama_menu,
            'description' => $menu->meta_description,
            'pakai_sidebar' => $menu->route_type === 'admin',
            'pakaiTopBar' => $menu->route_type !== 'admin',
        ]);
    }

    /**
     * Handle menu fallback (for parent menus, redirects, etc.)
     */
    protected function handleMenuFallback(MasterMenu $menu)
    {
        // If it's a parent menu, redirect to first accessible child
        $firstChild = $menu->children()
            ->where('is_active', true)
            ->orderBy('urutan')
            ->first();

        if ($firstChild && $firstChild->isAccessible()) {
            return redirect('/' . ltrim($firstChild->slug, '/'));
        }

        // If no accessible children, show a default page
        return view('dynamic.default', [
            'menu' => $menu,
            'title' => $menu->meta_title ?: $menu->nama_menu,
            'content' => $this->generateMenuOverview($menu),
            'description' => $menu->meta_description
        ]);
    }

    /**
     * Handle dynamic route from MasterMenu configuration
     */
    public function handleDynamicRoute(MasterMenu $menu, Request $request)
    {
        try {
            // Check if user has access to this menu
            if (!$menu->isAccessible()) {
                if (!auth()->check()) {
                    return redirect()->route('login');
                }
                abort(403, 'Unauthorized access to this page.');
            }

            // Check if menu has a specific view configured
            if ($menu->view_path && view()->exists($menu->view_path)) {
                return $this->renderView($menu, $request);
            }

            // Check if menu links to a CMS page
            if ($menu->slug) {
                $page = Page::where('slug', $menu->slug)
                    ->where('is_published', true)
                    ->first();

                if ($page) {
                    return view('pages.show', [
                        'page' => $page,
                        'menu' => $menu,
                        'title' => $menu->meta_title ?: $page->title,
                        'description' => $menu->meta_description ?: $page->meta_description
                    ]);
                }
            }

            // Default fallback - render a generic dynamic page
            return $this->renderDefaultView($menu, $request);

        } catch (\Exception $e) {
            Log::error('Dynamic route error', [
                'menu_id' => $menu->id,
                'menu_name' => $menu->menu_name,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            if (config('app.debug')) {
                throw $e;
            }

            abort(500, 'Page could not be loaded.');
        }
    }

    /**
     * Render a specific view for the menu
     */
    protected function renderView(MasterMenu $menu, Request $request)
    {
        $data = [
            'menu' => $menu,
            'title' => $menu->meta_title ?: $menu->menu_name,
            'description' => $menu->meta_description,
            'request' => $request
        ];

        // Add any additional data from menu configuration
        if ($menu->view_data) {
            $additionalData = json_decode($menu->view_data, true);
            if (is_array($additionalData)) {
                $data = array_merge($data, $additionalData);
            }
        }

        return view($menu->view_path, $data);
    }

    /**
     * Render default view for dynamic menus
     */
    protected function renderDefaultView(MasterMenu $menu, Request $request)
    {
        $data = [
            'menu' => $menu,
            'title' => $menu->meta_title ?: $menu->menu_name,
            'description' => $menu->meta_description,
            'content' => $menu->menu_description ?: 'This is a dynamic page for ' . $menu->menu_name
        ];

        // Try to find a suitable view template
        $possibleViews = [
            "dynamic.{$menu->route_type}",
            "dynamic.{$menu->slug}",
            'dynamic.default'
        ];

        foreach ($possibleViews as $viewName) {
            if (view()->exists($viewName)) {
                return view($viewName, $data);
            }
        }

        // Ultimate fallback - create a simple view
        return response()->view('dynamic.fallback', $data);
    }

    /**
     * Handle API requests for dynamic routes
     */
    public function handleApiRoute(MasterMenu $menu, Request $request)
    {
        // Check access
        if (!$menu->isAccessible()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Return menu data as JSON
        return response()->json([
            'menu' => $menu->only(['id', 'menu_name', 'menu_description', 'slug']),
            'title' => $menu->meta_title ?: $menu->menu_name,
            'description' => $menu->meta_description,
            'type' => $menu->route_type
        ]);
    }

    /**
     * Generate menu overview content for parent menus
     */
    protected function generateMenuOverview(MasterMenu $menu): string
    {
        $children = $menu->children()
            ->where('is_active', true)
            ->orderBy('urutan')
            ->get();

        if ($children->isEmpty()) {
            $emptyIcon = $menu->icon;
            $emptyTitle = $menu->nama_menu;
            $emptyDesc = $menu->meta_description ?: 'This section is currently empty.';
            
            return "
                <div class='card'>
                    <div class='card-body text-center'>
                        <div class='empty'>
                            <div class='empty-icon'>
                                <i class='{$emptyIcon}' style='font-size: 3rem; color: #6c757d;'></i>
                            </div>
                            <p class='empty-title'>{$emptyTitle}</p>
                            <p class='empty-subtitle text-muted'>{$emptyDesc}</p>
                        </div>
                    </div>
                </div>
            ";
        }

        $cardsHtml = '';
        foreach ($children as $child) {
            if ($child->isAccessible()) {
                $childIcon = $child->icon;
                $childName = $child->nama_menu;
                $childUrl = url($child->slug);
                $childDesc = $child->meta_description ?: "Access {$child->nama_menu} section";
                
                $cardsHtml .= "
                    <div class='col-sm-6 col-lg-4'>
                        <div class='card card-sm h-100'>
                            <div class='card-body'>
                                <div class='row align-items-center'>
                                    <div class='col-auto'>
                                        <span class='bg-primary text-white avatar'>
                                            <i class='{$childIcon}'></i>
                                        </span>
                                    </div>
                                    <div class='col'>
                                        <div class='font-weight-medium'>
                                            <a href='{$childUrl}' class='text-reset text-decoration-none'>
                                                {$childName}
                                            </a>
                                        </div>
                                        <div class='text-muted'>{$childDesc}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                ";
            }
        }

        $menuIcon = $menu->icon;
        $menuName = $menu->nama_menu;
        $menuDesc = $menu->meta_description ?: 'Choose from the available options below.';

        return "
            <div class='card'>
                <div class='card-header'>
                    <h3 class='card-title'>
                        <i class='{$menuIcon}'></i> {$menuName}
                    </h3>
                </div>
                <div class='card-body'>
                    <p class='text-muted mb-4'>{$menuDesc}</p>
                    <div class='row g-3'>
                        {$cardsHtml}
                    </div>
                </div>
            </div>
        ";
    }
}
