<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| API routes untuk komponen sistem. Middleware 'api' sudah diapply otomatis
| oleh RouteServiceProvider untuk semua routes di file ini.
|
*/

/* Page Builder API Routes - Temporary tanpa auth untuk testing */
Route::prefix('builder')->name('api.builder.')->group(function () {
    Route::get('/components', [App\Http\Controllers\Panel\ComponentController::class, 'index'])->name('components');
    Route::get('/components/{id}/info', [App\Http\Controllers\Panel\ComponentController::class, 'getComponentInfo'])->name('component.info');
    Route::get('/components/search', [App\Http\Controllers\Panel\ComponentController::class, 'search'])->name('search');
    Route::get('/categories', [App\Http\Controllers\Panel\ComponentController::class, 'getCategories'])->name('categories');
    Route::post('/components/render', [App\Http\Controllers\Panel\ComponentController::class, 'render'])->name('render');
});

/* Test API Route */
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
