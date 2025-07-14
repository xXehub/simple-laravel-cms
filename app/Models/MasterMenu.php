<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Permission\Models\Role;

class MasterMenu extends Model
{
    protected $fillable = [
        'nama_menu',
        'slug',
        'parent_id',
        'route_name',
        'icon',
        'urutan',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationship to parent menu
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MasterMenu::class, 'parent_id');
    }

    /**
     * Relationship to child menus
     */
    public function children(): HasMany
    {
        return $this->hasMany(MasterMenu::class, 'parent_id')->orderBy('urutan');
    }

    /**
     * Relationship to roles through menu_roles pivot table
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'menu_roles', 'mastermenu_id', 'role_id');
    }

    /**
     * Relationship to corresponding dynamic page (if exists)
     */
    public function page(): HasOne
    {
        return $this->hasOne(Page::class, 'slug', 'slug');
    }

    /**
     * Scope for root menus (no parent)
     */
    public function scopeRootMenus($query)
    {
        return $query->whereNull('parent_id')->orderBy('urutan');
    }

    /**
     * Scope for active menus only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get URL for this menu based on slug or route_name
     */
    public function getUrl()
    {
        // Handle special case for homepage (empty slug)
        if ($this->slug === '') {
            return url('/');
        }

        // If has slug, use slug-based URL
        if ($this->slug && $this->slug !== null) {
            return url($this->slug);
        }

        // Fallback to route_name if exists
        if ($this->route_name && \Route::has($this->route_name)) {
            return route($this->route_name);
        }

        // For parent menus or menus without slugs/routes, return a placeholder
        return '#';
    }

    /**
     * Check if this menu is accessible to current user
     */
    public function isAccessible()
    {
        if (!auth()->check()) {
            return false;
        }

        $user = auth()->user();

        // First check if user has role-based access to this menu
        $userRoles = $user->roles->pluck('id');
        $hasRoleAccess = $this->roles()->whereIn('role_id', $userRoles)->exists();

        // Get required permission based on menu slug or route
        $requiredPermission = $this->getRequiredPermission();

        // For menus with specific permission requirements
        if ($requiredPermission) {
            $hasPermissionAccess = $user->can($requiredPermission);
            return $hasRoleAccess || $hasPermissionAccess;
        }

        // For parent/container menus without specific permissions:
        // Allow if they have role access or no role restrictions
        if ($this->children && $this->children->isNotEmpty()) {
            return $hasRoleAccess || !$this->roles()->exists();
        }

        // For leaf menus without specific permissions (like public pages)
        // Allow if they have role access or no restrictions
        return $hasRoleAccess || !$this->roles()->exists();
    }

    /**
     * Get the required permission for this menu
     */
    public function getRequiredPermission()
    {
        // Map menu slugs/routes to required permissions
        $permissionMap = [
            'panel/users' => 'view-users',
            'panel/roles' => 'view-roles',
            'panel/permissions' => 'view-permissions',
            'panel/menus' => 'view-menus',
            'panel/pages' => 'view-pages',
            'panel/settings' => 'view-settings',
            'profile' => 'view-profile',
            'panel' => 'access-panel',
            'dashboard' => 'access-panel', // Panel Management requires panel access
            'panel/dashboard' => 'view-dashboard'
        ];

        // Check by exact slug match first
        if (isset($permissionMap[$this->slug])) {
            return $permissionMap[$this->slug];
        }

        // Check by route name if no slug match
        if ($this->route_name) {
            $routeName = $this->route_name;

            // Extract permission from route name (e.g., panel.users.index -> view-users)
            if (str_contains($routeName, 'panel.')) {
                $parts = explode('.', $routeName);
                if (count($parts) >= 2) {
                    $entity = $parts[1]; // users, roles, permissions, etc.
                    return "view-{$entity}";
                }
            }
        }

        // Check by slug pattern
        if ($this->slug) {
            // For panel/* slugs, require access-panel at minimum
            if (str_starts_with($this->slug, 'panel/')) {
                $slugParts = explode('/', $this->slug);
                if (count($slugParts) >= 2) {
                    $entity = $slugParts[1];
                    return "view-{$entity}";
                }
                return 'access-panel';
            }
        }

        // No specific permission required (public menu)
        return null;
    }

    /**
     * Check if this is a panel/admin menu
     */
    public function isPanelMenu()
    {
        return str_starts_with($this->slug ?? '', 'panel/');
    }

    /**
     * Get the display name with proper formatting
     */
    public function getDisplayNameAttribute()
    {
        return $this->nama_menu;
    }

    /**
     * Get full breadcrumb path
     */
    public function getBreadcrumbs()
    {
        $breadcrumbs = collect([$this]);
        $parent = $this->parent;

        while ($parent) {
            $breadcrumbs->prepend($parent);
            $parent = $parent->parent;
        }

        return $breadcrumbs;
    }

    /**
     * Check if current menu is active based on current URL (recursive)
     */
    public function isActive()
    {
        $currentUrl = url()->current();
        $menuUrl = $this->getUrl();

        // Direct match
        if ($currentUrl === $menuUrl) {
            return true;
        }

        // Check if current path matches menu slug
        if ($this->slug && request()->path() === $this->slug) {
            return true;
        }

        // Parent menu is active if any child is active (recursive)
        if ($this->children->isNotEmpty()) {
            return $this->children->contains(function ($child) {
                return $child->isActive();
            });
        }

        return false;
    }

    /**
     * Get accessible children (filtered by permissions)
     */
    public function getAccessibleChildren()
    {
        return $this->children->filter(function ($child) {
            return $child->canAccess();
        });
    }

    /**
     * Check if user can access this menu or any of its children (recursive)
     */
    public function canAccess()
    {
        // Can access directly
        if ($this->isAccessible()) {
            return true;
        }

        // Can access if has accessible children (recursive check)
        if ($this->children->isNotEmpty()) {
            return $this->children->contains(function ($child) {
                return $child->canAccess();
            });
        }

        return false;
    }

    /**
     * Check if this menu has any accessible children (recursive)
     */
    public function hasAccessibleChildren()
    {
        return $this->getAccessibleChildren()->isNotEmpty();
    }

    /**
     * Get root menus for sidebar with all nested children loaded recursively
     */
    public static function getSidebarMenus()
    {
        // Load root menus with recursive children using nested sets pattern
        return static::rootMenus()
            ->active()
            ->with([
                'children' => function ($query) {
                    $query->active()->orderBy('urutan')
                        ->with([
                            'children' => function ($query) {
                                $query->active()->orderBy('urutan')
                                    ->with([
                                        'children' => function ($query) {
                                            $query->active()->orderBy('urutan');
                                        }
                                    ]);
                            }
                        ]);
                }
            ])
            ->get()
            ->filter(function ($menu) {
                return $menu->canAccess();
            });
    }

    /**
     * Debug method to see all menus structure
     */

}
