<?php

namespace App\Models;

use App\Console\Commands\CacheDynamicRoutes;
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
        'is_active',
        'is_beranda',
        'route_type',
        'controller_class',
        'view_path',
        'middleware_list',
        'meta_title',
        'meta_description',
        'visit_count'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_beranda' => 'boolean',
        'middleware_list' => 'array',
        'route_type' => 'array',
    ];

    /**
     * Boot method - add model event listeners
     */
    protected static function boot()
    {
        parent::boot();

        // Invalidate cache when menu data changes
        static::saved(function ($menu) {
            CacheDynamicRoutes::invalidateCache();
        });

        static::deleted(function ($menu) {
            CacheDynamicRoutes::invalidateCache();
        });
    }

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
     * Get menu beranda (simple method)
     */
    public static function getBeranda()
    {
        return static::where('is_beranda', true)->where('is_active', true)->first();
    }

    /**
     * Increment visit count for this menu
     */
    public function incrementVisit()
    {
        $this->increment('visit_count');
        return $this;
    }

    /**
     * Get most visited menus
     */
    public static function getMostVisited($limit = 10)
    {
        return static::whereNotNull('slug')
            ->where('is_active', true)
            ->orderBy('visit_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Scope for menus ordered by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('id');
    }

    /**
     * Scope for searching menus by name
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama_menu', 'like', "%{$search}%")
              ->orWhere('slug', 'like', "%{$search}%")
              ->orWhere('route_name', 'like', "%{$search}%");
        });
    }

    /**
     * Scope for menus with specific role
     */
    public function scopeWithRole($query, $roleId)
    {
        return $query->whereHas('roles', function ($q) use ($roleId) {
            $q->where('role_id', $roleId);
        });
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
     * Debug method to see all menus structure
     */

    // =====================================
    // DYNAMIC ROUTING METHODS - PHASE 1
    // =====================================

    /**
     * Get the dynamic view path for this menu
     */
    public function getDynamicViewPath(): ?string
    {
        return $this->view_path;
    }

    /**
     * Check if this menu should use a view instead of controller
     */
    public function shouldUseView(): bool
    {
        return !empty($this->view_path) && empty($this->controller_class);
    }

    /**
     * Check if this menu should use controller
     */
    public function shouldUseController(): bool
    {
        return !empty($this->controller_class);
    }

    /**
     * Get the controller class for this menu
     */
    public function getControllerClass(): ?string
    {
        return $this->controller_class;
    }

    /**
     * Get the middleware list for this menu (alias for compatibility)
     */
    public function getMiddleware(): array
    {
        return $this->middleware_list;
    }

    /**
     * Get the middleware list for this menu (main method)
     */
    public function getMiddlewareList(): array
    {
        return $this->middleware_list;
    }

    /**
     * Get the route name for this menu (for helper functions)
     */
    public function getRouteName(): ?string
    {
        return $this->route_name;
    }

    /**
     * Get SEO title with fallback
     */
    public function getSeoTitle(): string
    {
        return $this->meta_title ?? $this->nama_menu;
    }

    /**
     * Get SEO description with fallback
     */
    public function getSeoDescription(): ?string
    {
        return $this->meta_description;
    }

    /**
     * Scope for routes that have controllers
     */
    public function scopeWithController($query)
    {
        return $query->whereNotNull('controller_class');
    }

    /**
     * Scope for routes that use views
     */
    public function scopeWithView($query)
    {
        return $query->whereNotNull('view_path');
    }

    /**
     * Check if menu has specific route type
     */
    public function hasRouteType(string $type): bool
    {
        return in_array($type, $this->route_type ?? []);
    }

    /**
     * Check if menu should appear in sidebar (contains admin route type)
     */
    public function shouldShowInSidebar(): bool
    {
        return $this->hasRouteType('admin');
    }

    /**
     * Check if menu should appear in top bar (contains public route type)
     */
    public function shouldShowInTopBar(): bool
    {
        return $this->hasRouteType('public');
    }

    /**
     * Check if menu is API only (only contains api route type)
     */
    public function isApiOnly(): bool
    {
        $routeTypes = $this->route_type ?? [];
        return count($routeTypes) === 1 && in_array('api', $routeTypes);
    }

    /**
     * Scope for admin routes only
     */
    public function scopeAdminRoutes($query)
    {
        return $query->whereJsonContains('route_type', 'admin');
    }

    /**
     * Scope for public routes only
     */
    public function scopePublicRoutes($query)
    {
        return $query->whereJsonContains('route_type', 'public');
    }

    /**
     * Scope for API routes only
     */
    public function scopeApiRoutes($query)
    {
        return $query->whereJsonContains('route_type', 'api');
    }

    /**
     * Scope for sidebar menus (has admin route type)
     */
    public function scopeForSidebar($query)
    {
        return $query->whereJsonContains('route_type', 'admin');
    }

    /**
     * Scope for top bar menus (has public route type)
     */
    public function scopeForTopBar($query)
    {
        return $query->whereJsonContains('route_type', 'public');
    }

    /**
     * Get root menus for sidebar with all nested children loaded recursively
     */
    public static function getSidebarMenus()
    {
        // Load root menus with recursive children using nested sets pattern
        return static::rootMenus()
            ->active()
            ->forSidebar() // Only menus with admin route type
            ->with([
                'children' => function ($query) {
                    $query->active()->forSidebar()->orderBy('urutan')
                        ->with([
                            'children' => function ($query) {
                                $query->active()->forSidebar()->orderBy('urutan')
                                    ->with([
                                        'children' => function ($query) {
                                            $query->active()->forSidebar()->orderBy('urutan');
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
     * Get root menus for top bar navigation
     */
    public static function getTopBarMenus()
    {
        return static::rootMenus()
            ->active()
            ->forTopBar() // Only menus with public route type
            ->with([
                'children' => function ($query) {
                    $query->active()->forTopBar()->orderBy('urutan')
                        ->with([
                            'children' => function ($query) {
                                $query->active()->forTopBar()->orderBy('urutan');
                            }
                        ]);
                }
            ])
            ->get();
    }

    /**
     * Get the full route name including prefix
     */
    public function getFullRouteName(): string
    {
        if ($this->route_name) {
            return $this->route_name;
        }

        // Generate route name based on route type and slug
        $prefix = $this->route_type === 'admin' ? 'panel.' : '';
        $slug = $this->slug ?: str_replace('/', '.', trim($this->menu_url, '/'));
        
        return $prefix . $slug;
    }

    /**
     * Check if this menu has permissions configured
     */
    public function hasPermissions(): bool
    {
        if ($this->route_type === 'public') {
            return false;
        }

        // Check if middleware contains permission
        $middleware = $this->getMiddleware();
        foreach ($middleware as $mid) {
            if (str_starts_with($mid, 'permission:')) {
                return true;
            }
        }

        return false;
    }

    // =====================================
    // AUTO-SCAN CONTROLLER METHODS - NEW IMPLEMENTATION
    // =====================================

    /**
     * Get all controller methods from controller_class using auto-scan
     */
    public function getAllControllerMethods(): array
    {
        if (!$this->controller_class) {
            return [];
        }
        
        $scanner = new \App\Services\SimpleControllerScanner();
        $methods = $scanner->scanController($this->controller_class);
        
        // Sort methods by priority to ensure specific routes come before parameterized routes
        usort($methods, function($a, $b) use ($scanner) {
            $priorityA = $scanner->getRoutePriority($a['method']);
            $priorityB = $scanner->getRoutePriority($b['method']);
            return $priorityB <=> $priorityA; // Higher priority first
        });
        
        return $methods;
    }

    /**
     * Generate view path for specific method
     */
    public function getViewPathForMethod(string $method): ?string
    {
        if (!$this->view_path) {
            return null;
        }
        
        return $this->view_path . '.' . $method;
    }

    /**
     * Get base view path for this menu
     */
    public function getViewPath(): ?string
    {
        return $this->view_path;
    }

    /**
     * Mutator for middleware_list attribute
     * Converts comma-separated string to array when saving
     */
    public function setMiddlewareListAttribute($value)
    {
        if (is_string($value)) {
            // Parse comma-separated string
            $middlewares = array_map('trim', explode(',', $value));
            $middlewares = array_filter($middlewares); // Remove empty values
            $this->attributes['middleware_list'] = json_encode(array_values($middlewares));
        } elseif (is_array($value)) {
            $this->attributes['middleware_list'] = json_encode($value);
        } else {
            $this->attributes['middleware_list'] = null;
        }
    }

    /**
     * Accessor for middleware_list attribute
     * Ensures we always get an array
     */
    public function getMiddlewareListAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }
        
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        return is_array($value) ? $value : [];
    }

    /**
     * Get formatted middleware string for display
     */
    public function getMiddlewareString(): string
    {
        $middlewares = $this->middleware_list;
        return is_array($middlewares) ? implode(', ', $middlewares) : '';
    }

    /**
     * Check if menu has specific middleware
     */
    public function hasMiddleware(string $middleware): bool
    {
        $middlewares = $this->middleware_list;
        return in_array($middleware, $middlewares);
    }

    /**
     * Add middleware to menu
     */
    public function addMiddleware(string $middleware): self
    {
        $middlewares = $this->middleware_list;
        if (!in_array($middleware, $middlewares)) {
            $middlewares[] = $middleware;
            $this->middleware_list = $middlewares;
        }
        return $this;
    }

    /**
     * Remove middleware from menu
     */
    public function removeMiddleware(string $middleware): self
    {
        $middlewares = $this->middleware_list;
        $this->middleware_list = array_values(array_filter($middlewares, function($m) use ($middleware) {
            return $m !== $middleware;
        }));
        return $this;
    }

    /**
     * Get common middleware templates
     */
    public static function getMiddlewareTemplates(): array
    {
        return [
            'web_auth' => ['web', 'auth'],
            'web_auth_permission' => ['web', 'auth', 'permission:'],
            'api_auth' => ['api', 'auth:sanctum'],
            'web_guest' => ['web', 'guest'],
            'api_throttle' => ['api', 'throttle:60,1'],
        ];
    }

    /**
     * Get common individual middlewares
     */
    public static function getCommonMiddlewares(): array
    {
        return [
            'web' => 'Web middleware group',
            'api' => 'API middleware group',
            'auth' => 'Authentication required',
            'auth:sanctum' => 'Sanctum authentication',
            'guest' => 'Guest users only',
            'verified' => 'Email verification required',
            'throttle:60,1' => 'Rate limiting (60 requests per minute)',
            'permission:' => 'Permission-based access (add permission name)',
            'role:' => 'Role-based access (add role name)',
        ];
    }
}
