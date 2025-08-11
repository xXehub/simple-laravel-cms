<?php

if (!function_exists('setting')) {
    /**
     * Get a setting value by key with optional default.
     * Uses static cache for performance.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        static $settings = [];

        if (!isset($settings[$key])) {
            $settings[$key] = \App\Models\Setting::getValue($key, $default);
        }

        return $settings[$key];
    }
}

if (!function_exists('setting_image')) {
    /**
     * Get image URL from settings with proper path handling
     * 
     * @param string $key Setting key
     * @param string $default Default value
     * @return string
     */
    function setting_image(string $key, string $default = ''): string
    {
        $imagePath = setting($key, $default);
        
        // If image is uploaded (starts with 'settings/'), add storage prefix
        if (str_starts_with($imagePath, 'settings/')) {
            return '/storage/' . $imagePath;
        }
        
        // Return as-is for static files
        return $imagePath;
    }
}

if (!function_exists('menu_route')) {
    /**
     * Get URL for a menu by its route name or slug
     * 
     * @param string $identifier Route name or slug
     * @return string
     */
    function menu_route(string $identifier): string
    {
        static $cache = [];
        
        if (isset($cache[$identifier])) {
            return $cache[$identifier];
        }
        
        // Try to find menu by route_name first, then by slug
        $menu = \App\Models\MasterMenu::where('route_name', $identifier)
            ->orWhere('slug', $identifier)
            ->where('is_active', true)
            ->first();
            
        if ($menu) {
            $cache[$identifier] = $menu->getUrl();
            return $cache[$identifier];
        }
        
        // Fallback to URL with identifier
        $cache[$identifier] = url($identifier);
        return $cache[$identifier];
    }
}

if (!function_exists('menu_route_name')) {
    /**
     * Get route name for a menu by its slug
     * 
     * @param string $slug
     * @return string|null
     */
    function menu_route_name(string $slug): ?string
    {
        static $cache = [];
        
        if (isset($cache[$slug])) {
            return $cache[$slug];
        }
        
        $menu = \App\Models\MasterMenu::where('slug', $slug)
            ->where('is_active', true)
            ->first();
            
        if ($menu) {
            $cache[$slug] = $menu->getRouteName();
            return $cache[$slug];
        }
        
        return null;
    }
}