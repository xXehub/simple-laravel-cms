<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Panel\User\StoreUserRequest;
use App\Http\Requests\Panel\User\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * Display users listing
     */
    public function index()
    {
        $users = User::with('roles')->paginate(20);
        $roles = Role::all();
        return view('panel.users.index', compact('users', 'roles'));
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
}
