<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Welcome page settings
            'welcome_title' => 'Judul Dinamis',
            'welcome_subtitle' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
            
            // Feature settings
            'feature_1_icon' => 'fas fa-shield-alt',
            'feature_1_title' => 'Role-Based Access',
            'feature_1_description' => 'Spatie Laravel Permission for granular access control with roles and permissions.',
            
            'feature_2_icon' => 'fas fa-bars',
            'feature_2_title' => 'Dynamic Menus',
            'feature_2_description' => 'Hierarchical menu system with role-based visibility using clean relationship queries.',
            
            'feature_3_icon' => 'fas fa-file-alt',
            'feature_3_title' => 'Dynamic Pages',
            'feature_3_description' => 'Slug-based routing with custom templates and SEO-friendly URL management.',
            
            'feature_4_icon' => 'fas fa-code',
            'feature_4_title' => 'Clean Code',
            'feature_4_description' => 'Modern Laravel practices with minimal conditionals, Blade components, and policies.',
            
            // Sample pages settings
            'sample_pages_title' => 'Explore Sample Pages',
            
            // General site settings
            'site_title' => 'Laravel Superapp CMS',
            'site_description' => 'Laravel-based Content Management System',
            'site_icon' => 'favicon.ico',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
