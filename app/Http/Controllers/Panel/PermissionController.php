<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Requests\Panel\Permission\StorePermissionRequest;
use App\Http\Requests\Panel\Permission\UpdatePermissionRequest;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    /**
     * Display permissions listing
     */
    public function index()
    {
        return view('panel.permissions.index');
    }

    /**
     * Server-side datatable for permissions
     */
    public function datatable(Request $request)
    {
        $query = Permission::query();

        // Add search if provided
        if ($search = $request->get('search')['value'] ?? null) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('group', 'LIKE', "%{$search}%");
            });
        }

        return DataTables::of($query)
            ->addColumn('checkbox', function ($permission) {
                return '<input class="form-check-input m-0 align-middle table-selectable-check" type="checkbox" aria-label="Select permission" value="' . $permission->id . '"/>';
            })
            ->addColumn('DT_RowIndex', function ($permission) {
                return '';
            })
            ->addColumn('action', function ($permission) {
                // Render action buttons using the component
                return view('components.modals.permissions.action', [
                    'permission' => [
                        'id' => $permission->id,
                        'name' => $permission->name,
                    ]
                ])->render();
            })
            ->editColumn('created_at', function ($permission) {
                return $permission->created_at->toISOString();
            })
            ->addColumn('group_badge', function ($permission) {
                if ($permission->group) {
                    return '<span class="badge bg-primary-lt me-1">' . $permission->group . '</span>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->rawColumns(['checkbox', 'action', 'group_badge'])
            ->make(true);
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
        \Log::info('PermissionController@destroy called', [
            'route_id' => $request->route('id'),
            'input_id' => $request->input('id'),
            'method' => $request->method(),
            'url' => $request->url()
        ]);
        
        $permissionId = $request->route('id') ?? $request->input('id');
        $permission = Permission::findOrFail($permissionId);

        $permission->delete();

        return redirect()->route('panel.permissions')
            ->with('success', 'Permission deleted successfully');
    }

    /**
     * Bulk delete permissions
     */
    public function bulkDestroy(Request $request)
    {
        \Log::info('PermissionController@bulkDestroy called', [
            'method' => $request->method(),
            'url' => $request->url(),
            'input' => $request->all()
        ]);
        
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:permissions,id'
        ]);

        $deletedCount = Permission::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => "Successfully deleted {$deletedCount} permission(s)",
            'deleted_count' => $deletedCount
        ]);
    }

    /**
     * Get permissions for DataTables
     */
    public function getPermissions(Request $request)
    {
        if ($request->ajax()) {
            $permissions = Permission::query();

            return DataTables::of($permissions)
                ->addColumn('action', function ($permission) {
                    return view('panel.permissions.index.partials.actions', compact('permission'));
                })
                ->make(true);
        }

        return abort(404);
    }
}
