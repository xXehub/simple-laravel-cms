<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Panel\User\StoreUserRequest;
use App\Http\Requests\Panel\User\UpdateUserRequest;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display users listing
     */
    public function index()
    {
        $roles = Role::all();
        return view('panel.users.index', compact('roles'));
    }

    /**
     * Show create user form
     */
    public function create()
    {
        $roles = Role::all();
        return view('panel.users.create', compact('roles'));
    }

    /**
     * Store new user
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        return redirect()->route('panel.users.index')
            ->with('success', 'User created successfully');
    }

    /**
     * Show edit user form
     */
    public function edit(Request $request)
    {
        $userId = $request->route('id') ?? $request->input('id');
        $user = User::findOrFail($userId);
        $roles = Role::all();

        // If this is an AJAX request, return JSON data
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'user' => $user->load('roles'),
                'roles' => $roles
            ]);
        }

        return view('panel.users.edit', compact('user', 'roles'));
    }

    /**
     * Update user
     */
    public function update(UpdateUserRequest $request)
    {
        $userId = $request->route('id') ?? $request->input('id');
        $user = User::findOrFail($userId);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles($request->roles ?? []);

        return redirect()->route('panel.users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Delete user
     */
    public function destroy(Request $request)
    {
        $userId = $request->route('id') ?? $request->input('id');
        $user = User::findOrFail($userId);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account');
        }

        $user->delete();

        return redirect()->route('panel.users.index')
            ->with('success', 'User deleted successfully');
    }

    /**
     * Server-side datatable for users
     */
    public function datatable(Request $request)
    {
        $query = User::with('roles');
        return DataTables::of($query)
            ->addColumn('roles', function ($user) {
                return $user->roles->pluck('name')->toArray();
            })
            ->addColumn('action', function ($user) {
                // Render action buttons using the component
                return view('components.modals.users.action', [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email
                    ]
                ])->render();
            })
            ->editColumn('created_at', function ($user) {
                return $user->created_at->toISOString();
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
