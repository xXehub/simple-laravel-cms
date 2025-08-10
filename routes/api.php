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

/* Page Builder API Routes - Auth dengan permission required menggunakan web auth */
Route::middleware(['web', 'auth', 'permission:view-pages'])->prefix('builder')->name('api.builder.')->group(function () {
    Route::get('/components', [App\Http\Controllers\Panel\ComponentController::class, 'index'])->name('components');
    Route::post('/components/render', [App\Http\Controllers\Panel\ComponentController::class, 'render'])->name('render');
});

/* Test API Route */
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
