<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\MasterMenu;
use App\Models\MenuRole;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MasterMenuTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $adminRole;
    protected $userRole;

    public function setUp(): void
    {
        parent::setUp();
        
        // Create roles and permissions
        $this->adminRole = Role::create(['name' => 'admin']);
        $this->userRole = Role::create(['name' => 'user']);
        
        Permission::create(['name' => 'view-admin']);
        Permission::create(['name' => 'view-users']);
        
        $this->adminRole->givePermissionTo(['view-admin', 'view-users']);
        $this->userRole->givePermissionTo(['view-admin']);
        
        // Create test user
        $this->user = User::factory()->create();
        $this->user->assignRole('user');
    }

    /** @test */
    public function it_can_get_controller_class()
    {
        $menu = MasterMenu::create([
            'nama_menu' => 'Test Menu',
            'controller_class' => 'App\\Http\\Controllers\\TestController',
            'slug' => 'test',
            'is_active' => true
        ]);

        $this->assertEquals('App\\Http\\Controllers\\TestController', $menu->getControllerClass());
    }

    /** @test */
    public function it_can_get_controller_method()
    {
        $menu = MasterMenu::create([
            'nama_menu' => 'Test Menu',
            'controller_method' => 'show',
            'slug' => 'test',
            'is_active' => true
        ]);

        $this->assertEquals('show', $menu->getControllerMethod());
    }

    /** @test */
    public function it_defaults_to_index_method()
    {
        $menu = MasterMenu::create([
            'nama_menu' => 'Test Menu',
            'slug' => 'test',
            'is_active' => true
        ]);

        $this->assertEquals('index', $menu->getControllerMethod());
    }

    /** @test */
    public function it_can_parse_middleware_from_json()
    {
        $menu = MasterMenu::create([
            'nama_menu' => 'Test Menu',
            'middleware_list' => json_encode(['web', 'auth', 'permission:view-admin']),
            'slug' => 'test',
            'is_active' => true
        ]);

        $middleware = $menu->getMiddleware();
        $this->assertIsArray($middleware);
        $this->assertContains('web', $middleware);
        $this->assertContains('auth', $middleware);
        $this->assertContains('permission:view-admin', $middleware);
    }

    /** @test */
    public function it_returns_empty_array_for_null_middleware()
    {
        $menu = MasterMenu::create([
            'nama_menu' => 'Test Menu',
            'slug' => 'test',
            'is_active' => true
        ]);

        $this->assertIsArray($menu->getMiddleware());
        $this->assertEmpty($menu->getMiddleware());
    }

    /** @test */
    public function it_determines_if_should_use_controller()
    {
        $controllerMenu = MasterMenu::create([
            'nama_menu' => 'Controller Menu',
            'controller_class' => 'App\\Http\\Controllers\\TestController',
            'slug' => 'controller-test',
            'is_active' => true
        ]);

        $viewMenu = MasterMenu::create([
            'nama_menu' => 'View Menu',
            'view_path' => 'pages.test',
            'slug' => 'view-test',
            'is_active' => true
        ]);

        $this->assertTrue($controllerMenu->shouldUseController());
        $this->assertFalse($viewMenu->shouldUseController());
    }

    /** @test */
    public function it_determines_if_should_use_view()
    {
        $viewMenu = MasterMenu::create([
            'nama_menu' => 'View Menu',
            'view_path' => 'pages.test',
            'slug' => 'view-test',
            'is_active' => true
        ]);

        $controllerMenu = MasterMenu::create([
            'nama_menu' => 'Controller Menu',
            'controller_class' => 'App\\Http\\Controllers\\TestController',
            'slug' => 'controller-test',
            'is_active' => true
        ]);

        $this->assertTrue($viewMenu->shouldUseView());
        $this->assertFalse($controllerMenu->shouldUseView());
    }

    /** @test */
    public function it_can_check_accessibility_with_roles()
    {
        // Create menu with role requirement
        $menu = MasterMenu::create([
            'nama_menu' => 'Admin Menu',
            'slug' => 'admin-menu',
            'is_active' => true
        ]);

        // Assign menu to admin role only
        MenuRole::create([
            'role_id' => $this->adminRole->id,
            'mastermenu_id' => $menu->id
        ]);

        // User with user role should not have access
        $this->actingAs($this->user);
        $this->assertFalse($menu->isAccessible());

        // Assign user admin role
        $this->user->assignRole('admin');
        $this->assertTrue($menu->isAccessible());
    }

    /** @test */
    public function it_allows_access_to_menus_without_role_restrictions()
    {
        $menu = MasterMenu::create([
            'nama_menu' => 'Public Menu',
            'slug' => 'public-menu',
            'is_active' => true
        ]);

        // No MenuRole entries mean it's accessible to all
        $this->actingAs($this->user);
        $this->assertTrue($menu->isAccessible());
    }

    /** @test */
    public function it_generates_correct_url()
    {
        $menu = MasterMenu::create([
            'nama_menu' => 'Test Menu',
            'slug' => 'test/path',
            'is_active' => true
        ]);

        $expectedUrl = url('/test/path');
        $this->assertEquals($expectedUrl, $menu->getUrl());
    }

    /** @test */
    public function it_handles_parent_child_relationships()
    {
        $parent = MasterMenu::create([
            'nama_menu' => 'Parent Menu',
            'slug' => 'parent',
            'is_active' => true
        ]);

        $child = MasterMenu::create([
            'nama_menu' => 'Child Menu',
            'slug' => 'parent/child',
            'parent_id' => $parent->id,
            'is_active' => true
        ]);

        $this->assertTrue($parent->children->contains($child));
        $this->assertEquals($parent->id, $child->parent->id);
    }

    /** @test */
    public function it_generates_breadcrumbs_correctly()
    {
        $parent = MasterMenu::create([
            'nama_menu' => 'Parent',
            'slug' => 'parent',
            'is_active' => true
        ]);

        $child = MasterMenu::create([
            'nama_menu' => 'Child',
            'slug' => 'parent/child',
            'parent_id' => $parent->id,
            'is_active' => true
        ]);

        $breadcrumbs = $child->getBreadcrumbs();
        
        $this->assertIsArray($breadcrumbs);
        $this->assertCount(3, $breadcrumbs); // Home + Parent + Child
        
        // Check breadcrumb structure
        $this->assertEquals('Home', $breadcrumbs[0]['title']);
        $this->assertEquals('Parent', $breadcrumbs[1]['title']);
        $this->assertEquals('Child', $breadcrumbs[2]['title']);
        $this->assertTrue($breadcrumbs[2]['is_current']);
    }

    /** @test */
    public function it_can_determine_active_status()
    {
        $activeMenu = MasterMenu::create([
            'nama_menu' => 'Active Menu',
            'slug' => 'active',
            'is_active' => true
        ]);

        $inactiveMenu = MasterMenu::create([
            'nama_menu' => 'Inactive Menu',
            'slug' => 'inactive',
            'is_active' => false
        ]);

        $this->assertTrue($activeMenu->is_active);
        $this->assertFalse($inactiveMenu->is_active);
    }

    /** @test */
    public function it_scopes_active_menus()
    {
        MasterMenu::create(['nama_menu' => 'Active 1', 'slug' => 'active1', 'is_active' => true]);
        MasterMenu::create(['nama_menu' => 'Active 2', 'slug' => 'active2', 'is_active' => true]);
        MasterMenu::create(['nama_menu' => 'Inactive', 'slug' => 'inactive', 'is_active' => false]);

        $activeMenus = MasterMenu::active()->get();
        $this->assertCount(2, $activeMenus);
    }

    /** @test */
    public function it_orders_menus_by_urutan()
    {
        MasterMenu::create(['nama_menu' => 'Third', 'slug' => 'third', 'urutan' => 3, 'is_active' => true]);
        MasterMenu::create(['nama_menu' => 'First', 'slug' => 'first', 'urutan' => 1, 'is_active' => true]);
        MasterMenu::create(['nama_menu' => 'Second', 'slug' => 'second', 'urutan' => 2, 'is_active' => true]);

        $orderedMenus = MasterMenu::ordered()->get();
        
        $this->assertEquals('First', $orderedMenus[0]->nama_menu);
        $this->assertEquals('Second', $orderedMenus[1]->nama_menu);
        $this->assertEquals('Third', $orderedMenus[2]->nama_menu);
    }
}
