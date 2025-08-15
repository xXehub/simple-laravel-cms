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
        $this->middleware('permission:view-users')->only(['index', 'datatable', 'show']);
        $this->middleware('permission:create-users')->only(['create', 'store']);
        $this->middleware('permission:update-users')->only(['edit', 'update', 'uploadAvatar', 'deleteAvatar']);
        $this->middleware('permission:delete-users')->only(['destroy', 'restore', 'forceDestroy', 'bulkDestroy']);
    }

    /**
     * Display users listing with dynamic view from database
     */
    public function index(Request $request)
    {
        // Handle DataTable AJAX requests
        if ($request->ajax() && $request->has('draw')) {
            return $this->datatable($request);
        }

        // Get current menu from request
        $currentSlug = $request->route()->uri ?? 'panel/users';
        $menu = MasterMenu::where('slug', $currentSlug)->first();

        // Get dynamic view path from database
        $viewPath = $menu?->getDynamicViewPath() ?? 'panel.users.index';

        $roles = Role::all();
        
        // Count soft deleted users
        $trashedCount = User::onlyTrashed()->count();
        
        return view($viewPath, compact('roles', 'menu', 'trashedCount'));
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

        return ResponseHelper::redirect('panel.users', 'User berhasil dibuat!');
    }

    /**
     * Show user details
     */
    public function show(Request $request)
    {
        $user = $this->getUserById($request);

        // Get current menu from request
        $currentSlug = str_replace('/show', '', $request->route()->uri ?? 'panel/users');
        $menu = MasterMenu::where('slug', $currentSlug)->first();

        // Get dynamic view path from database
        $viewPath = $menu?->getDynamicViewPath() ?? 'panel.users.show';

        return view($viewPath, compact('user', 'menu'));
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

        return ResponseHelper::redirect('panel.users', 'User berhasil diperbarui!');
    }

    /**
     * Delete user (soft delete)
     */
    public function destroy(Request $request)
    {
        $user = $this->getUserById($request);

        if ($user->id === auth()->id()) {
            return ResponseHelper::error('Tidak dapat menghapus akun sendiri');
        }

        $user->update(['deleted_by' => auth()->id()]);
        $user->delete(); // This will soft delete

        return ResponseHelper::redirect('panel.users', 'User berhasil dihapus!');
    }

    /**
     * Restore soft deleted user
     */
    public function restore(Request $request)
    {
        $user = User::withTrashed()->findOrFail($request->route('id') ?? $request->input('id'));
        $user->restore();

        return ResponseHelper::redirect('panel.users', 'User berhasil dipulihkan!');
    }

    /**
     * Permanently delete user
     */
    public function forceDestroy(Request $request)
    {
        $user = User::withTrashed()->findOrFail($request->route('id') ?? $request->input('id'));

        if ($user->id === auth()->id()) {
            return ResponseHelper::error('Tidak dapat menghapus akun sendiri secara permanen');
        }

        $user->forceDelete(); // Permanent delete

        return ResponseHelper::redirect('panel.users', 'User berhasil dihapus secara permanen!');
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
            $user = User::withTrashed()->find($userId);

            if (!$user) {
                continue;
            }

            // Prevent self-deletion
            if ($user->id === $currentUserId) {
                $errors[] = "Cannot delete your own account ({$user->name})";
                continue;
            }

            // Check if user is already trashed (soft deleted)
            if ($user->trashed()) {
                // Force delete (permanent delete for trashed users)
                $user->forceDelete();
            } else {
                // Soft delete for active users
                $user->update(['deleted_by' => $currentUserId]);
                $user->delete();
            }
            $deletedCount++;
        }

        if ($deletedCount > 0) {
            $message = "Berhasil menghapus {$deletedCount} user(s)";
            $type = 'success';
            if (!empty($errors)) {
                $message .= ". Namun, beberapa pengguna tidak dapat dihapus: " . implode(', ', $errors);
            }
        } else {
            $message = 'Tidak ada pengguna yang dihapus. ' . implode(', ', $errors);
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
        $query = User::with(['roles', 'deletedBy']);

        // Handle trashed filter
        $showTrashed = $request->get('show_trashed', false);
        if ($showTrashed === 'true' || $showTrashed === '1') {
            $query = User::onlyTrashed()->with(['roles', 'deletedBy']);
        } elseif ($showTrashed === 'with') {
            $query = User::withTrashed()->with(['roles', 'deletedBy']);
        }

        // Add search if provided  
        if ($search = $request->get('search')['value'] ?? null) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('username', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        return DataTables::of($query)
            ->addColumn('checkbox', function ($user) {
                return '<input class="form-check-input m-0 align-middle table-selectable-check" type="checkbox" aria-label="Select user" value="' . $user->id . '"/>';
            })
            ->addColumn('roles', function ($user) {
                return $user->roles->pluck('name')->toArray();
            })
            ->addColumn('status', function ($user) {
                if ($user->trashed()) {
                    return '<span class="badge bg-danger">Deleted</span>';
                }
                return '<span class="badge bg-success">Active</span>';
            })
            ->addColumn('action', function ($user) {
                // Render action buttons using the component
                return view('components.modals.users.action', [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'avatar_url' => $user->avatar_url,
                        'has_custom_avatar' => $user->hasCustomAvatar(),
                        'is_trashed' => $user->trashed()
                    ]
                ])->render();
            })
            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('Y-m-d H:i:s');
            })
            ->editColumn('deleted_at', function ($user) {
                return $user->deleted_at ? $user->deleted_at->format('Y-m-d H:i:s') : null;
            })
            ->addColumn('deleted_by', function ($user) {
                return $user->deletedBy ? $user->deletedBy->name : null;
            })
            ->rawColumns(['checkbox', 'action', 'status'])
            ->make(true);
    }

    protected function getUserById($request)
    {
        $userId = $request->route('id') ?? $request->input('id');
        return User::withTrashed()->findOrFail($userId);
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

            return ResponseHelper::handleBack($request, 'Avatar berhasil diunggah!', [
                'avatar_url' => $user->avatar_url
            ]);

        } catch (\Exception $e) {
            return ResponseHelper::handleBack($request, 'Gagal mengunggah avatar: ' . $e->getMessage(), null, 'error');
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

            return ResponseHelper::handleBack($request, 'Avatar berhasil dihapus!', [
                'avatar_url' => $user->avatar_url
            ]);

        } catch (\Exception $e) {
            return ResponseHelper::handleBack($request, 'Gagal menghapus avatar: ' . $e->getMessage(), null, 'error');
        }
    }
}
