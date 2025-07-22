<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Requests\Panel\Permission\StorePermissionRequest;
use App\Http\Requests\Panel\Permission\UpdatePermissionRequest;

class PermissionController extends Controller
{
    /**
     * Display permissions listing
     */
    public function index()
    {
        $permissions = Permission::paginate(20);
        return view('panel.permissions.index', compact('permissions'));
    }

    /**
     * Show create permission form
     */
    public function create()
    {
        return view('panel.permissions.index.create');
    }

    /**
     * Store new permission
     */
    public function store(StorePermissionRequest $request)
    {
        Permission::create([
            'name' => $request->name,
            'group' => $request->group
        ]);

        return redirect()->route('panel.permissions')
            ->with('success', 'Permission created successfully');
    }

    /**
     * Show edit permission form
     */
    public function edit(Request $request)
    {
        $permissionId = $request->route('id') ?? $request->input('id');
        $permission = Permission::findOrFail($permissionId);

        // If this is an AJAX request, return JSON data
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'permission' => $permission
            ]);
        }

        return view('panel.permissions.index.edit', compact('permission'));
    }

    /**
     * Update permission
     */
    public function update(UpdatePermissionRequest $request)
    {
        $permissionId = $request->route('id') ?? $request->input('id');
        $permission = Permission::findOrFail($permissionId);

        $permission->update([
            'name' => $request->name,
            'group' => $request->group
        ]);

        return redirect()->route('panel.permissions')
            ->with('success', 'Permission updated successfully');
    }

    /**
     * Delete permission
     */
    public function destroy(Request $request)
    {
        $permissionId = $request->route('id') ?? $request->input('id');
        $permission = Permission::findOrFail($permissionId);

        $permission->delete();

        return redirect()->route('panel.permissions')
            ->with('success', 'Permission deleted successfully');
    }
}
