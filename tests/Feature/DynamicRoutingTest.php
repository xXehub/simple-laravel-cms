<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\MasterMenu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DynamicRoutingTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;
    protected $user;
    protected $adminRole;
    protected $userRole;

    public function setUp(): void
    {
        parent::setUp();
        
        // Create roles and permissions
        $this->adminRole = Role::create(['name' => 'admin']);
        $this->userRole = Role::create(['name' => 'user']);
        
        Permission::create(['name' => 'view-dashboard']);
        Permission::create(['name' => 'view-users']);
        Permission::create(['name' => 'view-settings']);
        
        $this->adminRole->givePermissionTo(['view-dashboard', 'view-users', 'view-settings']);
        $this->userRole->givePermissionTo(['view-dashboard']);
        
        // Create users
        $this->admin = User::factory()->create();
        $this->user = User::factory()->create();
        
        $this->admin->assignRole('admin');
        $this->user->assignRole('user');
        
        // Create test menus
        $this->createTestMenus();
    }

    protected function createTestMenus()
    {
        // Parent menu
        $dashboard = MasterMenu::create([
            'nama_menu' => 'Dashboard',
            'slug' => 'panel/dashboard',
            'route_name' => 'dashboard',
            'route_type' => 'admin',
            'controller_class' => 'App\\Http\\Controllers\\Panel\\DashboardController',
            'controller_method' => 'index',
            'middleware_list' => json_encode(['web', 'auth', 'permission:view-dashboard']),
            'icon' => 'ti ti-dashboard',
            'urutan' => 1,
            'is_active' => true,
            'meta_title' => 'Dashboard',
            'meta_description' => 'Admin Dashboard'
        ]);

        // Admin-only menu
        MasterMenu::create([
            'nama_menu' => 'Users',
            'slug' => 'panel/users',
            'route_name' => 'users.index',
            'route_type' => 'admin',
            'controller_class' => 'App\\Http\\Controllers\\Panel\\UserController',
            'controller_method' => 'index',
            'middleware_list' => json_encode(['web', 'auth', 'permission:view-users']),
            'icon' => 'ti ti-users',
            'urutan' => 2,
            'is_active' => true,
            'meta_title' => 'Users Management',
            'meta_description' => 'Manage system users'
        ]);

        // Public menu
        MasterMenu::create([
            'nama_menu' => 'About Us',
            'slug' => 'about',
            'route_name' => 'about',
            'route_type' => 'public',
            'view_path' => 'pages.about',
            'middleware_list' => json_encode(['web']),
            'icon' => 'ti ti-info-circle',
            'urutan' => 3,
            'is_active' => true,
            'meta_title' => 'About Us',
            'meta_description' => 'Learn more about us'
        ]);
    }

    /** @test */
    public function it_can_access_dashboard_with_permission()
    {
        $this->actingAs($this->admin)
            ->get('/panel/dashboard')
            ->assertSuccessful()
            ->assertViewIs('panel.dashboard.index');
    }

    /** @test */
    public function it_denies_access_without_permission()
    {
        $this->actingAs($this->user)
            ->get('/panel/users')
            ->assertStatus(403);
    }

    /** @test */
    public function it_can_access_public_pages()
    {
        $this->get('/about')
            ->assertSuccessful()
            ->assertViewIs('pages.about');
    }

    /** @test */
    public function it_redirects_unauthenticated_users_to_login()
    {
        $this->get('/panel/dashboard')
            ->assertRedirect('/login');
    }

    /** @test */
    public function it_returns_404_for_inactive_menus()
    {
        $menu = MasterMenu::create([
            'nama_menu' => 'Inactive Menu',
            'slug' => 'inactive',
            'route_name' => 'inactive',
            'is_active' => false
        ]);

        $this->get('/inactive')
            ->assertStatus(404);
    }

    /** @test */
    public function it_generates_correct_breadcrumbs()
    {
        $this->actingAs($this->admin)
            ->get('/panel/dashboard')
            ->assertSuccessful()
            ->assertViewHas('breadcrumbs');
    }

    /** @test */
    public function it_handles_parent_menu_redirects()
    {
        // Create parent menu
        $parent = MasterMenu::create([
            'nama_menu' => 'Management',
            'slug' => 'panel/management',
            'route_name' => 'management',
            'route_type' => 'admin',
            'middleware_list' => json_encode(['web', 'auth']),
            'urutan' => 1,
            'is_active' => true
        ]);

        // Create child menu
        $child = MasterMenu::create([
            'nama_menu' => 'Settings',
            'slug' => 'panel/management/settings',
            'parent_id' => $parent->id,
            'route_name' => 'management.settings',
            'route_type' => 'admin',
            'controller_class' => 'App\\Http\\Controllers\\Panel\\SettingsController',
            'controller_method' => 'index',
            'middleware_list' => json_encode(['web', 'auth', 'permission:view-settings']),
            'urutan' => 1,
            'is_active' => true
        ]);

        $this->actingAs($this->admin)
            ->get('/panel/management')
            ->assertRedirect('/panel/management/settings');
    }

    /** @test */
    public function it_loads_correct_meta_data()
    {
        $this->actingAs($this->admin)
            ->get('/panel/dashboard')
            ->assertSuccessful()
            ->assertSee('Dashboard', false) // title
            ->assertSee('Admin Dashboard', false); // meta description
    }

    /** @test */
    public function it_applies_correct_middleware()
    {
        // Test that auth middleware is applied
        $this->get('/panel/dashboard')
            ->assertRedirect('/login');

        // Test that permission middleware is applied
        $this->actingAs($this->user)
            ->get('/panel/users')
            ->assertStatus(403);
    }

    /** @test */
    public function it_handles_dynamic_controller_dispatch()
    {
        $this->actingAs($this->admin);

        // Test controller-based route
        $response = $this->get('/panel/dashboard');
        $response->assertSuccessful();

        // Test view-based route
        $response = $this->get('/about');
        $response->assertSuccessful();
    }
}
