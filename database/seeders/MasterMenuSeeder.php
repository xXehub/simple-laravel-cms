<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterMenu;

class MasterMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --------------------
        // PUBLIC MENUS
        // --------------------
        $beranda = MasterMenu::create([
            'nama_menu' => 'Beranda',
            'slug' => '/beranda',
            'parent_id' => null,
            'route_name' => 'beranda',
            'icon' => 'fa-solid fa-plane-departure',
            'urutan' => 1,
            'is_active' => 1,
            'route_type' => 'public',
            'controller_class' => 'App\\Http\\Controllers\\BerandaController',
            'view_path' => 'beranda',
            'middleware_list' => ["web", "auth"],
            'meta_title' => 'Home',
            'meta_description' => 'Welcome to our website',
        ]);

        // --------------------
        // ADMIN MENUS
        // --------------------
        $dashboard = MasterMenu::create([
            'nama_menu' => 'Dashboard',
            'slug' => 'panel/dashboard',
            'parent_id' => null,
            'route_name' => 'panel.dashboard',
            'icon' => 'fa-solid fa-table-columns',
            'urutan' => 2,
            'is_active' => 1,
            'route_type' => 'admin',
            'controller_class' => 'App\\Http\\Controllers\\Panel\\DashboardController',
            'view_path' => null,
            'middleware_list' => ["web", "auth", "permission:view-dashboard"],
            'meta_title' => 'Admin Dashboard',
            'meta_description' => 'Administrative dashboard with system overview',
        ]);
        $panelManagement = MasterMenu::create([
            'nama_menu' => 'Panel Management',
            'slug' => null,
            'parent_id' => null,
            'route_name' => null,
            'icon' => 'fas fa-bars-progress',
            'urutan' => 3,
            'is_active' => 1,
            'route_type' => 'admin',
            'controller_class' => 'App\\Http\\Controllers\\RedirectController',
            'view_path' => null,
            'middleware_list' => ["web", "auth"],
            'meta_title' => 'Panel Management',
            'meta_description' => 'Navigate to Panel Management section',
        ]);
        $panelManagementId = $panelManagement->getKey();
        $users = MasterMenu::create([
            'nama_menu' => 'Users',
            'slug' => 'panel/users',
            'parent_id' => $panelManagementId,
            'route_name' => 'panel.users.index',
            'icon' => 'fas fa-users',
            'urutan' => 4,
            'is_active' => 1,
            'route_type' => 'admin',
            'controller_class' => 'App\\Http\\Controllers\\Panel\\UserController',
            'view_path' => 'panel.users.index',
            'middleware_list' => ["web", "auth", "permission:view-users"],
            'meta_title' => 'User Management',
            'meta_description' => 'Manage system users and their roles',
        ]);
        $roles = MasterMenu::create([
            'nama_menu' => 'Roles',
            'slug' => 'panel/roles',
            'parent_id' => $panelManagementId,
            'route_name' => 'panel.roles.index',
            'icon' => 'fas fa-user-tag',
            'urutan' => 5,
            'is_active' => 1,
            'route_type' => 'admin',
            'controller_class' => 'App\\Http\\Controllers\\Panel\\RoleController',
            'view_path' => null,
            'middleware_list' => ["web", "auth", "permission:view-roles"],
            'meta_title' => 'Role Management',
            'meta_description' => 'Manage user roles and permissions',
        ]);
        $permissions = MasterMenu::create([
            'nama_menu' => 'Permissions',
            'slug' => 'panel/permissions',
            'parent_id' => $panelManagementId,
            'route_name' => 'panel.permissions.index',
            'icon' => 'fas fa-key',
            'urutan' => 6,
            'is_active' => 1,
            'route_type' => 'admin',
            'controller_class' => 'App\\Http\\Controllers\\Panel\\PermissionController',
            'view_path' => null,
            'middleware_list' => ["web", "auth", "permission:view-permissions"],
            'meta_title' => 'Permission Management',
            'meta_description' => 'Manage system permissions',
        ]);
        $menus = MasterMenu::create([
            'nama_menu' => 'Menus',
            'slug' => 'panel/menus',
            'parent_id' => $panelManagementId,
            'route_name' => 'panel.menus',
            'icon' => 'fas fa-bars',
            'urutan' => 7,
            'is_active' => 1,
            'route_type' => 'admin',
            'controller_class' => 'App\\Http\\Controllers\\Panel\\MenuController',
            'view_path' => null,
            'middleware_list' => ["web", "auth", "permission:view-menus"],
            'meta_title' => 'Menu Management',
            'meta_description' => 'Manage dynamic menu structure',
        ]);
        $pages = MasterMenu::create([
            'nama_menu' => 'Pages',
            'slug' => 'panel/pages',
            'parent_id' => $panelManagementId,
            'route_name' => 'panel.pages.index',
            'icon' => 'fas fa-file-alt',
            'urutan' => 8,
            'is_active' => 1,
            'route_type' => 'admin',
            'controller_class' => 'App\\Http\\Controllers\\Panel\\PageController',
            'view_path' => null,
            'middleware_list' => ["web", "auth", "permission:view-pages"],
            'meta_title' => 'Page Management',
            'meta_description' => 'Manage CMS pages and content',
        ]);
        $settings = MasterMenu::create([
            'nama_menu' => 'Settings',
            'slug' => 'panel/settings',
            'parent_id' => $panelManagementId,
            'route_name' => 'panel.settings.index',
            'icon' => 'fas fa-cog',
            'urutan' => 9,
            'is_active' => 1,
            'route_type' => 'admin',
            'controller_class' => 'App\\Http\\Controllers\\Panel\\SettingController',
            'view_path' => null,
            'middleware_list' => ["web", "auth", "permission:view-settings"],
            'meta_title' => 'System Settings',
            'meta_description' => 'Configure system settings and preferences',
        ]);

        $about = MasterMenu::create([
            'nama_menu' => 'About Us',
            'slug' => 'about-us',
            'parent_id' => null,
            'route_name' => 'about',
            'icon' => 'fas fa-info-circle',
            'urutan' => 10,
            'is_active' => 1,
            'route_type' => 'public',
            'controller_class' => null,
            'view_path' => 'pages.about',
            'middleware_list' => ["web"],
            'meta_title' => 'About Us',
            'meta_description' => 'Learn more about our company and mission',
        ]);
        $articlesPublic = MasterMenu::create([
            'nama_menu' => 'Articles',
            'slug' => 'articles',
            'parent_id' => null,
            'route_name' => 'articles.index',
            'icon' => 'fas fa-newspaper',
            'urutan' => 11,
            'is_active' => 1,
            'route_type' => 'public',
            'controller_class' => 'App\\Http\\Controllers\\ArticleController',
            'view_path' => null,
            'middleware_list' => ["web"],
            'meta_title' => 'Articles',
            'meta_description' => 'Browse our latest articles',
        ]);
        $contact = MasterMenu::create([
            'nama_menu' => 'Contact',
            'slug' => 'contact',
            'parent_id' => null,
            'route_name' => 'contact',
            'icon' => 'fas fa-envelope',
            'urutan' => 12,
            'is_active' => 1,
            'route_type' => 'public',
            'controller_class' => null,
            'view_path' => 'pages.contact',
            'middleware_list' => ["web"],
            'meta_title' => 'Contact Us',
            'meta_description' => 'Get in touch with our team',
        ]);

        // --------------------
        // ADVANCED MENUS
        // --------------------
        $advancedManagement = MasterMenu::create([
            'nama_menu' => 'Dalam Proses',
            'slug' => null,
            'parent_id' => null,
            'route_name' => null,
            'icon' => 'fas fa-layer-group',
            'urutan' => 30,
            'is_active' => 1,
            'route_type' => 'admin',
            'controller_class' => 'App\\Http\\Controllers\\RedirectController',
            'view_path' => null,
            'middleware_list' => ["web", "auth"],
            'meta_title' => 'Advanced Management',
            'meta_description' => 'Navigate to Advanced Management section',
        ]);
        $advancedManagementId = $advancedManagement->getKey();
        $contentManagement = MasterMenu::create([
            'nama_menu' => 'Content Management',
            'slug' => null,
            'parent_id' => $advancedManagementId,
            'route_name' => null,
            'icon' => 'fas fa-folder',
            'urutan' => 31,
            'is_active' => 1,
            'route_type' => 'admin',
            'controller_class' => 'App\\Http\\Controllers\\RedirectController',
            'view_path' => null,
            'middleware_list' => ["web", "auth"],
            'meta_title' => 'Content Management',
            'meta_description' => 'Navigate to Content Management section',
        ]);
        $contentManagementId = $contentManagement->getKey();
        $articlesAdvanced = MasterMenu::create([
            'nama_menu' => 'Articles',
            'slug' => null,
            'parent_id' => $contentManagementId,
            'route_name' => null,
            'icon' => 'fas fa-newspaper',
            'urutan' => 32,
            'is_active' => 1,
            'route_type' => 'admin',
            'controller_class' => 'App\\Http\\Controllers\\RedirectController',
            'view_path' => null,
            'middleware_list' => ["web", "auth"],
            'meta_title' => 'Article Management',
            'meta_description' => 'Navigate to Article Management section',
        ]);
        $articlesAdvancedId = $articlesAdvanced->getKey();
        $articleCategories = MasterMenu::create([
            'nama_menu' => 'Article Categories',
            'slug' => 'panel/article-categories',
            'parent_id' => $articlesAdvancedId,
            'route_name' => null,
            'icon' => 'fas fa-tags',
            'urutan' => 33,
            'is_active' => 1,
            'route_type' => 'admin',
            'controller_class' => 'App\\Http\\Controllers\\Panel\\ArticleCategoryController',
            'view_path' => null,
            'middleware_list' => ["web", "auth", "permission:view-articles"],
            'meta_title' => 'Article Categories',
            'meta_description' => 'Manage article categories',
        ]);
        $articleTags = MasterMenu::create([
            'nama_menu' => 'Article Tags',
            'slug' => 'panel/article-tags',
            'parent_id' => $articlesAdvancedId,
            'route_name' => null,
            'icon' => 'fas fa-hashtag',
            'urutan' => 34,
            'is_active' => 1,
            'route_type' => 'admin',
            'controller_class' => 'App\\Http\\Controllers\\Panel\\ArticleTagController',
            'view_path' => null,
            'middleware_list' => ["web", "auth", "permission:view-articles"],
            'meta_title' => 'Article Tags',
            'meta_description' => 'Manage article tags',
        ]);
        $media = MasterMenu::create([
            'nama_menu' => 'Media',
            'slug' => null,
            'parent_id' => $contentManagementId,
            'route_name' => null,
            'icon' => 'fas fa-photo-video',
            'urutan' => 35,
            'is_active' => 1,
            'route_type' => 'admin',
            'controller_class' => 'App\\Http\\Controllers\\RedirectController',
            'view_path' => null,
            'middleware_list' => ["web", "auth"],
            'meta_title' => 'Media Management',
            'meta_description' => 'Navigate to Media Management section',
        ]);
        $mediaId = $media->getKey();
        $images = MasterMenu::create([
            'nama_menu' => 'Images',
            'slug' => 'panel/media/images',
            'parent_id' => $mediaId,
            'route_name' => null,
            'icon' => 'fas fa-images',
            'urutan' => 36,
            'is_active' => 1,
            'route_type' => 'admin',
            'controller_class' => 'App\\Http\\Controllers\\Panel\\MediaController',
            'view_path' => null,
            'middleware_list' => ["web", "auth", "permission:view-media"],
            'meta_title' => 'Image Management',
            'meta_description' => 'Manage uploaded images',
        ]);
        $videos = MasterMenu::create([
            'nama_menu' => 'Videos',
            'slug' => 'panel/media/videos',
            'parent_id' => $mediaId,
            'route_name' => null,
            'icon' => 'fas fa-video',
            'urutan' => 37,
            'is_active' => 1,
            'route_type' => 'admin',
            'controller_class' => 'App\\Http\\Controllers\\Panel\\MediaController',
            'view_path' => null,
            'middleware_list' => ["web", "auth", "permission:view-media"],
            'meta_title' => 'Video Management',
            'meta_description' => 'Manage uploaded videos',
        ]);
        $systemTools = MasterMenu::create([
            'nama_menu' => 'System Tools',
            'slug' => null,
            'parent_id' => $advancedManagementId,
            'route_name' => null,
            'icon' => 'fas fa-tools',
            'urutan' => 40,
            'is_active' => 1,
            'route_type' => 'admin',
            'controller_class' => 'App\\Http\\Controllers\\RedirectController',
            'view_path' => null,
            'middleware_list' => ["web", "auth"],
            'meta_title' => 'System Tools',
            'meta_description' => 'Navigate to System Tools section',
        ]);
        $systemToolsId = $systemTools->getKey();
        $monitoring = MasterMenu::create([
            'nama_menu' => 'Monitoring',
            'slug' => null,
            'parent_id' => $systemToolsId,
            'route_name' => null,
            'icon' => 'fas fa-chart-line',
            'urutan' => 41,
            'is_active' => 1,
            'route_type' => 'admin',
            'controller_class' => 'App\\Http\\Controllers\\RedirectController',
            'view_path' => null,
            'middleware_list' => ["web", "auth"],
            'meta_title' => 'System Monitoring',
            'meta_description' => 'Navigate to System Monitoring section',
        ]);
        $monitoringId = $monitoring->getKey();
        $systemLogs = MasterMenu::create([
            'nama_menu' => 'System Logs',
            'slug' => 'panel/logs',
            'parent_id' => $monitoringId,
            'route_name' => null,
            'icon' => 'fas fa-file-code',
            'urutan' => 42,
            'is_active' => 1,
            'route_type' => 'admin',
            'controller_class' => 'App\\Http\\Controllers\\Panel\\SystemController',
            'view_path' => null,
            'middleware_list' => ["web", "auth", "permission:view-system"],
            'meta_title' => 'System Logs',
            'meta_description' => 'View system logs and errors',
        ]);
        $performance = MasterMenu::create([
            'nama_menu' => 'Performance',
            'slug' => 'panel/performance',
            'parent_id' => $monitoringId,
            'route_name' => null,
            'icon' => 'fas fa-tachometer-alt',
            'urutan' => 43,
            'is_active' => 1,
            'route_type' => 'admin',
            'controller_class' => 'App\\Http\\Controllers\\Panel\\SystemController',
            'view_path' => null,
            'middleware_list' => ["web", "auth", "permission:view-system"],
            'meta_title' => 'Performance Monitor',
            'meta_description' => 'Monitor system performance metrics',
        ]);

        // $this->command->info("\n🎯 MasterMenu seeding completed!");
        $this->command->info("✅ total menu yang dibuat: " . MasterMenu::count());
    }
}
