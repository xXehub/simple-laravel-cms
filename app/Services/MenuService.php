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
            $options = array_merge($options, $childOptions);
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
}
