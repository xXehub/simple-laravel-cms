<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MasterMenu;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Panel\User\StoreUserRequest;
use App\Http\Requests\Panel\User\UpdateUserRequest;
use App\Http\Requests\Panel\User\UpdateUserAvatarRequest;
use App\Services\AvatarService;
use App\Helpers\ResponseHelper;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:view-users')->only(['index', 'datatable']);
        $this->middleware('permission:create-users')->only(['create', 'store']);
        $this->middleware('permission:update-users')->only(['edit', 'update', 'uploadAvatar', 'deleteAvatar']);
        $this->middleware('permission:delete-users')->only(['destroy', 'bulkDestroy']);
    }

    /**
     * Display users listing with dynamic view from database
     */
    public function index(Request $request)
    {
        // Get current menu from request
        $currentSlug = $request->route()->uri ?? 'panel/users';
        $menu = MasterMenu::where('slug', $currentSlug)->first();

        // Get dynamic view path from database
        $viewPath = $menu?->getDynamicViewPath() ?? 'panel.users.index';

        $roles = Role::all();
        return view($viewPath, compact('roles', 'menu'));
    }

    /**
     * Show create user form with dynamic view
     */
    public function create(Request $request)
    {
        // Get current menu for dynamic view resolution
        $currentSlug = str_replace('/create', '', $request->route()->uri ?? 'panel/users');
        $menu = MasterMenu::where('slug', $currentSlug)->first();

        // Use create view path or fallback
        $viewPath = 'panel.users.create'; // Static for forms

        $roles = Role::all();
        return view($viewPath, compact('roles', 'menu'));
    }

    /**
     * Store new user
     */
    public function store(StoreUserRequest $request)
    {
        $userData = $request->validated();
        $userData['password'] = Hash::make($userData['password']);

        $user = User::create($userData);

        if ($request->has('roles')) {
            $user->syncRoles($request->validated()['roles'] ?? []);
        }

        return ResponseHelper::redirect('panel.users', 'User created successfully');
    }

    /**
     * Show edit user form
     */
    public function edit(Request $request)
    {
        $user = $this->getUserById($request);
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
        $user = $this->getUserById($request);

        $userData = $request->validated();

        if (isset($userData['password']) && $userData['password']) {
            $userData['password'] = Hash::make($userData['password']);
        } else {
            unset($userData['password']);
        }

        $user->update($userData);
        $user->syncRoles($request->validated()['roles'] ?? []);

        return ResponseHelper::redirect('panel.users', 'User updated successfully');
    }

    /**
     * Delete user
     */
    public function destroy(Request $request)
    {
        $user = $this->getUserById($request);

        if ($user->id === auth()->id()) {
            return ResponseHelper::error('You cannot delete your own account');
        }

        $user->delete();

        return ResponseHelper::redirect('panel.users', 'User deleted successfully');
    }

    /**
     * Bulk delete users
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $userIds = $request->input('user_ids');
        $deletedCount = 0;
        $errors = [];
        $currentUserId = auth()->id();

        foreach ($userIds as $userId) {
            $user = User::find($userId);

            if (!$user) {
                continue;
            }

            // Prevent self-deletion
            if ($user->id === $currentUserId) {
                $errors[] = "Cannot delete your own account ({$user->name})";
                continue;
            }

            $user->delete();
            $deletedCount++;
        }

        if ($deletedCount > 0) {
            $message = "Successfully deleted {$deletedCount} user(s)";
            $type = 'success';
            if (!empty($errors)) {
                $message .= ". However, some users could not be deleted: " . implode(', ', $errors);
            }
        } else {
            $message = 'No users were deleted. ' . implode(', ', $errors);
            $type = 'error';
        }

        return ResponseHelper::handle($request, 'panel.users', $message, [
            'deleted_count' => $deletedCount
        ], $type);
    }

    /**
     * Server-side datatable for users
     */
    public function datatable(Request $request)
    {
        $query = User::withRoles();

        // Add search if provided
        if ($search = $request->get('search')['value'] ?? null) {
            $query->search($search);
        }

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
                        'email' => $user->email,
                        'avatar_url' => $user->avatar_url,
                        'has_custom_avatar' => $user->hasCustomAvatar()
                    ]
                ])->render();
            })
            ->editColumn('created_at', function ($user) {
                return $user->created_at->toISOString();
            })
            ->addColumn('id', function ($user) {
                return $user->id;
            })
            ->addColumn('name', function ($user) {
                return $user->name;
            })
            ->addColumn('username', function ($user) {
                return $user->username;
            })
            ->addColumn('avatar_url', function ($user) {
                return $user->avatar_url;
            })
            ->addColumn('email', function ($user) {
                return $user->email;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    protected function getUserById($request)
    {
        return User::findOrFail($request->route('id') ?? $request->input('id'));
    }

    /**
     * Upload user avatar
     */
    public function uploadAvatar(UpdateUserAvatarRequest $request, AvatarService $avatarService)
    {
        $user = $this->getUserById($request);

        try {
            $filename = $avatarService->uploadAvatar(
                $request->file('avatar'),
                $user->avatar
            );

            $user->update(['avatar' => $filename]);
            $user->refresh();

            return ResponseHelper::handleBack($request, 'Avatar uploaded successfully', [
                'avatar_url' => $user->avatar_url
            ]);

        } catch (\Exception $e) {
            return ResponseHelper::handleBack($request, 'Failed to upload avatar: ' . $e->getMessage(), null, 'error');
        }
    }

    /**
     * Delete user avatar
     */
    public function deleteAvatar(Request $request, AvatarService $avatarService)
    {
        $user = $this->getUserById($request);

        // Check permission
        if (!auth()->user()->can('update-users') && auth()->id() != $user->id) {
            abort(403, 'Unauthorized');
        }

        try {
            if ($user->avatar) {
                $avatarService->deleteAvatar($user->avatar);
                $user->update(['avatar' => null]);
                $user->refresh();
            }

            return ResponseHelper::handleBack($request, 'Avatar deleted successfully', [
                'avatar_url' => $user->avatar_url
            ]);

        } catch (\Exception $e) {
            return ResponseHelper::handleBack($request, 'Failed to delete avatar: ' . $e->getMessage(), null, 'error');
        }
    }
}
