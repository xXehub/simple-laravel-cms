<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Route;

echo "=== DEBUGGING MIDDLEWARE ISSUES ===\n\n";

// 1. Check middleware_list data in database
echo "1. MIDDLEWARE_LIST IN DATABASE:\n";
$panelMenus = App\Models\MasterMenu::whereIn('slug', ['panel/users', 'panel/roles', 'panel/permissions', 'panel/menus'])
    ->get(['id', 'nama_menu', 'slug', 'middleware_list']);

foreach ($panelMenus as $menu) {
    echo "  {$menu->nama_menu} ({$menu->slug}):\n";
    echo "    Raw: " . var_export($menu->middleware_list, true) . "\n";
    echo "    Parsed: " . var_export($menu->getMiddlewareList(), true) . "\n";
    echo "    Type: " . gettype($menu->middleware_list) . "\n\n";
}

// 2. Check actual registered routes and their middleware
echo "2. REGISTERED ROUTES MIDDLEWARE:\n";
$routeCollection = Route::getRoutes();
$panelRoutes = [];

foreach ($routeCollection as $route) {
    $name = $route->getName();
    if ($name && str_starts_with($name, 'panel.')) {
        $uri = $route->uri();
        // Only check main routes, not sub-routes
        if (in_array($uri, ['panel/users', 'panel/roles', 'panel/permissions', 'panel/menus'])) {
            $panelRoutes[] = [
                'name' => $name,
                'uri' => $uri,
                'middleware' => $route->middleware(),
                'action' => $route->getActionName()
            ];
        }
    }
}

foreach ($panelRoutes as $route) {
    echo "  Route: {$route['name']} ({$route['uri']})\n";
    echo "    Middleware: " . implode(', ', $route['middleware']) . "\n";
    echo "    Action: {$route['action']}\n\n";
}

// 3. Check if routes are registered by DynamicRouteService or web.php
echo "3. ROUTE REGISTRATION SOURCE:\n";
$webRoutesFile = file_get_contents('routes/web.php');
if (str_contains($webRoutesFile, 'panel/users') || str_contains($webRoutesFile, 'UserController')) {
    echo "  Routes are registered in routes/web.php (STATIC)\n";
    echo "  This means middleware_list from database is IGNORED!\n\n";
} else {
    echo "  Routes are likely registered by DynamicRouteService (DYNAMIC)\n";
    echo "  middleware_list from database should apply\n\n";
}

// 4. Check cache status
echo "4. CACHE STATUS:\n";
$cacheStatus = App\Console\Commands\CacheDynamicRoutes::hasCachedRoutes();
echo "  Has cached routes: " . ($cacheStatus ? 'YES' : 'NO') . "\n";

if ($cacheStatus) {
    echo "  Cached routes are being used, database changes ignored until cache cleared!\n";
    $cachedRoutes = App\Console\Commands\CacheDynamicRoutes::getCachedRoutes();
    echo "  Cached routes count: " . count($cachedRoutes) . "\n";
    
    foreach ($cachedRoutes as $cached) {
        if (in_array($cached['slug'], ['panel/users', 'panel/roles', 'panel/permissions', 'panel/menus'])) {
            echo "    {$cached['slug']}: " . implode(', ', $cached['middleware'] ?? []) . "\n";
        }
    }
}

echo "\n=== CONCLUSION ===\n";
echo "If routes are in web.php OR cache is active, middleware_list changes won't apply!\n";
echo "Solution: Clear cache with 'php artisan optimize:clear' and check routes/web.php\n";
