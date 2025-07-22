<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Superadmin - semua permission
        $superadmin = Role::create(['name' => 'superadmin']);
        $superadmin->givePermissionTo(Permission::all());

        // Admin - akses panel dan management
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'access-panel', 'view-dashboard',
            'view-users', 'create-users', 'update-users',
            'view-pages', 'create-pages', 'update-pages', 'delete-pages',
            'view-articles', 'create-articles', 'update-articles', 'delete-articles',
            'view-media', 'create-media', 'update-media', 'delete-media',
            'view-profile', 'update-profile',
        ]);

        // User - hanya profile
        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo(['view-profile', 'update-profile']);

        $this->command->info("✅ 3 roles created (superadmin, admin, user)");
    }
}
