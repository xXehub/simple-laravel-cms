<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\MasterMenu;
use Illuminate\Support\Facades\Route;

class DynamicBreadcrumb extends Component
{
    public array $items;
    public string $currentTitle;

    /**
     * Create a new component instance.
     */
    public function __construct(?string $routeName = null, ?string $title = null)
    {
        $this->items = [];
        $this->currentTitle = $title ?? 'Page';
        
        // Auto-detect current route if not provided
        if (!$routeName) {
            $routeName = Route::currentRouteName();
        }
        
        if ($routeName) {
            $this->buildBreadcrumbFromRoute($routeName);
        }
        
        // If title not provided, try to get from menu
        if (!$title && $routeName) {
            $menu = MasterMenu::where('route_name', $routeName)->first();
            if ($menu) {
                $this->currentTitle = $menu->nama_menu;
            }
        }
    }

    /**
     * Build breadcrumb items from route name
     */
    private function buildBreadcrumbFromRoute(string $routeName): void
    {
        // Find the menu item for this route
        $currentMenu = MasterMenu::where('route_name', $routeName)
            ->where('is_active', true)
            ->first();

        if (!$currentMenu) {
            return;
        }

        // Build breadcrumb path from menu hierarchy
        $path = $this->getMenuPath($currentMenu);
        
        foreach ($path as $menu) {
            // Skip the current page (will be shown as title)
            if ($menu->id === $currentMenu->id) {
                continue;
            }
            
            $this->items[] = [
                'title' => $menu->nama_menu,
                'url' => $this->getMenuUrl($menu),
                'icon' => $menu->icon,
                'active' => false
            ];
        }
    }

    /**
     * Get the full path from root to current menu
     */
    private function getMenuPath(MasterMenu $menu): array
    {
        $path = [];
        $current = $menu;
        
        // Build path from current to root
        while ($current) {
            array_unshift($path, $current);
            $current = $current->parent;
        }
        
        return $path;
    }

    /**
     * Get URL for a menu item
     */
    private function getMenuUrl(MasterMenu $menu): ?string
    {
        if (!$menu->route_name) {
            return null;
        }

        try {
            return route($menu->route_name);
        } catch (\Exception $e) {
            // If route doesn't exist, try to generate dynamic URL
            if ($menu->slug) {
                return url($menu->slug);
            }
            return null;
        }
    }

    /**
     * Add manual breadcrumb item
     */
    public function addItem(string $title, ?string $url = null, ?string $icon = null): self
    {
        $this->items[] = [
            'title' => $title,
            'url' => $url,
            'icon' => $icon,
            'active' => false
        ];
        
        return $this;
    }

    /**
     * Set current page title
     */
    public function setTitle(string $title): self
    {
        $this->currentTitle = $title;
        return $this;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dynamic-breadcrumb');
    }
}
