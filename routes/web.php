<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DynamicController;

/*
|--------------------------------------------------------------------------
| Web Routes - Now Powered by Dynamic Route System
|--------------------------------------------------------------------------
|
| This file now contains only essential authentication routes and fallback
| handlers. All panel/admin routes and most application routes are now
| dynamically generated from the master_menus database table via the
| DynamicRouteServiceProvider.
|
*/

// Authentication routes (Laravel UI) - These remain static as they're core functionality
Auth::routes([
    'register' => true,
    'reset' => true,
    'verify' => false,
]);

// Home redirect after login/register - Keep this for Laravel UI compatibility
Route::get('/home', function () {
    return redirect('/');
})->name('home');

/*
|--------------------------------------------------------------------------
| Dynamic Route Fallbacks
|--------------------------------------------------------------------------
|
| These routes serve as fallbacks when dynamic routes don't match.
| The dynamic system will handle most routes, but these provide backup.
|
*/

// Welcome page - This can be handled dynamically but kept as fallback
Route::get('/', [DynamicController::class, 'handleWelcome'])->name('welcome');

// Profile route - Keep this as it's user-specific functionality
Route::middleware('auth')->group(function () {
    Route::get('/profile', [DynamicController::class, 'handleProfile'])->name('profile');
});

/*
|--------------------------------------------------------------------------
| Dynamic CMS Page Handler (Catch-All)
|--------------------------------------------------------------------------
|
| This route handles dynamic CMS pages and any routes not caught by the
| dynamic route system. It should remain at the end as a catch-all.
|
*/

Route::get('/{slug}', [DynamicController::class, 'handleDynamicPage'])
    ->where('slug', '[a-zA-Z0-9\-\/]+')
    ->name('dynamic.page');

/*
|--------------------------------------------------------------------------
| DEPRECATED STATIC ROUTES
|--------------------------------------------------------------------------
|
| The following routes are now handled by the DynamicRouteServiceProvider
| and can be removed once testing is complete:
| 
| - All /panel/* routes (handled dynamically)
| - Most authenticated routes (handled dynamically)
| - Menu-based navigation routes (handled dynamically)
|
| This transformation makes the application fully dynamic and scalable.
|
*/
