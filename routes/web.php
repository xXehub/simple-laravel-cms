<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DynamicController;

/*
|--------------------------------------------------------------------------
| Route Web - [Dinamis]
|--------------------------------------------------------------------------
|
| Cuma berisi route penting dan fallback aja.
| Route panel admin dan route lainnya diatur di database 
| Dibuat otomatis dari tabel master_menus lewat DynamicRouteServiceProvider.
|
*/

/* Route autentikasi (Laravel UI) - Ini tetap statis karena fitur inti */
Auth::routes([
    'register' => true,
    'reset' => true,
    'verify' => false,
]);

/* Route maintenance page */
Route::get('/maintenance', function () {
    return view('maintenance');
})->name('maintenance');

/* Route untuk Visit Tracking Management */
Route::middleware(['auth', 'permission:access-panel'])->prefix('panel')->name('panel.')->group(function () {
    Route::prefix('visit-tracking')->name('visit-tracking.')->group(function () {
        Route::get('/', [App\Http\Controllers\Panel\VisitTrackingController::class, 'index'])->name('index');
        Route::post('/update-retention', [App\Http\Controllers\Panel\VisitTrackingController::class, 'updateRetention'])->name('update-retention');
        Route::post('/manual-reset', [App\Http\Controllers\Panel\VisitTrackingController::class, 'manualReset'])->name('manual-reset');
        Route::post('/force-sync', [App\Http\Controllers\Panel\VisitTrackingController::class, 'forceSync'])->name('force-sync');
        Route::post('/auto-cleanup', [App\Http\Controllers\Panel\VisitTrackingController::class, 'autoCleanup'])->name('auto-cleanup');
        Route::post('/reset-cache', [App\Http\Controllers\Panel\VisitTrackingController::class, 'resetCache'])->name('reset-cache');
        Route::get('/export', [App\Http\Controllers\Panel\VisitTrackingController::class, 'exportData'])->name('export');
    });
});

/* Redirect ke home setelah login/register - Biar Laravel UI tetap jalan */
Route::get('/home', function () {
    return redirect('/');
})->name('home');

/* Halaman welcome - Sebenarnya bisa dinamis, tapi ini buat backup */
Route::get('/', [DynamicController::class, 'handleWelcome'])->name('landing');

// Profile route - Keep this as it's user-specific functionality
Route::middleware('auth')->group(function () {
    Route::get('/profile', [DynamicController::class, 'handleProfile'])->name('profile');
});

/*
|--------------------------------------------------------------------------
| Handler CMS Dinamis (Catch-All)
|--------------------------------------------------------------------------
|
| Route ini buat handle halaman CMS dinamis & route yang nggak ketangkep
| sistem dinamis (wajib ditaroh paling bawah)*
|
*/

Route::get('/{slug}', [DynamicController::class, 'handleDynamicPage'])
    ->where('slug', '[a-zA-Z0-9\-\/]+')
    ->middleware('track_visit')
    ->name('dynamic.page');
