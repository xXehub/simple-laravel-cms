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
    ->name('dynamic.page');
