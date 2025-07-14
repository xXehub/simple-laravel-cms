<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuRole;
use App\Models\MasterMenu;
use Spatie\Permission\Models\Role;

class MenuRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::all();
        $menus = MasterMenu::all();

        // Admin has access to ALL menus (including all panel menus)
        $adminRole = $roles->where('name', 'admin')->first();
        if ($adminRole) {
            foreach ($menus as $menu) {
                MenuRole::firstOrCreate([
                    'role_id' => $adminRole->id,
                    'mastermenu_id' => $menu->id,
                ]);
            }
        }

        // Editor has access to profile and limited panel features
        $editorRole = $roles->where('name', 'editor')->first();
        if ($editorRole) {
            // Editor menu access by menu names
            $editorMenuNames = [
                'Beranda', // Homepage
                'Dashboard', // Dashboard
                'Panel Management', // Container - needs access to see Pages
                'Pages', // Pages management only
                'About Us', // Public pages
                'Contact',
            ];

            $editorMenus = $menus->whereIn('nama_menu', $editorMenuNames);
            foreach ($editorMenus as $menu) {
                MenuRole::firstOrCreate([
                    'role_id' => $editorRole->id,
                    'mastermenu_id' => $menu->id,
                ]);
            }
        }

        // Viewer has access to public pages and profile only (NO panel access)
        $viewerRole = $roles->where('name', 'viewer')->first();
        if ($viewerRole) {
            // Viewer menu access by menu names - very restricted
            $viewerMenuNames = [
                'Beranda', // Homepage only
                'About Us', // Public pages
                'Contact',
            ];

            $viewerMenus = $menus->whereIn('nama_menu', $viewerMenuNames);
            foreach ($viewerMenus as $menu) {
                MenuRole::firstOrCreate([
                    'role_id' => $viewerRole->id,
                    'mastermenu_id' => $menu->id,
                ]);
            }
        }
    }
}
