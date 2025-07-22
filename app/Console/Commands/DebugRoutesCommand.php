<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DynamicRouteService;
use App\Models\MasterMenu;

class DebugRoutesCommand extends Command
{
    protected $signature = 'debug:routes';
    protected $description = 'Debug routes registration issue';

    public function handle()
    {
        $this->info('=== Debugging Routes Registration ===');
        
        // 1. Check database connection
        $this->line('1. Checking database connection...');
        try {
            \DB::connection()->getPdo();
            $this->info('   ✅ Database connected');
        } catch (\Exception $e) {
            $this->error('   ❌ Database connection failed: ' . $e->getMessage());
            return;
        }
        
        // 2. Check master_menus table
        $this->line('2. Checking master_menus table...');
        if (\Schema::hasTable('master_menus')) {
            $this->info('   ✅ master_menus table exists');
            $count = MasterMenu::count();
            $this->info("   ✅ Found {$count} menus in database");
        } else {
            $this->error('   ❌ master_menus table not found');
            return;
        }
        
        // 3. Check UserController menu
        $this->line('3. Checking UserController menu...');
        $userMenu = MasterMenu::where('controller_class', 'App\Http\Controllers\Panel\UserController')->first();
        if ($userMenu) {
            $this->info('   ✅ UserController menu found');
            $this->info("   - ID: {$userMenu->id}");
            $this->info("   - Name: {$userMenu->nama_menu}");
            $this->info("   - Slug: {$userMenu->slug}");
            $this->info("   - Active: " . ($userMenu->is_active ? 'Yes' : 'No'));
            $this->info("   - Should Use Controller: " . ($userMenu->shouldUseController() ? 'Yes' : 'No'));
        } else {
            $this->error('   ❌ UserController menu not found');
            return;
        }
        
        // 4. Test auto-scan
        $this->line('4. Testing auto-scan...');
        $methods = $userMenu->getAllControllerMethods();
        $this->info("   ✅ Found {" . count($methods) . "} methods");
        
        // 5. Manual route registration
        $this->line('5. Manually registering routes...');
        $service = new DynamicRouteService();
        $service->registerRoutes();
        $this->info('   ✅ Routes registration completed');
        
        // 6. Check registered routes
        $this->line('6. Checking registered routes...');
        $userRoutes = [];
        foreach (\Route::getRoutes() as $route) {
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
            $this->info("   ✅ Found {" . count($userRoutes) . "} UserController routes:");
            foreach ($userRoutes as $route) {
                $this->line("   - {$route['method']} /{$route['uri']} -> {$route['name']}");
            }
        } else {
            $this->error('   ❌ No UserController routes found');
        }
        
        $this->line('');
        $this->info('Debug completed!');
    }
}
