<?php

namespace App\Services;

use App\Models\MasterMenu;
use App\Console\Commands\CacheDynamicRoutes;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class DynamicRouteService
{
    /**
     * Register all dynamic routes from the database
     */
    public function registerRoutes(): void
    {
        try {
            // Try to use cached routes first
            if (CacheDynamicRoutes::hasCachedRoutes()) {
                $this->registerCachedRoutes();
                return;
            }

            // Fallback to database if no cache
            $this->registerRoutesFromDatabase();
        } catch (\Exception $e) {
            Log::error('Failed to register dynamic routes', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
    }

    /**
     * Register routes from cache
     */
    protected function registerCachedRoutes(): void
    {
        $cachedRoutes = CacheDynamicRoutes::getCachedRoutes();

        if (!$cachedRoutes) {
            $this->registerRoutesFromDatabase();
            return;
        }

        $count = 0;
        foreach ($cachedRoutes as $routeData) {
            try {
                $this->registerCachedRoute($routeData);
                $count++;
            } catch (\Exception $e) {
                Log::warning('Failed to register cached route', [
                    'route_data' => $routeData,
                    'error' => $e->getMessage()
                ]);
            }
        }

        Log::info('Dynamic routes registered from cache', [
            'total_routes' => $count
        ]);
    }

    /**
     * Register routes from database (fallback)
     */
    protected function registerRoutesFromDatabase(): void
    {
        $menus = MasterMenu::where('is_active', true)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->orderBy('route_type')
            ->orderBy('urutan')
            ->get();

        $totalRoutes = 0;
        foreach ($menus as $menu) {
            if ($menu->shouldUseController()) {
                // Use auto-scanning for controller menus
                $totalRoutes += $this->registerMenuRoutesWithAutoScan($menu);
            } else {
                // Use traditional registration for view/dynamic routes
                $this->registerRoute($menu);
                $totalRoutes++;
            }
        }

        Log::info('Dynamic routes registered from database', [
            'total_routes' => $totalRoutes
        ]);
    }

    /**
     * Register a single cached route
     */
    protected function registerCachedRoute(array $routeData): void
    {
        $url = ltrim($routeData['slug'], '/');
        $middleware = $routeData['middleware'] ?? [];
        $routeName = $routeData['route_name'];

        if (!empty($routeData['controller_class'])) {
            $this->registerControllerRouteFromCache($routeData, $url, $middleware, $routeName);
        } elseif (!empty($routeData['view_path'])) {
            $this->registerViewRouteFromCache($routeData, $url, $middleware, $routeName);
        } else {
            $this->registerDynamicRouteFromCache($routeData, $url, $middleware, $routeName);
        }
    }

    /**
     * Register controller route from cache
     */
    protected function registerControllerRouteFromCache(array $routeData, string $url, array $middleware, ?string $routeName): void
    {
        $controllerClass = $routeData['controller_class'];
        $method = $routeData['controller_method'] ?? 'index';

        if (!class_exists($controllerClass)) {
            return;
        }

        $route = Route::get($url, [$controllerClass, $method]);

        if (!empty($middleware)) {
            $route->middleware($middleware);
        }

        if ($routeName) {
            $route->name($routeName);
        }
    }

    /**
     * Register view route from cache
     */
    protected function registerViewRouteFromCache(array $routeData, string $url, array $middleware, ?string $routeName): void
    {
        $route = Route::get($url, function () use ($routeData) {
            return view($routeData['view_path'], [
                'title' => $routeData['meta_title'] ?? 'Page',
                'content' => 'Dynamic content for ' . $routeData['view_path']
            ]);
        });

        if (!empty($middleware)) {
            $route->middleware($middleware);
        }

        if ($routeName) {
            $route->name($routeName);
        }
    }

    /**
     * Register dynamic route from cache
     */
    protected function registerDynamicRouteFromCache(array $routeData, string $url, array $middleware, ?string $routeName): void
    {
        // Add visit tracking middleware
        $middleware[] = 'track_visit';
        
        $route = Route::get($url, [\App\Http\Controllers\DynamicController::class, 'handleDynamicPage'])
            ->defaults('menu_id', $routeData['id']);

        if (!empty($middleware)) {
            $route->middleware($middleware);
        }

        if ($routeName) {
            $route->name($routeName);
        }
    }

    /**
     * Register a single route from menu configuration
     */
    protected function registerRoute(MasterMenu $menu): void
    {
        // Skip if no slug configured
        if (!$menu->slug) {
            return;
        }

        $url = $this->prepareUrl($menu);
        $middleware = $menu->getMiddlewareList();
        
        // Add visit tracking middleware to all dynamic routes
        $middleware[] = 'track_visit';
        
        $routeName = $menu->route_name;

        try {
            if ($menu->shouldUseController()) {
                $this->registerControllerRoute($menu, $url, $middleware, $routeName);
            } elseif ($menu->shouldUseView()) {
                $this->registerViewRoute($menu, $url, $middleware, $routeName);
            } else {
                $this->registerDynamicRoute($menu, $url, $middleware, $routeName);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to register route', [
                'menu_id' => $menu->id,
                'menu_name' => $menu->nama_menu,
                'url' => $url,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Register a controller-based route
     * DEPRECATED: Gunakan registerMenuRoutesWithAutoScan() untuk auto-scan methods
     */
    protected function registerControllerRoute(MasterMenu $menu, string $url, array $middleware, string $routeName): void
    {
        $controllerClass = $menu->getControllerClass();
        $method = 'index'; // Default ke index method untuk backward compatibility

        if (!class_exists($controllerClass)) {
            Log::warning('Controller class not found', [
                'controller' => $controllerClass,
                'menu_id' => $menu->id
            ]);
            return;
        }

        if (!method_exists($controllerClass, $method)) {
            Log::warning('Controller method not found', [
                'controller' => $controllerClass,
                'method' => $method,
                'menu_id' => $menu->id
            ]);
            return;
        }

        $route = Route::get($url, [$controllerClass, $method]);

        if (!empty($middleware)) {
            $route->middleware($middleware);
        }

        if ($routeName) {
            $route->name($routeName);
        }

        // Add route parameters if configured
        if ($menu->route_parameters) {
            $parameters = json_decode($menu->route_parameters, true);
            if (is_array($parameters)) {
                foreach ($parameters as $key => $pattern) {
                    $route->where($key, $pattern);
                }
            }
        }
    }

    /**
     * Register a view-based route
     */
    protected function registerViewRoute(MasterMenu $menu, string $url, array $middleware, string $routeName): void
    {
        $viewPath = $menu->getViewPath();

        if (!view()->exists($viewPath)) {
            Log::warning('View not found', [
                'view' => $viewPath,
                'menu_id' => $menu->id
            ]);
            return;
        }

        $route = Route::get($url, function () use ($menu, $viewPath) {
            $data = [
                'menu' => $menu,
                'title' => $menu->meta_title ?: $menu->nama_menu,
                'description' => $menu->meta_description
            ];

            return view($viewPath, $data);
        });

        if (!empty($middleware)) {
            $route->middleware($middleware);
        }

        if ($routeName) {
            $route->name($routeName);
        }
    }

    /**
     * Register a dynamic route (handled by DynamicController)
     */
    protected function registerDynamicRoute(MasterMenu $menu, string $url, array $middleware, string $routeName): void
    {
        $route = Route::get($url, function (Request $request) use ($menu) {
            return app('App\Http\Controllers\DynamicController')->handleDynamicRoute($menu, $request);
        });

        if (!empty($middleware)) {
            $route->middleware($middleware);
        }

        if ($routeName) {
            $route->name($routeName);
        }
    }

    /**
     * Prepare URL for route registration
     */
    protected function prepareUrl(MasterMenu $menu): string
    {
        $url = ltrim($menu->slug, '/');

        // Handle root URL
        if (empty($url) || $url === '/') {
            return '/';
        }

        // Add leading slash if not present
        if (!str_starts_with($url, '/')) {
            $url = '/' . $url;
        }

        return $url;
    }

    /**
     * Get all registered dynamic routes
     */
    public function getRegisteredRoutes(): array
    {
        $routes = [];
        $routeCollection = Route::getRoutes();

        foreach ($routeCollection as $route) {
            $name = $route->getName();
            if ($name && (str_starts_with($name, 'panel.') || str_starts_with($name, 'dynamic.'))) {
                $routes[] = [
                    'name' => $name,
                    'uri' => $route->uri(),
                    'methods' => $route->methods(),
                    'middleware' => $route->middleware(),
                    'action' => $route->getActionName()
                ];
            }
        }

        return $routes;
    }

    /**
     * Clear and re-register all dynamic routes
     */
    public function refreshRoutes(): void
    {
        // Clear route cache
        if (app()->environment('production')) {
            \Artisan::call('route:clear');
        }

        // Re-register routes
        $this->registerRoutes();
    }

    /**
     * Check if a route exists in the current registration
     */
    public function routeExists(string $routeName): bool
    {
        return Route::has($routeName);
    }

    /**
     * Get route by menu ID
     */
    public function getRouteByMenuId(int $menuId): ?array
    {
        $menu = MasterMenu::find($menuId);
        if (!$menu) {
            return null;
        }

        $routeName = $menu->getFullRouteName();
        if (!$this->routeExists($routeName)) {
            return null;
        }

        $route = Route::getRoutes()->getByName($routeName);
        return [
            'name' => $routeName,
            'uri' => $route->uri(),
            'methods' => $route->methods(),
            'middleware' => $route->middleware(),
            'action' => $route->getActionName()
        ];
    }

    /**
     * Register menu routes with auto-scan functionality
     * NEW IMPLEMENTATION - Auto scan all controller methods
     */
    protected function registerMenuRoutesWithAutoScan(MasterMenu $menu): int
    {
        $registeredCount = 0;

        try {
            // Get all methods from controller using auto-scanner
            $controllerMethods = $menu->getAllControllerMethods();

            if (empty($controllerMethods)) {
                Log::warning('No controller methods found for menu', [
                    'menu_id' => $menu->id,
                    'controller_class' => $menu->controller_class
                ]);
                return 0;
            }

            $baseSlug = rtrim($menu->slug, '/');
            $middleware = $menu->getMiddlewareList();

            // Register route untuk setiap method yang ditemukan
            foreach ($controllerMethods as $methodInfo) {
                $success = $this->registerSingleControllerMethod(
                    $menu,
                    $methodInfo,
                    $baseSlug,
                    $middleware
                );

                if ($success) {
                    $registeredCount++;
                }
            }

            Log::info('Auto-scan routes registered for menu', [
                'menu_id' => $menu->id,
                'controller_class' => $menu->controller_class,
                'methods_registered' => $registeredCount
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to register auto-scan routes for menu', [
                'menu_id' => $menu->id,
                'controller_class' => $menu->controller_class,
                'error' => $e->getMessage()
            ]);
        }

        return $registeredCount;
    }

    /**
     * Register single controller method with proper RESTful routing
     */
    protected function registerSingleControllerMethod(
        MasterMenu $menu,
        array $methodInfo,
        string $baseSlug,
        array $middleware
    ): bool {
        try {
            $methodName = $methodInfo['method'];
            $httpMethod = $methodInfo['http_method'];
            $routePattern = $methodInfo['route_pattern'];
            $controllerClass = $menu->controller_class;

            // Build full route URL
            $fullRoute = $baseSlug . $routePattern;

            // Generate route name
            $routeName = $this->generateRouteName($menu, $methodName);

            // Register route dengan HTTP method yang sesuai
            $route = null;
            $httpMethods = explode(',', $httpMethod);

            // Jika ada multiple HTTP methods (misal: PUT,PATCH), gunakan Route::match
            if (count($httpMethods) > 1) {
                $cleanMethods = array_map('trim', $httpMethods);
                $route = Route::match($cleanMethods, $fullRoute, [$controllerClass, $methodName]);
            } else {
                // Single HTTP method
                $method = trim($httpMethods[0]);
                switch (strtoupper($method)) {
                    case 'GET':
                        $route = Route::get($fullRoute, [$controllerClass, $methodName]);
                        break;
                    case 'POST':
                        $route = Route::post($fullRoute, [$controllerClass, $methodName]);
                        break;
                    case 'PUT':
                        $route = Route::put($fullRoute, [$controllerClass, $methodName]);
                        break;
                    case 'PATCH':
                        $route = Route::patch($fullRoute, [$controllerClass, $methodName]);
                        break;
                    case 'DELETE':
                        $route = Route::delete($fullRoute, [$controllerClass, $methodName]);
                        break;
                }
            }

            if ($route) {
                // Apply middleware
                if (!empty($middleware)) {
                    $route->middleware($middleware);
                }

                // Set route name
                if ($routeName) {
                    $route->name($routeName);
                }

                return true;
            }

        } catch (\Exception $e) {
            Log::warning('Failed to register controller method route', [
                'menu_id' => $menu->id,
                'method' => $methodInfo['method'] ?? 'unknown',
                'error' => $e->getMessage()
            ]);
        }

        return false;
    }

    /**
     * Generate route name for auto-scanned method
     */
    protected function generateRouteName(MasterMenu $menu, string $methodName): string
    {
        // Base route name dari menu
        $baseName = '';

        if ($menu->route_name) {
            // Jika menu punya route_name, extract base-nya (hapus .index jika ada)
            $routeName = $menu->route_name;
            if (str_ends_with($routeName, '.index')) {
                $baseName = substr($routeName, 0, -6); // Remove .index
            } else {
                $baseName = $routeName;
            }
        } else {
            // Generate dari slug
            $slug = str_replace('/', '.', trim($menu->slug, '/'));
            $baseName = $slug;
        }

        // Untuk index method, gunakan base name saja
        if ($methodName === 'index') {
            return $baseName;
        }

        // Untuk method lain, tambahkan nama method
        return $baseName . '.' . $methodName;
    }
}
