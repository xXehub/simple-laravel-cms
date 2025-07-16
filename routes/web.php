<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DynamicController;
use App\Http\Controllers\Panel\DashboardController;
use App\Http\Controllers\Panel\UserController;
use App\Http\Controllers\Panel\RoleController;
use App\Http\Controllers\Panel\PermissionController;
use App\Http\Controllers\Panel\MenuController;
use App\Http\Controllers\Panel\PageController as PanelPageController;
use App\Http\Controllers\Panel\SettingController;

// Authentication routes (Laravel UI)
Auth::routes();

// Public routes
Route::get('/', [DynamicController::class, 'handleWelcome'])->name('welcome');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [DynamicController::class, 'handleProfile'])->name('profile');

    // Panel routes - Admin area with permission-based access
    Route::prefix('panel')->name('panel.')->middleware(['permission:access-panel'])->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('index')->middleware('permission:view-dashboard');
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard')->middleware('permission:view-dashboard');

        // Users Management
        Route::prefix('users')->name('users.')->middleware('permission:view-users')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/datatable', [UserController::class, 'datatable'])->name('datatable');
            Route::get('/create', [UserController::class, 'create'])->name('create')->middleware('permission:create-users');
            Route::post('/store', [UserController::class, 'store'])->name('store')->middleware('permission:create-users');
            Route::get('/edit', [UserController::class, 'edit'])->name('edit')->middleware('permission:update-users');
            Route::match(['PUT', 'POST'], '/update', [UserController::class, 'update'])->name('update')->middleware('permission:update-users');
            Route::delete('/delete', [UserController::class, 'destroy'])->name('delete')->middleware('permission:delete-users');
            Route::delete('/destroy', [UserController::class, 'destroy'])->name('destroy')->middleware('permission:delete-users');
            Route::delete('/bulk-delete', [UserController::class, 'bulkDestroy'])->name('bulk-delete')->middleware('permission:delete-users');
            
            // Avatar Management
            Route::post('/upload-avatar', [UserController::class, 'uploadAvatar'])->name('upload-avatar')->middleware('permission:update-users');
            Route::delete('/delete-avatar', [UserController::class, 'deleteAvatar'])->name('delete-avatar')->middleware('permission:update-users');
        });

        // Roles Management
        Route::prefix('roles')->name('roles.')->middleware('permission:view-roles')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::get('/create', [RoleController::class, 'create'])->name('create')->middleware('permission:create-roles');
            Route::post('/store', [RoleController::class, 'store'])->name('store')->middleware('permission:create-roles');
            Route::get('/edit', [RoleController::class, 'edit'])->name('edit')->middleware('permission:update-roles');
            Route::match(['PUT', 'POST'], '/update', [RoleController::class, 'update'])->name('update')->middleware('permission:update-roles');
            Route::delete('/delete', [RoleController::class, 'destroy'])->name('delete')->middleware('permission:delete-roles');
        });

        // Permissions Management
        Route::prefix('permissions')->name('permissions.')->middleware('permission:view-permissions')->group(function () {
            Route::get('/', [PermissionController::class, 'index'])->name('index');
            Route::get('/create', [PermissionController::class, 'create'])->name('create')->middleware('permission:create-permissions');
            Route::post('/store', [PermissionController::class, 'store'])->name('store')->middleware('permission:create-permissions');
            Route::get('/edit', [PermissionController::class, 'edit'])->name('edit')->middleware('permission:update-permissions');
            Route::match(['PUT', 'POST'], '/update', [PermissionController::class, 'update'])->name('update')->middleware('permission:update-permissions');
            Route::delete('/delete', [PermissionController::class, 'destroy'])->name('delete')->middleware('permission:delete-permissions');
        });

        // Menus Management
        Route::prefix('menus')->name('menus.')->middleware('permission:view-menus')->group(function () {
            Route::get('/', [MenuController::class, 'index'])->name('index');
            Route::get('/create', [MenuController::class, 'create'])->name('create')->middleware('permission:create-menus');
            Route::post('/store', [MenuController::class, 'store'])->name('store')->middleware('permission:create-menus');
            Route::get('/edit', [MenuController::class, 'edit'])->name('edit')->middleware('permission:update-menus');
            Route::match(['PUT', 'POST'], '/update', [MenuController::class, 'update'])->name('update')->middleware('permission:update-menus');
            Route::delete('/delete', [MenuController::class, 'destroy'])->name('delete')->middleware('permission:delete-menus');
            Route::delete('/bulk-delete', [MenuController::class, 'bulkDestroy'])->name('bulk-delete')->middleware('permission:delete-menus');
        });

        // Pages Management
        Route::prefix('pages')->name('pages.')->middleware('permission:view-pages')->group(function () {
            Route::get('/', [PanelPageController::class, 'index'])->name('index');
            Route::get('/create', [PanelPageController::class, 'create'])->name('create')->middleware('permission:create-pages');
            Route::post('/store', [PanelPageController::class, 'store'])->name('store')->middleware('permission:create-pages');
            Route::get('/edit', [PanelPageController::class, 'edit'])->name('edit')->middleware('permission:update-pages');
            Route::match(['PUT', 'POST'], '/update', [PanelPageController::class, 'update'])->name('update')->middleware('permission:update-pages');
            Route::delete('/delete', [PanelPageController::class, 'destroy'])->name('delete')->middleware('permission:delete-pages');
        });

        // Settings Management
        Route::prefix('settings')->name('settings.')->middleware('permission:view-settings')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::match(['PUT', 'POST'], '/update', [SettingController::class, 'update'])->name('update')->middleware('permission:update-settings');
        });
    });
});

// Dynamic page routes (for CMS pages) - keep this at the end as catch-all
Route::get('/{slug}', [DynamicController::class, 'handleDynamicPage'])
    ->where('slug', '[a-zA-Z0-9\-\/]+')
    ->name('dynamic.page');

// Backward compatibility route
Route::redirect('/home', '/profile')->name('home');
