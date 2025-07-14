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
        $parentMenus = collect(menu_formatted_options());
        $roles = Role::all();
        
        return view('panel.menus.index', compact('menus', 'parentMenus', 'roles'));
    }

    /**
     * Show create menu form
     */
    public function create()
    {
        // Get hierarchical menu options for parent selection
        $parentMenus = collect(menu_formatted_options());
        $roles = Role::all();
        return view('panel.menus.create', compact('parentMenus', 'roles'));
    }

    /**
     * Store new menu
     */
    public function store(StoreMenuRequest $request)
    {
        $menu = MasterMenu::create([
            'nama_menu' => $request->nama_menu,
            'slug' => $request->slug,
            'route_name' => $request->route_name,
            'icon' => $request->icon,
            'parent_id' => $request->parent_id,
            'urutan' => $request->urutan,
            'is_active' => $request->has('is_active'),
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
        // Get all menus except current one to prevent circular reference
        $parentMenus = MasterMenu::where('id', '!=', $menu->id)->orderBy('urutan')->get();
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
            'is_active' => $request->has('is_active'),
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

        // Check if menu has children
        if ($menu->children()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete menu with child menus');
        }

        $menu->delete();

        return redirect()->route('panel.menus.index')
            ->with('success', 'Menu deleted successfully');
    }
}
