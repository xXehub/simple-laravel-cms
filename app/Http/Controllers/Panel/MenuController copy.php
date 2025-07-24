<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\MasterMenu;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Http\Requests\Panel\Menu\StoreMenuRequest;
use App\Http\Requests\Panel\Menu\UpdateMenuRequest;
use Illuminate\Support\Facades\DB;
use App\Helpers\ResponseHelper;
use App\Services\MenuService;

class MenuController extends Controller
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
        $this->middleware(['auth']);
        $this->middleware('permission:view-menus')->only(['index']);
        $this->middleware('permission:create-menus')->only(['create', 'store']);
        $this->middleware('permission:update-menus')->only(['edit', 'update', 'moveOrder']);
        $this->middleware('permission:delete-menus')->only(['destroy', 'bulkDestroy']);
    }

    /**
     * Display menus listing
     */
    public function index(Request $request)
    {
        $parentMenus = $this->menuService->getMenuOptions();
        $roles = Role::all();

        if ($request->ajax() && $request->has('draw')) {
            return $this->getDataTablesData($request);
        }

        if ($request->ajax()) {
            $excludeId = $request->get('exclude_id');
            $parentMenus = $this->menuService->getMenuOptions(null, 0, $excludeId);

            // Ensure parentMenus is treated as an object in JSON, not an array
            $parentMenus = (object) $parentMenus;

            return response()->json([
                'parentMenus' => $parentMenus,
                'roles' => $roles->pluck('name', 'id')
            ]);
        }

        return view('panel.menus.index', compact('parentMenus', 'roles'));
    }

    /**
     * Get DataTables data for AJAX request
     */
    private function getDataTablesData(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $search = $request->get('search')['value'] ?? '';
        $orderColumn = $request->get('order')[0]['column'] ?? 0;
        $orderDir = $request->get('order')[0]['dir'] ?? 'asc';

        $columns = [
            1 => 'id',
            2 => 'nama_menu',
            3 => 'slug',
            4 => 'parent_id',
            5 => 'route_name',
            6 => null,
            7 => 'urutan',
            8 => null,
            9 => null,
            10 => null
        ];

        $orderBy = isset($columns[$orderColumn]) && $columns[$orderColumn] !== null
            ? $columns[$orderColumn]
            : 'urutan';

        $query = MasterMenu::with('roles', 'parent', 'children');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_menu', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('route_name', 'like', "%{$search}%")
                    ->orWhereHas('parent', function ($subQ) use ($search) {
                        $subQ->where('nama_menu', 'like', "%{$search}%");
                    });
            });
        }

        $totalRecords = MasterMenu::count();
        $filteredRecords = $query->count();

        $menus = $query->orderBy($orderBy, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        $data = [];
        foreach ($menus as $index => $menu) {
            // Get only direct siblings (same parent_id) to determine if menu is first or last among siblings
            $siblings = MasterMenu::where('parent_id', $menu->parent_id)
                ->orderBy('urutan', 'asc')
                ->pluck('id')
                ->toArray();

            $currentPosition = array_search($menu->id, $siblings);
            $isFirst = $currentPosition === 0;
            $isLast = $currentPosition === (count($siblings) - 1);


            $menuData = [
                'id' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'slug' => $menu->slug,
                'parent' => $menu->parent ? $menu->parent->nama_menu : null,
                'route_name' => $menu->route_name,
                'icon' => $menu->icon,
                'urutan' => $menu->urutan,
                'is_active' => (bool) $menu->is_active,
                'roles' => $menu->roles->pluck('name')->toArray(),
                'parent_id' => $menu->parent_id,
                'is_first' => $isFirst,
                'is_last' => $isLast,
                'order_controls' => view('components.modals.menus.order-controls', ['menu' => array_merge($menu->toArray(), ['is_first' => $isFirst, 'is_last' => $isLast])])->render(),
                'actions' => view('components.modals.menus.action', ['menu' => array_merge($menu->toArray(), ['is_first' => $isFirst, 'is_last' => $isLast])])->render()
            ];

            $data[] = $menuData;
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    /**
     * Show create menu form
     */
    public function create()
    {
        $parentMenus = $this->menuService->getMenuOptions();
        $roles = Role::all();
        return view('panel.menus.create', compact('parentMenus', 'roles'));
    }

    /**
     * Store new menu
     */
    public function store(StoreMenuRequest $request)
    {
        $data = $request->validated();

        $menu = MasterMenu::create($data);

        if ($request->has('roles')) {
            $menu->roles()->sync($request->input('roles'));
        }

        return ResponseHelper::redirect('panel.menus', 'Menu created successfully');
    }

    /**
     * Show edit menu form
     */
    public function edit(Request $request)
    {
        $menuId = $request->route('id') ?? $request->input('id');

        if (!$menuId) {
            return ResponseHelper::redirect('panel.menus', 'Menu ID not provided', 'error');
        }

        $menu = MasterMenu::findOrFail($menuId);
        $parentMenus = $this->menuService->getMenuOptions(null, 0, $menu->id);
        $roles = Role::all();

        return view('panel.menus.edit', compact('menu', 'parentMenus', 'roles'));
    }

    /**
     * Update menu
     */
    public function update(UpdateMenuRequest $request)
    {
        $menuId = $request->route('id') ?? $request->input('id');
        $menu = MasterMenu::findOrFail($menuId);

        $data = $request->validated();
        $menu->update($data);

        $menu->roles()->sync($request->input('roles', []));

        return ResponseHelper::redirect('panel.menus', 'Menu updated successfully');
    }

    /**
     * Delete menu
     */
    public function destroy(Request $request)
    {
        $menuId = $request->route('id') ?? $request->input('id');
        $menu = MasterMenu::findOrFail($menuId);

        if ($menu->children()->count() > 0) {
            return ResponseHelper::back('Cannot delete menu with child menus', 'error');
        }

        $menu->delete();

        return ResponseHelper::redirect('panel.menus', 'Menu deleted successfully');
    }

    /**
     * Bulk delete menus
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'menu_ids' => 'required|array',
            'menu_ids.*' => 'exists:master_menus,id'
        ]);

        $menuIds = $request->input('menu_ids');
        $deletedCount = 0;
        $errors = [];

        foreach ($menuIds as $menuId) {
            $menu = MasterMenu::find($menuId);

            if (!$menu) {
                continue;
            }

            if (!$this->menuService->canDelete($menuId)) {
                $errors[] = "Cannot delete '{$menu->nama_menu}' - has child menus";
                continue;
            }

            $menu->delete();
            $deletedCount++;
        }

        if ($deletedCount > 0) {
            $message = "Successfully deleted {$deletedCount} menu(s)";
            $type = 'success';
            if (!empty($errors)) {
                $message .= ". However, some menus could not be deleted: " . implode(', ', $errors);
            }
        } else {
            $message = 'No menus were deleted. ' . implode(', ', $errors);
            $type = 'error';
        }

        return ResponseHelper::handle($request, 'panel.menus', $message, [
            'deleted_count' => $deletedCount
        ], $type);
    }

    /**
     * Get formatted menu options for parent selection
     */
    private function getMenuOptions($parentId = null, $level = 0, $excludeId = null)
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
            $hasChildren = $menu->children()->count() > 0;
            $label = $hasChildren ? 'Parent' : '';
            $displayName = $prefix . ($label ? $label . ' - ' : '') . $menu->nama_menu;

            $options[$menu->id] = $displayName;

            $children = $this->getMenuOptions($menu->id, $level + 1, $excludeId);
            $options = $options + $children;
        }

        return $options;
    }

    /**
     * Move menu order up or down with parent-child hierarchy management
     */
    public function moveOrder(Request $request)
    {
        // Debug logging
        \Log::info('MoveOrder called', [
            'method' => $request->method(),
            'input' => $request->all(),
            'route' => $request->route()->getName()
        ]);

        $request->validate([
            'menu_id' => 'required|exists:master_menus,id',
            'direction' => 'required|in:up,down'
        ]);

        $menuId = $request->input('menu_id');
        $direction = $request->input('direction');

        try {
            $menu = MasterMenu::with('children')->findOrFail($menuId);

            // Get siblings (menus with same parent)
            $siblings = $this->getSiblings($menu->parent_id);

            // Find target menu to swap with
            $targetMenu = $this->findTargetMenu($menu, $siblings, $direction);

            if (!$targetMenu) {
                return response()->json([
                    'success' => false,
                    'message' => $direction === 'up' ? 'Menu sudah di posisi teratas' : 'Menu sudah di posisi terbawah'
                ], 400);
            }

            // Perform the swap with transaction
            DB::transaction(function () use ($menu, $targetMenu) {
                $this->swapMenuGroups($menu, $targetMenu);
                $this->reorderAllMenus();
            });

            return response()->json([
                'success' => true,
                'message' => 'Urutan menu berhasil diubah'
            ]);

        } catch (\Exception $e) {
            \Log::error('MoveOrder error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all children of a menu with their current order
     */
    private function getAllChildrenWithOrder($parentId)
    {
        return MasterMenu::where('parent_id', $parentId)
            ->orderBy('urutan', 'asc')
            ->get();
    }

    /**
     * Get siblings of a menu (menus with same parent)
     */
    private function getSiblings($parentId)
    {
        return MasterMenu::where('parent_id', $parentId)
            ->orderBy('urutan', 'asc')
            ->get();
    }

    /**
     * Find target menu to swap with based on direction
     */
    private function findTargetMenu($menu, $siblings, $direction)
    {
        $currentIndex = $siblings->search(function ($item) use ($menu) {
            return $item->id == $menu->id;
        });

        if ($currentIndex === false) {
            return null;
        }

        $targetIndex = $direction === 'up' ? $currentIndex - 1 : $currentIndex + 1;

        if ($targetIndex < 0 || $targetIndex >= $siblings->count()) {
            return null;
        }

        return $siblings[$targetIndex];
    }

    /**
     * Swap two menu groups (menu + their children) while maintaining hierarchy
     */
    private function swapMenuGroups($menu1, $menu2)
    {
        // Get children of both menus
        $menu1Children = $menu1->children()->orderBy('urutan')->get();
        $menu2Children = $menu2->children()->orderBy('urutan')->get();

        // Store original orders
        $menu1Order = $menu1->urutan;
        $menu2Order = $menu2->urutan;

        // Calculate group sizes
        $menu1GroupSize = 1 + $menu1Children->count();
        $menu2GroupSize = 1 + $menu2Children->count();

        // Determine which menu comes first
        if ($menu1Order < $menu2Order) {
            // Menu1 is moving down, Menu2 is moving up
            $this->moveMenuGroup($menu1, $menu1Children, $menu2Order);
            $this->moveMenuGroup($menu2, $menu2Children, $menu1Order);
        } else {
            // Menu1 is moving up, Menu2 is moving down  
            $this->moveMenuGroup($menu2, $menu2Children, $menu1Order);
            $this->moveMenuGroup($menu1, $menu1Children, $menu2Order);
        }
    }

    /**
     * Move a menu and its children to a new order position
     */
    private function moveMenuGroup($menu, $children, $newOrder)
    {
        // Update parent menu order
        $menu->update(['urutan' => $newOrder]);

        // Update children orders sequentially after parent
        $currentOrder = $newOrder + 1;
        foreach ($children as $child) {
            $child->update(['urutan' => $currentOrder]);
            $currentOrder++;
        }
    }

    /**
     * Reorder all menus sequentially to ensure clean ordering
     */
    private function reorderAllMenus()
    {
        // Get all root level menus first
        $rootMenus = MasterMenu::whereNull('parent_id')
            ->orderBy('urutan', 'asc')
            ->get();

        $currentOrder = 1;

        foreach ($rootMenus as $menu) {
            // Update parent menu order
            $menu->update(['urutan' => $currentOrder]);
            $currentOrder++;

            // Update all children sequentially after parent
            $children = $menu->children()->orderBy('urutan', 'asc')->get();
            foreach ($children as $child) {
                $child->update(['urutan' => $currentOrder]);
                $currentOrder++;
            }
        }
    }

    /**
     * @deprecated Use reorderAllMenus() instead
     */
    private function reorderMenusSequentially($parentId = null)
    {
        $menus = MasterMenu::where('parent_id', $parentId)
            ->orderBy('urutan', 'asc')
            ->get();

        $order = 1;
        foreach ($menus as $menu) {
            $menu->update(['urutan' => $order]);
            $order++;

            // Recursively reorder children
            $children = $this->getAllChildrenWithOrder($menu->id);
            foreach ($children as $child) {
                $child->update(['urutan' => $order]);
                $order++;
            }
        }
    }
}
