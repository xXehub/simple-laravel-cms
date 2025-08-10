<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterMenu;

class PageBuilderMenuSeeder extends Seeder
{
    public function run()
    {
        // Cari parent menu untuk Panel Management
        $panelManagement = MasterMenu::where('nama_menu', 'Panel Management')->first();
        
        if (!$panelManagement) {
            $this->command->error('Panel Management menu tidak ditemukan. Jalankan MasterMenuSeeder dulu.');
            return;
        }

        // Create Page Builder menu
        $pageBuilder = MasterMenu::create([
            'nama_menu' => 'Page Builder',
            'slug' => 'panel/builder',
            'parent_id' => $panelManagement->id,
            'route_name' => 'panel.builder.index',
            'icon' => 'ti ti-layout-grid',
            'urutan' => 15, // Setelah menu lain di Panel Management
            'is_active' => true,
            'route_type' => 'admin',
            'controller_class' => 'App\\Http\\Controllers\\Panel\\PageBuilderController',
            'view_path' => null,
            'middleware_list' => ['web', 'auth', 'permission:view-pages'],
            'meta_title' => 'Page Builder',
            'meta_description' => 'Build and design pages with drag & drop components',
            'visit_count' => 0
        ]);

        // Create API menu untuk Builder Components
        MasterMenu::create([
            'nama_menu' => 'Builder Components API',
            'slug' => 'panel/builder/components',
            'parent_id' => $pageBuilder->id,
            'route_name' => 'panel.builder.components',
            'icon' => 'ti ti-api',
            'urutan' => 1,
            'is_active' => true,
            'route_type' => 'api',
            'controller_class' => 'App\\Http\\Controllers\\Panel\\ComponentController',
            'view_path' => null,
            'middleware_list' => ['web', 'auth', 'permission:view-pages'],
            'meta_title' => 'Components API',
            'meta_description' => 'API endpoint for builder components',
            'visit_count' => 0
        ]);

        // Create API menu untuk Render Component
        MasterMenu::create([
            'nama_menu' => 'Render Component API',
            'slug' => 'panel/builder/components/render',
            'parent_id' => $pageBuilder->id,
            'route_name' => 'panel.builder.render',
            'icon' => 'ti ti-play',
            'urutan' => 2,
            'is_active' => true,
            'route_type' => 'api',
            'controller_class' => 'App\\Http\\Controllers\\Panel\\ComponentController',
            'view_path' => null,
            'middleware_list' => ['web', 'auth', 'permission:view-pages'],
            'meta_title' => 'Render Component',
            'meta_description' => 'API endpoint for rendering components',
            'visit_count' => 0
        ]);

        $this->command->info("✅ Page Builder menus berhasil dibuat!");
    }
}
