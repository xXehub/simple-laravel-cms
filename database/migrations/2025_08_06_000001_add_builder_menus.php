<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Insert menu builder ke database
        DB::table('master_menus')->insertOrIgnore([
            [
                'id' => 999, // ID tinggi untuk menghindari konflik
                'nama_menu' => 'Page Builder',
                'slug' => 'panel/builder',
                'icon' => 'ti ti-layout-grid',
                'route_type' => 'admin',
                'controller_class' => 'App\\Http\\Controllers\\Panel\\PageBuilderController',
                'view_path' => null,
                'parent_id' => null,
                'urutan' => 100,
                'is_active' => true,
                'middleware_list' => json_encode(["web", "auth", "permission:view-pages"]),
                'meta_title' => 'Page Builder',
                'meta_description' => 'Build and design pages with drag & drop components',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Insert submenu untuk API components
        DB::table('master_menus')->insertOrIgnore([
            [
                'id' => 1000,
                'nama_menu' => 'Builder Components API',
                'slug' => 'panel/builder/components',
                'icon' => 'ti ti-api',
                'route_type' => 'api',
                'controller_class' => 'App\\Http\\Controllers\\Panel\\ComponentController',
                'view_path' => null,
                'parent_id' => 999,
                'urutan' => 1,
                'is_active' => true,
                'middleware_list' => json_encode(["web", "auth", "permission:view-pages"]),
                'meta_title' => 'Components API',
                'meta_description' => 'API endpoint for builder components',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Insert menu untuk render component API (POST)
        DB::table('master_menus')->insertOrIgnore([
            [
                'id' => 1001,
                'nama_menu' => 'Render Component API',
                'slug' => 'panel/builder/components/render',
                'icon' => 'ti ti-play',
                'route_type' => 'api',
                'controller_class' => 'App\\Http\\Controllers\\Panel\\ComponentController',
                'view_path' => null,
                'parent_id' => 999,
                'urutan' => 2,
                'is_active' => true,
                'middleware_list' => json_encode(["web", "auth", "permission:view-pages"]),
                'meta_title' => 'Render Component',
                'meta_description' => 'API endpoint for rendering components',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down()
    {
        DB::table('master_menus')->whereIn('id', [999, 1000, 1001])->delete();
    }
};
