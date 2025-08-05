<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterMenu;

class PageBuilderMenuSeeder extends Seeder
{
    public function run()
    {
        // Create menu entry for page builder
        MasterMenu::firstOrCreate([
            'slug' => 'pages/builder'
        ], [
            'nama_menu' => 'Page Builder',
            'slug' => 'pages/builder',
            'parent_id' => null,
            'route_name' => 'pages.builder',
            'icon' => 'layout-dashboard',
            'urutan' => 999,
            'is_active' => true,
            'route_type' => 'admin',
            'controller_class' => 'App\\Http\\Controllers\\Panel\\PageController',
            'view_path' => null,
            'middleware_list' => ['auth', 'permission:update-pages'],
            'meta_title' => 'Page Builder',
            'meta_description' => 'Visual page builder for creating pages',
            'visit_count' => 0
        ]);

        echo "Page builder menu created successfully!\n";
    }
}
