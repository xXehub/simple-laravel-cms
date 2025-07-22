<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use App\Models\MasterMenu;

class CacheDynamicRoutes extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'routes:cache-dynamic 
                            {--clear : Clear the route cache}
                            {--refresh : Refresh the route cache}';

    /**
     * The console command description.
     */
    protected $description = 'Cache all dynamic routes from master_menus table for improved performance';

    /**
     * Cache key for dynamic routes
     */
    const CACHE_KEY = 'dynamic_routes_cache';
    const CACHE_TTL = 86400; // 24 hours

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('clear')) {
            return $this->clearCache();
        }

        if ($this->option('refresh')) {
            $this->clearCache();
        }

        return $this->cacheRoutes();
    }

    /**
     * Cache all dynamic routes
     */
    protected function cacheRoutes()
    {
        $this->info('Caching dynamic routes...');
        
        try {
            $routes = $this->generateRoutesData();
            
            Cache::put(self::CACHE_KEY, $routes, self::CACHE_TTL);
            
            $this->info("Successfully cached {$routes->count()} dynamic routes.");
            $this->table(
                ['Slug', 'Type', 'Controller/View', 'Middleware'],
                $routes->map(function($route) {
                    return [
                        $route['slug'],
                        $route['route_type'],
                        $route['controller_class'] ?: $route['view_path'],
                        implode(', ', $route['middleware'] ?? [])
                    ];
                })->toArray()
            );
            
            return 0;
        } catch (\Exception $e) {
            $this->error("Failed to cache routes: {$e->getMessage()}");
            return 1;
        }
    }

    /**
     * Clear the route cache
     */
    protected function clearCache()
    {
        Cache::forget(self::CACHE_KEY);
        $this->info('Dynamic route cache cleared successfully.');
        return 0;
    }

    /**
     * Generate routes data from database
     */
    protected function generateRoutesData()
    {
        return MasterMenu::active()
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->get()
            ->map(function($menu) {
                return [
                    'id' => $menu->id,
                    'slug' => $menu->slug,
                    'route_name' => $menu->route_name,
                    'route_type' => $menu->route_type ?? 'public',
                    'controller_class' => $menu->controller_class,
                    'controller_method' => $menu->controller_method ?? 'index',
                    'view_path' => $menu->view_path,
                    'middleware' => $menu->getMiddleware(),
                    'meta_title' => $menu->meta_title,
                    'meta_description' => $menu->meta_description,
                    'parent_id' => $menu->parent_id,
                    'updated_at' => $menu->updated_at->timestamp
                ];
            });
    }

    /**
     * Get cached routes
     */
    public static function getCachedRoutes()
    {
        return Cache::get(self::CACHE_KEY);
    }

    /**
     * Check if routes are cached
     */
    public static function hasCachedRoutes()
    {
        return Cache::has(self::CACHE_KEY);
    }

    /**
     * Invalidate cache when menus change
     */
    public static function invalidateCache()
    {
        Cache::forget(self::CACHE_KEY);
    }
}
