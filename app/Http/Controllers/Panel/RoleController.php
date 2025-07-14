<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Requests\Panel\Role\StoreRoleRequest;
use App\Http\Requests\Panel\Role\UpdateRoleRequest;

class RoleController extends Controller
{
    /**
     * Display roles listing
     */
    public function index()
    {
        $roles = Role::with('permissions')->paginate(20);
        $permissions = Permission::all();
        return view('panel.roles.index', compact('roles', 'permissions'));
    }

    /**
     * Show create role form
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('panel.roles.create', compact('permissions'));
    }

    /**
     * Store new role
     */
    public function store(StoreRoleRequest $request)
    {
        try {
            $role = Role::create(['name' => $request->name]);

            if ($request->has('permissions')) {
                $role->givePermissionTo($request->permissions);
            }

            return redirect()->route('panel.roles.index')
                ->with('success', 'Role created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating role: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show edit role form
     */
    public function edit(Request $request)
    {
        $roleId = $request->route('id') ?? $request->input('id');
        $role = Role::findOrFail($roleId);
        $permissions = Permission::all();

        // If this is an AJAX request, return JSON data
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'role' => $role->load('permissions'),
                'permissions' => $permissions
            ]);
        }

        return view('panel.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update role
     */
    public function update(UpdateRoleRequest $request)
    {
        $roleId = $request->route('id') ?? $request->input('id');
        $role = Role::findOrFail($roleId);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('panel.roles.index')
            ->with('success', 'Role updated successfully');
    }

    /**
     * Delete role
     */
    public function destroy(Request $request)
    {
        $roleId = $request->route('id') ?? $request->input('id');
        $role = Role::findOrFail($roleId);

        // Prevent deletion of super admin role
        if ($role->name === 'admin') {
            return redirect()->back()->with('error', 'Cannot delete admin role');
        }

        $role->delete();

        return redirect()->route('panel.roles.index')
            ->with('success', 'Role deleted successfully');
    }
}
