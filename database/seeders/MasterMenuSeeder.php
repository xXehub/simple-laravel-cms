<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MasterMenu;

class MasterMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Beranda - Root menu
        MasterMenu::create([
            'nama_menu' => 'Beranda',
            'slug' => '',
            'parent_id' => null,
            'route_name' => 'welcome',
            'icon' => 'fa-solid fa-shop',
            'urutan' => 0,
            'is_active' => true,
        ]);

        // 2. Dashboard - Root menu
        MasterMenu::create([
            'nama_menu' => 'Dashboard',
            'slug' => 'panel/dashboard',
            'parent_id' => null,
            'route_name' => null,
            'icon' => 'fa-solid fa-table-columns',
            'urutan' => 1,
            'is_active' => true,
        ]);

        // 3. Panel Management - Parent menu
        $panelManagement = MasterMenu::create([
            'nama_menu' => 'Panel Management',
            'slug' => 'dashboard',
            'parent_id' => null,
            'route_name' => null,
            'icon' => 'fa-solid fa-bars-progress',
            'urutan' => 2,
            'is_active' => true,
        ]);

        // Child menus under Panel Management
        MasterMenu::create([
            'nama_menu' => 'Users',
            'slug' => 'panel/users',
            'parent_id' => $panelManagement->id,
            'route_name' => null,
            'icon' => 'fas fa-users',
            'urutan' => 3,
            'is_active' => true,
        ]);

        MasterMenu::create([
            'nama_menu' => 'Roles',
            'slug' => 'panel/roles',
            'parent_id' => $panelManagement->id,
            'route_name' => null,
            'icon' => 'fas fa-user-tag',
            'urutan' => 4,
            'is_active' => true,
        ]);

        MasterMenu::create([
            'nama_menu' => 'Permissions',
            'slug' => 'panel/permissions',
            'parent_id' => $panelManagement->id,
            'route_name' => null,
            'icon' => 'fas fa-key',
            'urutan' => 5,
            'is_active' => true,
        ]);

        MasterMenu::create([
            'nama_menu' => 'Menus',
            'slug' => 'panel/menus',
            'parent_id' => $panelManagement->id,
            'route_name' => null,
            'icon' => 'fas fa-bars',
            'urutan' => 6,
            'is_active' => true,
        ]);

        MasterMenu::create([
            'nama_menu' => 'Pages',
            'slug' => 'panel/pages',
            'parent_id' => $panelManagement->id,
            'route_name' => null,
            'icon' => 'fas fa-file-alt',
            'urutan' => 7,
            'is_active' => true,
        ]);

        MasterMenu::create([
            'nama_menu' => 'Settings',
            'slug' => 'panel/settings',
            'parent_id' => $panelManagement->id,
            'route_name' => null,
            'icon' => 'fas fa-cog',
            'urutan' => 8,
            'is_active' => true,
        ]);

        // 4. Public pages - Root menus
        MasterMenu::create([
            'nama_menu' => 'About Us',
            'slug' => 'about-us',
            'parent_id' => null,
            'route_name' => null,
            'icon' => 'fas fa-info-circle',
            'urutan' => 9,
            'is_active' => true,
        ]);

        MasterMenu::create([
            'nama_menu' => 'Contact',
            'slug' => 'contact',
            'parent_id' => null,
            'route_name' => null,
            'icon' => 'fas fa-envelope',
            'urutan' => 10,
            'is_active' => true,
        ]);

        // 5. Advanced Management - Root menu with deeper nesting
        $advancedManagement = MasterMenu::create([
            'nama_menu' => 'Advanced Management',
            'slug' => '',
            'parent_id' => null,
            'route_name' => null,
            'icon' => 'fas fa-layer-group',
            'urutan' => 11,
            'is_active' => true,
        ]);

        // Level 2: Content Management under Advanced
        $contentManagement = MasterMenu::create([
            'nama_menu' => 'Content Management',
            'slug' => '',
            'parent_id' => $advancedManagement->id,
            'route_name' => null,
            'icon' => 'fas fa-folder',
            'urutan' => 12,
            'is_active' => true,
        ]);

        // Level 3: Articles under Content Management
        $articlesManagement = MasterMenu::create([
            'nama_menu' => 'Articles',
            'slug' => '',
            'parent_id' => $contentManagement->id,
            'route_name' => null,
            'icon' => 'fas fa-newspaper',
            'urutan' => 13,
            'is_active' => true,
        ]);

        // Level 4: Categories under Articles
        MasterMenu::create([
            'nama_menu' => 'Article Categories',
            'slug' => 'panel/article-categories',
            'parent_id' => $articlesManagement->id,
            'route_name' => null,
            'icon' => 'fas fa-tags',
            'urutan' => 14,
            'is_active' => true,
        ]);

        // Level 4: Tags under Articles
        MasterMenu::create([
            'nama_menu' => 'Article Tags',
            'slug' => 'panel/article-tags',
            'parent_id' => $articlesManagement->id,
            'route_name' => null,
            'icon' => 'fas fa-hashtag',
            'urutan' => 15,
            'is_active' => true,
        ]);

        // Level 3: Media under Content Management
        $mediaManagement = MasterMenu::create([
            'nama_menu' => 'Media',
            'slug' => '',
            'parent_id' => $contentManagement->id,
            'route_name' => null,
            'icon' => 'fas fa-photo-video',
            'urutan' => 16,
            'is_active' => true,
        ]);

        // Level 4: Images under Media
        MasterMenu::create([
            'nama_menu' => 'Images',
            'slug' => 'panel/media/images',
            'parent_id' => $mediaManagement->id,
            'route_name' => null,
            'icon' => 'fas fa-images',
            'urutan' => 17,
            'is_active' => true,
        ]);

        // Level 4: Videos under Media
        MasterMenu::create([
            'nama_menu' => 'Videos',
            'slug' => 'panel/media/videos',
            'parent_id' => $mediaManagement->id,
            'route_name' => null,
            'icon' => 'fas fa-video',
            'urutan' => 18,
            'is_active' => true,
        ]);

        // Level 2: System Tools under Advanced
        $systemTools = MasterMenu::create([
            'nama_menu' => 'System Tools',
            'slug' => '',
            'parent_id' => $advancedManagement->id,
            'route_name' => null,
            'icon' => 'fas fa-tools',
            'urutan' => 19,
            'is_active' => true,
        ]);

        // Level 3: Monitoring under System Tools
        $monitoring = MasterMenu::create([
            'nama_menu' => 'Monitoring',
            'slug' => '',
            'parent_id' => $systemTools->id,
            'route_name' => null,
            'icon' => 'fas fa-chart-line',
            'urutan' => 20,
            'is_active' => true,
        ]);

        // Level 4: Logs under Monitoring
        MasterMenu::create([
            'nama_menu' => 'System Logs',
            'slug' => 'panel/logs',
            'parent_id' => $monitoring->id,
            'route_name' => null,
            'icon' => 'fas fa-file-code',
            'urutan' => 21,
            'is_active' => true,
        ]);

        // Level 4: Performance under Monitoring
        MasterMenu::create([
            'nama_menu' => 'Performance',
            'slug' => 'panel/performance',
            'parent_id' => $monitoring->id,
            'route_name' => null,
            'icon' => 'fas fa-tachometer-alt',
            'urutan' => 22,
            'is_active' => true,
        ]);
    }
}
