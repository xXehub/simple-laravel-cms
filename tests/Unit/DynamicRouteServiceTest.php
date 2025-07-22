<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\DynamicRouteService;
use App\Models\MasterMenu;
use App\Console\Commands\CacheDynamicRoutes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;

class DynamicRouteServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new DynamicRouteService();
    }

    /** @test */
    public function it_can_register_routes_from_database()
    {
        // Create test menu
        MasterMenu::create([
            'nama_menu' => 'Test Page',
            'slug' => 'test-page',
            'route_name' => 'test.page',
            'route_type' => 'public',
            'controller_class' => 'App\\Http\\Controllers\\TestController',
            'controller_method' => 'index',
            'is_active' => true
        ]);

        // Register routes
        $this->service->registerRoutes();

        // Check if route is registered
        $this->assertTrue(Route::has('test.page'));
    }

    /** @test */
    public function it_skips_inactive_menus()
    {
        // Create inactive menu
        MasterMenu::create([
            'nama_menu' => 'Inactive Page',
            'slug' => 'inactive-page',
            'route_name' => 'inactive.page',
            'is_active' => false
        ]);

        $this->service->registerRoutes();

        // Route should not be registered
        $this->assertFalse(Route::has('inactive.page'));
    }

    /** @test */
    public function it_resolves_menu_from_slug()
    {
        $menu = MasterMenu::create([
            'nama_menu' => 'About',
            'slug' => 'about-us',
            'route_name' => 'about',
            'is_active' => true
        ]);

        $resolved = $this->service->resolveMenuFromSlug('about-us');
        
        $this->assertNotNull($resolved);
        $this->assertEquals($menu->id, $resolved->id);
    }

    /** @test */
    public function it_returns_null_for_non_existent_slug()
    {
        $resolved = $this->service->resolveMenuFromSlug('non-existent');
        $this->assertNull($resolved);
    }

    /** @test */
    public function it_handles_controller_method_validation()
    {
        // Create menu with valid controller
        $validMenu = MasterMenu::create([
            'nama_menu' => 'Valid Controller',
            'slug' => 'valid',
            'route_name' => 'valid',
            'controller_class' => 'App\\Http\\Controllers\\Controller', // Base controller exists
            'controller_method' => 'callAction', // Method exists
            'is_active' => true
        ]);

        // Create menu with invalid controller
        $invalidMenu = MasterMenu::create([
            'nama_menu' => 'Invalid Controller',
            'slug' => 'invalid',
            'route_name' => 'invalid',
            'controller_class' => 'App\\Http\\Controllers\\NonExistentController',
            'controller_method' => 'index',
            'is_active' => true
        ]);

        $this->service->registerRoutes();

        // Valid route should be registered
        $this->assertTrue(Route::has('valid'));
        
        // Invalid route should not be registered
        $this->assertFalse(Route::has('invalid'));
    }

    /** @test */
    public function it_uses_cached_routes_when_available()
    {
        // Mock cached routes
        $cachedRoutes = collect([
            [
                'id' => 1,
                'slug' => 'cached-test',
                'route_name' => 'cached.test',
                'route_type' => 'public',
                'controller_class' => 'App\\Http\\Controllers\\TestController',
                'controller_method' => 'index',
                'middleware' => ['web'],
                'meta_title' => 'Cached Test',
                'meta_description' => 'Test page from cache'
            ]
        ]);

        Cache::put(CacheDynamicRoutes::CACHE_KEY, $cachedRoutes, 3600);

        $this->service->registerRoutes();

        // Route should be registered from cache
        $this->assertTrue(Route::has('cached.test'));
    }

    /** @test */
    public function it_falls_back_to_database_when_cache_empty()
    {
        // Ensure cache is empty
        Cache::forget(CacheDynamicRoutes::CACHE_KEY);

        // Create database menu
        MasterMenu::create([
            'nama_menu' => 'Database Test',
            'slug' => 'database-test',
            'route_name' => 'database.test',
            'route_type' => 'public',
            'is_active' => true
        ]);

        $this->service->registerRoutes();

        // Route should be registered from database
        $this->assertTrue(Route::has('database.test'));
    }

    /** @test */
    public function it_handles_view_based_routes()
    {
        MasterMenu::create([
            'nama_menu' => 'View Page',
            'slug' => 'view-page',
            'route_name' => 'view.page',
            'view_path' => 'pages.view',
            'route_type' => 'public',
            'is_active' => true
        ]);

        $this->service->registerRoutes();

        $this->assertTrue(Route::has('view.page'));
    }

    /** @test */
    public function it_applies_middleware_correctly()
    {
        MasterMenu::create([
            'nama_menu' => 'Protected Page',
            'slug' => 'protected',
            'route_name' => 'protected',
            'middleware_list' => json_encode(['web', 'auth']),
            'route_type' => 'admin',
            'is_active' => true
        ]);

        $this->service->registerRoutes();

        $route = Route::getRoutes()->getByName('protected');
        $this->assertNotNull($route);
        
        // Check if middleware is applied
        $middleware = $route->gatherMiddleware();
        $this->assertContains('web', $middleware);
        $this->assertContains('auth', $middleware);
    }
}
