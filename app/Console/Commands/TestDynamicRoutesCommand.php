<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DynamicRouteService;
use App\Models\MasterMenu;

class TestDynamicRoutesCommand extends Command
{
    protected $signature = 'test:dynamic-routes';
    protected $description = 'Test dynamic routes registration with auto-scan';

    public function handle()
    {
        $this->info('=== Testing Dynamic Routes Registration ===');
        
        // Clear existing routes
        $this->line('Clearing route cache...');
        \Artisan::call('route:clear');
        
        // Get menu with controller
        $menu = MasterMenu::where('controller_class', 'App\Http\Controllers\Panel\UserController')->first();
        
        if (!$menu) {
            $this->error('UserController menu not found in database');
            return;
        }
        
        $this->info('Found menu: ' . $menu->nama_menu);
        $this->info('Controller: ' . $menu->controller_class);
        $this->info('Slug: ' . $menu->slug);
        $this->line('');
        
        // Test auto-scan methods
        $this->info('Testing auto-scan methods...');
        $methods = $menu->getAllControllerMethods();
        
        $this->info('Methods found: ' . count($methods));
        foreach ($methods as $method) {
            $this->line("- {$method['method']} ({$method['http_method']}) {$method['route_pattern']}");
        }
        
        $this->line('');
        
        // Test actual route registration
        $this->info('Testing actual route registration...');
        $service = new \App\Services\DynamicRouteService();
        
        try {
            $service->registerRoutes();
            $this->info('✅ Routes registered successfully!');
            
            // Show registered routes
            $this->line('');
            $this->info('Checking some registered routes:');
            
            $routes = \Route::getRoutes();
            $userRoutes = [];
            
            foreach ($routes as $route) {
                $uri = $route->uri();
                if (str_starts_with($uri, 'panel/users')) {
                    $userRoutes[] = [
                        'method' => implode('|', $route->methods()),
                        'uri' => $uri,
                        'name' => $route->getName(),
                        'action' => $route->getActionName()
                    ];
                }
            }
            
            if (count($userRoutes) > 0) {
                $this->info('UserController routes found: ' . count($userRoutes));
                foreach (array_slice($userRoutes, 0, 5) as $route) { // Show first 5
                    $this->line("  {$route['method']} /{$route['uri']} -> {$route['action']}");
                }
            } else {
                $this->warn('No UserController routes found in registered routes');
            }
            
        } catch (\Exception $e) {
            $this->error('Failed to register routes: ' . $e->getMessage());
        }
        
        $this->line('');
        $this->info('Dynamic routes test completed!');
    }
}
