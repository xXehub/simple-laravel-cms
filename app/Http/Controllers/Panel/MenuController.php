<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\MasterMenu;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Http\Requests\Panel\Menu\StoreMenuRequest;
use App\Http\Requests\Panel\Menu\UpdateMenuRequest;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:view-menus')->only(['index']);
        $this->middleware('permission:create-menus')->only(['create', 'store']);
        $this->middleware('permission:update-menus')->only(['edit', 'update']);
        $this->middleware('permission:delete-menus')->only(['destroy', 'bulkDestroy']);
    }

    /**
     * Display menus listing
     */
    public function index(Request $request)
    {
        $parentMenus = $this->getMenuOptions();
        $roles = Role::all();

        if ($request->ajax() && $request->has('draw')) {
            return $this->getDataTablesData($request);
        }

        if ($request->ajax()) {
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
        foreach ($menus as $menu) {
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
                'actions' => view('components.modals.menus.action', ['menu' => $menu->toArray()])->render()
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
        $parentMenus = $this->getMenuOptions();
        $roles = Role::all();
        return view('panel.menus.create', compact('parentMenus', 'roles'));
    }

    /**
     * Store new menu
     */
    public function store(StoreMenuRequest $request)
    {
        $menu = MasterMenu::create([
            'nama_menu' => $request->input('nama_menu'),
            'slug' => $request->input('slug'),
            'route_name' => $request->input('route_name'),
            'icon' => $request->input('icon'),
            'parent_id' => $request->input('parent_id'),
            'urutan' => $request->input('urutan'),
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($request->has('roles')) {
            $menu->roles()->sync($request->roles);
        }

        return redirect()->route('panel.menus.index')
            ->with('success', 'Menu created successfully');
    }

    /**
     * Show edit menu form
     */
    public function edit(Request $request)
    {
        $menuId = $request->route('id') ?? $request->input('id');

        if (!$menuId) {
            return redirect()->route('panel.menus.index')
                ->with('error', 'Menu ID not provided');
        }

        $menu = MasterMenu::findOrFail($menuId);
        $parentMenus = $this->getMenuOptions(null, 0, $menu->id);
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

        $menu->update([
            'nama_menu' => $request->nama_menu,
            'slug' => $request->slug,
            'route_name' => $request->route_name,
            'icon' => $request->icon,
            'parent_id' => $request->parent_id,
            'urutan' => $request->urutan,
            'is_active' => $request->boolean('is_active'),
        ]);

        $menu->roles()->sync($request->roles ?? []);

        return redirect()->route('panel.menus.index')
            ->with('success', 'Menu updated successfully');
    }

    /**
     * Delete menu
     */
    public function destroy(Request $request)
    {
        $menuId = $request->route('id') ?? $request->input('id');
        $menu = MasterMenu::findOrFail($menuId);

        if ($menu->children()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete menu with child menus');
        }

        $menu->delete();

        return redirect()->route('panel.menus.index')
            ->with('success', 'Menu deleted successfully');
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

        $menuIds = $request->menu_ids;
        $deletedCount = 0;
        $errors = [];

        foreach ($menuIds as $menuId) {
            $menu = MasterMenu::find($menuId);

            if (!$menu) {
                continue;
            }

            if ($menu->children()->count() > 0) {
                $errors[] = "Cannot delete '{$menu->nama_menu}' - has child menus";
                continue;
            }

            $menu->delete();
            $deletedCount++;
        }

        if ($deletedCount > 0) {
            $message = "Successfully deleted {$deletedCount} menu(s)";
            $status = 'success';
            if (!empty($errors)) {
                $message .= ". However, some menus could not be deleted: " . implode(', ', $errors);
            }
        } else {
            $message = 'No menus were deleted. ' . implode(', ', $errors);
            $status = 'error';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'deleted_count' => $deletedCount
            ]);
        }

        return redirect()->route('panel.menus.index')->with($status, $message);
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
}
