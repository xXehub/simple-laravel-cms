<?php

/**
 * Menu Helper Functions
 * 
 * Clean, functional approach to menu rendering without if-else chains.
 * Each function has a single responsibility and uses functional programming patterns.
 * 
 * Architecture:
 * - menu_data(): Main function that aggregates all menu properties
 * - menu_url(): Safe URL generation with error handling
 * - menu_is_active(): Functional active state checking
 * - menu_*_classes(): CSS class generation
 * - menu_render_type(): Template selection logic
 * - menu_render_recursive(): Recursive unlimited nesting with permission checks
 * 
 * Permission Logic:
 * - Parent menus are only shown if user has access to them OR to any of their children
 * - Supports unlimited nesting levels with recursive permission checking
 * - Clean separation between permission logic and rendering logic
 * 
 * Usage in Blade:
 * @php $data = menu_data($menu); @endphp
 * @includeWhen($data['hasChildren'], 'menu-dropdown', $data)
 */

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

if (!function_exists('menu_url')) {
    /**
     * Get menu URL safely without exceptions
     */
    function menu_url($menu): string
    {
        return rescue(fn() => $menu->getUrl(), '#');
    }
}

if (!function_exists('menu_is_active')) {
    /**
     * Check if menu is currently active
     */
    function menu_is_active($menu): bool
    {
        $checkers = [
            fn() => url()->current() === menu_url($menu),
            fn() => $menu->slug && request()->path() === $menu->slug
        ];

        return collect($checkers)->contains(fn($check) => $check());
    }
}

if (!function_exists('menu_active_class')) {
    /**
     * Get CSS class for active menu state
     */
    function menu_active_class($menu): string
    {
        return menu_is_active($menu) ? 'active' : '';
    }
}

if (!function_exists('menu_has_children')) {
    /**
     * Check if menu has children
     */
    function menu_has_children($menu): bool
    {
        return $menu->children->count() > 0;
    }
}

if (!function_exists('menu_accessible_children')) {
    /**
     * Get accessible children for menu
     */
    function menu_accessible_children($menu)
    {
        return $menu->children->filter(fn($child) => $child->isAccessible());
    }
}

if (!function_exists('menu_link_classes')) {
    /**
     * Get CSS classes for menu link
     */
    function menu_link_classes($menu): string
    {
        $classes = ['nav-link', 'd-flex', 'align-items-center', menu_active_class($menu)];

        // Add collapsed class for dropdown menus
        if (menu_has_children($menu)) {
            $classes[] = 'collapsed';
        }

        return collect($classes)
            ->filter()
            ->implode(' ');
    }
}

if (!function_exists('menu_collapse_attrs')) {
    /**
     * Get collapse attributes for dropdown menu
     */
    function menu_collapse_attrs($menu): string
    {
        $attrs = [
            'data-bs-toggle' => 'collapse',
            'href' => "#submenu-{$menu->id}",
            'role' => 'button',
            'aria-expanded' => 'false',
            'aria-controls' => "submenu-{$menu->id}"
        ];

        return collect($attrs)
            ->map(fn($value, $key) => "{$key}=\"{$value}\"")
            ->implode(' ');
    }
}

if (!function_exists('menu_should_render')) {
    /**
     * Check if menu should be rendered
     */
    function menu_should_render($menu): bool
    {
        return $menu->isAccessible();
    }
}

if (!function_exists('menu_render_type')) {
    /**
     * Get menu render type (single or dropdown)
     */
    function menu_render_type($menu): string
    {
        return menu_has_children($menu) ? 'dropdown' : 'single';
    }
}

if (!function_exists('menu_icon')) {
    /**
     * Render menu icon safely
     */
    function menu_icon($menu): string
    {
        return $menu->icon ? "<i class=\"{$menu->icon} me-2\"></i>" : '';
    }
}

if (!function_exists('menu_data')) {
    /**
     * Get complete menu data array for rendering
     */
    function menu_data($menu): array
    {
        return [
            'menu' => $menu,
            'url' => menu_url($menu),
            'isActive' => menu_is_active($menu),
            'activeClass' => menu_active_class($menu),
            'linkClasses' => menu_link_classes($menu),
            'hasChildren' => menu_has_children($menu),
            'accessibleChildren' => menu_accessible_children($menu),
            'collapseAttrs' => menu_collapse_attrs($menu),
            'icon' => menu_icon($menu),
            'shouldRender' => menu_should_render($menu),
            'type' => menu_render_type($menu)
        ];
    }
}

if (!function_exists('menu_submenu_id')) {
    /**
     * Get submenu container ID
     */
    function menu_submenu_id($menu): string
    {
        return "submenu-{$menu->id}";
    }
}

if (!function_exists('menu_text')) {
    /**
     * Get escaped menu text
     */
    function menu_text($menu): string
    {
        return e($menu->nama_menu);
    }
}

if (!function_exists('menu_chevron')) {
    /**
     * Get chevron icon for dropdown menus with animation classes
     */
    function menu_chevron(): string
    {
        return '<i class="fas fa-chevron-down ms-auto menu-chevron"></i>';
    }
}

if (!function_exists('menu_has_accessible_content')) {
    /**
     * Check if menu has accessible content using functional approach
     * Eliminates if/else chains by using functional programming patterns
     */
    function menu_has_accessible_content($menu): bool
    {
        // Use functional composition to determine accessibility
        $accessibilityCheckers = [
            // Check 1: Direct menu accessibility for leaf nodes
            fn($m) => !$m->children->count() && $m->isAccessible(),
            
            // Check 2: Parent menu with accessible children (recursive)
            fn($m) => $m->children->count() && $m->children->contains(fn($child) => menu_has_accessible_content($child))
        ];
        
        // Apply all checkers and return true if any pass
        return collect($accessibilityCheckers)->contains(fn($checker) => $checker($menu));
    }
}

if (!function_exists('menu_should_show_parent')) {
    /**
     * Alias for menu_has_accessible_content for semantic clarity
     */
    function menu_should_show_parent($menu): bool
    {
        return menu_has_accessible_content($menu);
    }
}

if (!function_exists('menu_render_decision')) {
    /**
     * Single function to determine if menu should be rendered and how
     * Returns array with rendering decision and data
     */
    function menu_render_decision($menu): array
    {
        $shouldRender = menu_has_accessible_content($menu);
        
        return [
            'render' => $shouldRender,
            'data' => $shouldRender ? menu_data($menu) : null,
            'type' => $shouldRender ? (menu_has_children($menu) ? 'dropdown' : 'single') : 'none'
        ];
    }
}

if (!function_exists('menu_render_recursive')) {
    /**
     * Recursively render menu children with unlimited nesting
     * Only shows parent menus if user has permission or if they have accessible children
     */
    function menu_render_recursive($children, int $level = 1): string
    {
        if ($children->isEmpty()) {
            return '';
        }

        $indent = str_repeat('ms-3 ', $level);
        $html = "<ul class=\"nav flex-column {$indent}\">";

        foreach ($children as $child) {
            // Skip menu if user has no access to it or its children
            if (!menu_has_accessible_content($child)) {
                continue;
            }
            
            $childData = menu_data($child);
            
            $html .= '<li class="nav-item">';

            if ($childData['hasChildren']) {
                // Get only accessible children for rendering
                $accessibleChildren = $childData['accessibleChildren'];
                
                // Only render dropdown if there are accessible children or menu itself is accessible
                if ($accessibleChildren->isNotEmpty() || $childData['shouldRender']) {
                    // Render dropdown link
                    $html .= "<a class=\"{$childData['linkClasses']}\" {$childData['collapseAttrs']}>";
                    $html .= $childData['icon'];
                    $html .= menu_text($child);
                    $html .= menu_chevron();
                    $html .= "</a>";

                    // Render collapse container with recursive children
                    $html .= "<div class=\"collapse\" id=\"" . menu_submenu_id($child) . "\">";
                    $html .= menu_render_recursive($accessibleChildren, $level + 1);
                    $html .= "</div>";
                }
            } else {
                // Only render single menu item if user has access
                if ($childData['shouldRender']) {
                    $html .= "<a class=\"{$childData['linkClasses']}\" href=\"{$childData['url']}\">";
                    $html .= $childData['icon'];
                    $html .= menu_text($child);
                    $html .= "</a>";
                }
            }

            $html .= '</li>';
        }

        $html .= '</ul>';

        return $html;
    }
}

if (!function_exists('menu_all_accessible_children')) {
    /**
     * Get all accessible children recursively for a menu
     */
    function menu_all_accessible_children($menu)
    {
        $children = collect();

        foreach ($menu->children as $child) {
            if ($child->isAccessible()) {
                $children->push($child);
                $children = $children->merge(menu_all_accessible_children($child));
            }
        }

        return $children;
    }
}