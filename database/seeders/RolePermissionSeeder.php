<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions - Clean list following instructions with groups
        $permissions = [
            // Dashboard/Panel access
            ['name' => 'access-panel', 'group' => 'panel'],
            ['name' => 'view-dashboard', 'group' => 'panel'],
            
            // User management
            ['name' => 'view-users', 'group' => 'users'],
            ['name' => 'create-users', 'group' => 'users'],
            ['name' => 'update-users', 'group' => 'users'],
            ['name' => 'delete-users', 'group' => 'users'],
            
            // Role management
            ['name' => 'view-roles', 'group' => 'roles'],
            ['name' => 'create-roles', 'group' => 'roles'],
            ['name' => 'update-roles', 'group' => 'roles'],
            ['name' => 'delete-roles', 'group' => 'roles'],
            
            // Permission management
            ['name' => 'view-permissions', 'group' => 'permissions'],
            ['name' => 'create-permissions', 'group' => 'permissions'],
            ['name' => 'update-permissions', 'group' => 'permissions'],
            ['name' => 'delete-permissions', 'group' => 'permissions'],
            
            // Menu management
            ['name' => 'view-menus', 'group' => 'menus'],
            ['name' => 'create-menus', 'group' => 'menus'],
            ['name' => 'update-menus', 'group' => 'menus'],
            ['name' => 'delete-menus', 'group' => 'menus'],
            
            // Page management
            ['name' => 'view-pages', 'group' => 'pages'],
            ['name' => 'create-pages', 'group' => 'pages'],
            ['name' => 'update-pages', 'group' => 'pages'],
            ['name' => 'delete-pages', 'group' => 'pages'],
            
            // Settings management
            ['name' => 'view-settings', 'group' => 'settings'],
            ['name' => 'update-settings', 'group' => 'settings'],
            
            // Profile management
            ['name' => 'view-profile', 'group' => 'profile'],
            ['name' => 'update-profile', 'group' => 'profile'],
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission['name']]);
        }

        // Create roles and assign permissions - Clean approach
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all()); // Admin gets all permissions

        $editor = Role::create(['name' => 'editor']);
        $editor->givePermissionTo([
            'access-panel',
            'view-dashboard',
            'view-pages',
            'create-pages',
            'update-pages',
            'delete-pages',
            'view-profile',
            'update-profile',
        ]);

        $viewer = Role::create(['name' => 'viewer']);
        $viewer->givePermissionTo([
            'view-profile',
            'update-profile',
        ]);

        // Create admin user
        $adminUser = User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $adminUser->assignRole('admin');

        // Create editor user
        $editorUser = User::create([
            'name' => 'Editor User',
            'username' => 'editor',
            'email' => 'editor@example.com',
            'password' => bcrypt('password'),
        ]);
        $editorUser->assignRole('editor');

        // Create viewer user
        $viewerUser = User::create([
            'name' => 'Viewer User',
            'username' => 'viewer',
            'email' => 'viewer@example.com',
            'password' => bcrypt('password'),
        ]);
        $viewerUser->assignRole('viewer');
    }
}
