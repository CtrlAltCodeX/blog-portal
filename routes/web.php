<?php

use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\RoleController;

Illuminate\Support\Facades\Auth::routes();

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');

Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'web']], function () {
    Route::resource('roles', RoleController::class);
    
    Route::resource('users', UserController::class);
    
    Route::resource('listing', ListingController::class);
    
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('users/verified/approved', [UserController::class, 'verified'])->name('verified.users');
    
    Route::get('settings/blog', [SettingsController::class, 'blog'])->name('settings.blog');
});

Route::get('/', function () {
    return redirect()->route('dashboard');
});
