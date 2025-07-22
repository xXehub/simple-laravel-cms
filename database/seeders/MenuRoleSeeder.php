<?php

namespace Database\Seeders;

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
        // Superadmin - akses semua menu
        $superadmin = Role::where('name', 'superadmin')->first();
        if ($superadmin) {
            foreach (MasterMenu::all() as $menu) {
                MenuRole::firstOrCreate([
                    'role_id' => $superadmin->id,
                    'mastermenu_id' => $menu->getKey(),
                ]);
            }
        }

        // Admin - akses menu panel dan public
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $adminMenus = MasterMenu::whereIn('nama_menu', [
                'Beranda', 'About Us', 'Articles', 'Contact', // Public menus
                'Dashboard', 'Panel Management', 'Users', 'Pages', // Panel menus
                'Advanced Management', 'Content Management', 'Media'
            ])->get();

            foreach ($adminMenus as $menu) {
                MenuRole::firstOrCreate([
                    'role_id' => $admin->id,
                    'mastermenu_id' => $menu->getKey(),
                ]);
            }
        }

        // User - hanya menu public
        $user = Role::where('name', 'user')->first();
        if ($user) {
            $userMenus = MasterMenu::whereIn('nama_menu', [
                'Beranda', 'About Us', 'Articles', 'Contact' // Hanya public menus
            ])->get();

            foreach ($userMenus as $menu) {
                MenuRole::firstOrCreate([
                    'role_id' => $user->id,
                    'mastermenu_id' => $menu->getKey(),
                ]);
            }
        }

        $this->command->info("✅ Menu roles assigned for 3 roles");
    }
}
