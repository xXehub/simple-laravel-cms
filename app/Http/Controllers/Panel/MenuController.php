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
        $this->middleware('permission:delete-menus')->only(['destroy']);
    }

    /**
     * Display menus listing
     */
    public function index()
    {
        $menus = MasterMenu::with('roles', 'parent', 'children')->orderBy('urutan')->paginate(20);
        // Get hierarchical menu options for parent selection
        $parentMenus = $this->getMenuOptions();
        $roles = Role::all();

        return view('panel.menus.index', compact('menus', 'parentMenus', 'roles'));
    }

    /**
     * Show create menu form
     */
    public function create()
    {
        // Get hierarchical menu options for parent selection
        $parentMenus = $this->getMenuOptions();
        $roles = Role::all();
        return view('panel.menus.create', compact('parentMenus', 'roles'));
    }

    /**
     * Store new menu
     */
    public function store(StoreMenuRequest $request)
    {
        // Debug logging
        \Log::info('Store Menu Request Data:', $request->all());
        \Log::info('Parent ID:', ['parent_id' => $request->parent_id, 'type' => gettype($request->parent_id)]);
        \Log::info('Is Active:', ['is_active' => $request->boolean('is_active'), 'raw' => $request->get('is_active')]);

        $menu = MasterMenu::create([
            'nama_menu' => $request->nama_menu,
            'slug' => $request->slug,
            'route_name' => $request->route_name,
            'icon' => $request->icon,
            'parent_id' => $request->parent_id,
            'urutan' => $request->urutan,
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($request->has('roles')) {
            $menu->roles()->sync($request->roles);
        }

        \Log::info('Menu created successfully:', ['menu_id' => $menu->id]);

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
        // Get all menus except current one to prevent circular reference
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
        
        // Debug logging
        \Log::info('Update Menu Request Data:', $request->all());
        \Log::info('Menu ID:', ['menu_id' => $menuId]);
        \Log::info('Parent ID:', ['parent_id' => $request->parent_id, 'type' => gettype($request->parent_id)]);
        \Log::info('Is Active:', ['is_active' => $request->boolean('is_active'), 'raw' => $request->get('is_active')]);
        
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

        \Log::info('Menu updated successfully:', ['menu_id' => $menu->id]);

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

        // Check if menu has children
        if ($menu->children()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete menu with child menus');
        }

        $menu->delete();

        return redirect()->route('panel.menus.index')
            ->with('success', 'Menu deleted successfully');
    }

    /**
     * Get formatted menu options for parent selection
     */
    private function getMenuOptions($parentId = null, $level = 0, $excludeId = null)
    {
        $menus = MasterMenu::where('parent_id', $parentId)
            ->orderBy('urutan')
            ->get();

        $options = [];

        foreach ($menus as $menu) {
            // Skip if this is the menu we're editing (to prevent circular reference)
            if ($excludeId && $menu->id == $excludeId) {
                continue;
            }

            $prefix = str_repeat('└─ ', $level);

            // Check if this menu has children to add "Parent" label
            $hasChildren = $menu->children()->count() > 0;
            $label = $hasChildren ? 'Parent' : '';

            // Format: "└─ Parent - Menu Name" or "└─ Menu Name"
            $displayName = $prefix . ($label ? $label . ' - ' : '') . $menu->nama_menu;

            $options[$menu->id] = $displayName;

            // Recursively get children
            $children = $this->getMenuOptions($menu->id, $level + 1, $excludeId);
            $options = array_merge($options, $children);
        }

        return $options;
    }
}
