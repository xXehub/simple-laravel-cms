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
            'access-panel', 'view-dashboard',
            
            // User Management
            'view-users', 'create-users', 'update-users', 'delete-users',
            
            // Role & Permission Management
            'view-roles', 'create-roles', 'update-roles', 'delete-roles',
            'view-permissions', 'create-permissions', 'update-permissions', 'delete-permissions',
            
            // Menu Management
            'view-menus', 'create-menus', 'update-menus', 'delete-menus',
            
            // Content Management
            'view-pages', 'create-pages', 'update-pages', 'delete-pages',
            'view-articles', 'create-articles', 'update-articles', 'delete-articles',
            
            // Media Management
            'view-media', 'create-media', 'update-media', 'delete-media',
            
            // System & Settings
            'view-settings', 'update-settings',
            'view-system', 'update-system',
            
            // Profile
            'view-profile', 'update-profile',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $this->command->info("✅ " . count($permissions) . " permissions created");
    }
}
