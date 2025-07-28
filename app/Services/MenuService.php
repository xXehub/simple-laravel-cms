<?php

namespace App\Services;

use App\Models\MasterMenu;
use Illuminate\Database\Eloquent\Collection;

class MenuService
{
    /**
     * Get formatted menu options for parent selection
     */
    public function getMenuOptions($parentId = null, int $level = 0, ?int $excludeId = null): array
    {
        $menus = MasterMenu::where('parent_id', $parentId)
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();

        $options = [];

        foreach ($menus as $menu) {
            if ($excludeId && $menu->id == $excludeId) {
                continue;
            }

            $prefix = str_repeat('└─ ', $level);
            $options[$menu->id] = $prefix . $menu->nama_menu;

            // Recursively get child options
            $childOptions = $this->getMenuOptions($menu->id, $level + 1, $excludeId);
            $options = $options + $childOptions; // Use + instead of array_merge to preserve keys
        }

        return $options;
    }

    /**
     * Get siblings of a menu item for reordering
     */
    public function getSiblings(?int $parentId): Collection
    {
        return MasterMenu::where('parent_id', $parentId)
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();
    }

    /**
     * Reorder menu items
     */
    public function reorderMenus(array $orderData): bool
    {
        try {
            foreach ($orderData as $order => $menuId) {
                $menu = MasterMenu::find($menuId);
                if ($menu) {
                    $menu->update(['urutan' => $order + 1]);
                    
                    // Also update children if needed
                    $children = $menu->children()->orderBy('urutan')->get();
                    foreach ($children as $index => $child) {
                        $child->update(['urutan' => $index + 1]);
                    }
                }
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Move menu item up in order
     */
    public function moveUp(int $menuId): bool
    {
        $menu = MasterMenu::find($menuId);
        if (!$menu) {
            return false;
        }

        $siblings = $this->getSiblings($menu->parent_id);
        $currentIndex = $siblings->search(function($item) use ($menuId) {
            return $item->id == $menuId;
        });

        if ($currentIndex === false || $currentIndex == 0) {
            return false; // Already at top or not found
        }

        // Swap with previous item
        $previousItem = $siblings[$currentIndex - 1];
        $currentOrder = $menu->urutan;
        $previousOrder = $previousItem->urutan;

        $menu->update(['urutan' => $previousOrder]);
        $previousItem->update(['urutan' => $currentOrder]);

        return true;
    }

    /**
     * Move menu item down in order
     */
    public function moveDown(int $menuId): bool
    {
        $menu = MasterMenu::find($menuId);
        if (!$menu) {
            return false;
        }

        $siblings = $this->getSiblings($menu->parent_id);
        $currentIndex = $siblings->search(function($item) use ($menuId) {
            return $item->id == $menuId;
        });

        if ($currentIndex === false || $currentIndex == $siblings->count() - 1) {
            return false; // Already at bottom or not found
        }

        // Swap with next item
        $nextItem = $siblings[$currentIndex + 1];
        $currentOrder = $menu->urutan;
        $nextOrder = $nextItem->urutan;

        $menu->update(['urutan' => $nextOrder]);
        $nextItem->update(['urutan' => $currentOrder]);

        return true;
    }

    /**
     * Check if menu can be deleted (has no children)
     */
    public function canDelete(int $menuId): bool
    {
        $menu = MasterMenu::find($menuId);
        return $menu && $menu->children()->count() === 0;
    }

    /**
     * Get menu tree structure for display
     */
    public function getMenuTree($parentId = null, int $level = 0): array
    {
        $menus = MasterMenu::where('parent_id', $parentId)
            ->with(['roles'])
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();

        $tree = [];

        foreach ($menus as $menu) {
            $menuData = [
                'id' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'slug' => $menu->slug,
                'route_name' => $menu->route_name,
                'icon' => $menu->icon,
                'level' => $level,
                'is_active' => $menu->is_active,
                'roles' => $menu->roles->pluck('name')->toArray(),
                'children' => $this->getMenuTree($menu->id, $level + 1)
            ];

            $tree[] = $menuData;
        }

        return $tree;
    }

    /**
     * Build breadcrumb from menu
     */
    public function buildBreadcrumb(int $menuId): array
    {
        $breadcrumb = [];
        $menu = MasterMenu::find($menuId);

        while ($menu) {
            array_unshift($breadcrumb, [
                'id' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'slug' => $menu->slug
            ]);
            
            $menu = $menu->parent;
        }

        return $breadcrumb;
    }

    /**
     * Get public navbar menus for authenticated users
     * Returns hierarchical menu structure for navbar display
     */
    public function getNavbarMenus(): array
    {
        // Get current URL for active state detection
        $currentUrl = request()->url();
        $currentPath = request()->path();
        
        // Get only root level menus that are public and active
        $rootMenus = MasterMenu::whereNull('parent_id')
            ->where('route_type', 'public')
            ->where('is_active', true)
            ->orderBy('urutan')
            ->get();

        $navbarMenus = [];

        foreach ($rootMenus as $menu) {
            // Check if user has access to this menu
            if (!$menu->isAccessible()) {
                continue;
            }

            $menuUrl = $this->buildMenuUrl($menu);
            $children = $this->getAccessibleChildren($menu->id, $currentUrl, $currentPath);
            
            // Check if this menu or any of its children is active
            $isActive = $this->isMenuActive($menu, $currentUrl, $currentPath, $children);

            $menuData = [
                'id' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'slug' => $menu->slug,
                'route_name' => $menu->route_name,
                'icon' => $menu->icon,
                'url' => $menuUrl,
                'is_active' => $isActive,
                'children' => $children
            ];

            // Only add menu if it has accessible children or is accessible itself
            if (!empty($menuData['children']) || $menu->isAccessible()) {
                $navbarMenus[] = $menuData;
            }
        }

        return $navbarMenus;
    }

    /**
     * Get accessible children for a menu with active state detection
     */
    private function getAccessibleChildren(int $parentId, string $currentUrl, string $currentPath): array
    {
        $children = MasterMenu::where('parent_id', $parentId)
            ->where('route_type', 'public')
            ->where('is_active', true)
            ->orderBy('urutan')
            ->get();

        $accessibleChildren = [];

        foreach ($children as $child) {
            if (!$child->isAccessible()) {
                continue;
            }

            $childUrl = $this->buildMenuUrl($child);
            $grandChildren = $this->getAccessibleChildren($child->id, $currentUrl, $currentPath);
            
            // Check if this child menu is active
            $isActive = $this->isMenuActive($child, $currentUrl, $currentPath, $grandChildren);

            $childData = [
                'id' => $child->id,
                'nama_menu' => $child->nama_menu,
                'slug' => $child->slug,
                'route_name' => $child->route_name,
                'icon' => $child->icon,
                'url' => $childUrl,
                'is_active' => $isActive,
                'children' => $grandChildren
            ];

            $accessibleChildren[] = $childData;
        }

        return $accessibleChildren;
    }

    /**
     * Check if a menu is currently active
     */
    private function isMenuActive(MasterMenu $menu, string $currentUrl, string $currentPath, array $children = []): bool
    {
        // Check exact URL match
        $menuUrl = $this->buildMenuUrl($menu);
        if ($menuUrl === $currentUrl) {
            return true;
        }

        // Check if current path matches menu slug
        if ($menu->slug && trim($currentPath, '/') === trim($menu->slug, '/')) {
            return true;
        }

        // Check if current path starts with menu slug (for nested pages)
        if ($menu->slug && str_starts_with(trim($currentPath, '/'), trim($menu->slug, '/'))) {
            return true;
        }

        // Check if any children are active
        foreach ($children as $child) {
            if ($child['is_active']) {
                return true;
            }
        }

        return false;
    }

    /**
     * Build URL for menu item
     */
    private function buildMenuUrl(MasterMenu $menu): string
    {
        // If menu has a route name, use it
        if ($menu->route_name) {
            try {
                return route($menu->route_name);
            } catch (\Exception $e) {
                // Fallback to slug-based URL if route doesn't exist
            }
        }

        // If menu has a slug, build URL with it
        if ($menu->slug) {
            return url('/' . $menu->slug);
        }

        // Default fallback for parent menus
        return '#';
    }
}
