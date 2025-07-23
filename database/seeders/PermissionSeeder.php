<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Panel & Dashboard
            ['name' => 'access-panel', 'group' => 'panel'],
            ['name' => 'view-dashboard', 'group' => 'panel'],

            // User Management
            ['name' => 'view-users', 'group' => 'users'],
            ['name' => 'create-users', 'group' => 'users'],
            ['name' => 'update-users', 'group' => 'users'],
            ['name' => 'delete-users', 'group' => 'users'],

            // Role & Permission Management
            ['name' => 'view-roles', 'group' => 'roles'],
            ['name' => 'create-roles', 'group' => 'roles'],
            ['name' => 'update-roles', 'group' => 'roles'],
            ['name' => 'delete-roles', 'group' => 'roles'],
            ['name' => 'view-permissions', 'group' => 'permissions'],
            ['name' => 'create-permissions', 'group' => 'permissions'],
            ['name' => 'update-permissions', 'group' => 'permissions'],
            ['name' => 'delete-permissions', 'group' => 'permissions'],

            // Menu Management
            ['name' => 'view-menus', 'group' => 'menus'],
            ['name' => 'create-menus', 'group' => 'menus'],
            ['name' => 'update-menus', 'group' => 'menus'],
            ['name' => 'delete-menus', 'group' => 'menus'],

            // Content Management
            ['name' => 'view-pages', 'group' => 'pages'],
            ['name' => 'create-pages', 'group' => 'pages'],
            ['name' => 'update-pages', 'group' => 'pages'],
            ['name' => 'delete-pages', 'group' => 'pages'],
            ['name' => 'view-articles', 'group' => 'articles'],
            ['name' => 'create-articles', 'group' => 'articles'],
            ['name' => 'update-articles', 'group' => 'articles'],
            ['name' => 'delete-articles', 'group' => 'articles'],

            // Media Management
            ['name' => 'view-media', 'group' => 'media'],
            ['name' => 'create-media', 'group' => 'media'],
            ['name' => 'update-media', 'group' => 'media'],
            ['name' => 'delete-media', 'group' => 'media'],

            // System & Settings
            ['name' => 'view-settings', 'group' => 'settings'],
            ['name' => 'update-settings', 'group' => 'settings'],
            ['name' => 'view-system', 'group' => 'system'],
            ['name' => 'update-system', 'group' => 'system'],

            // Profile
            ['name' => 'view-profile', 'group' => 'profile'],
            ['name' => 'update-profile', 'group' => 'profile'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        $this->command->info("✅ " . count($permissions) . " permissions created");
    }
}
