<?php

use App\Http\Controllers\AmazonSrcappingController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TinyMCEController;
use App\Services\GoogleService;

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

Route::post('/auth/google/refresh', [GoogleController::class, 'refreshGoogle'])->name('google.refresh.token');

Route::get('/process-image', [GoogleService::class, 'processImage']);

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'web']], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('roles', RoleController::class);

    Route::get('roles/all/view', [RoleController::class, 'view'])->name('view.roles');

    Route::resource('users', UserController::class);

    Route::group(['prefix' => 'find-products'], function () {
        Route::get('amazon', [AmazonSrcappingController::class, 'find'])->name('amazon.find');

        Route::post('amazon', [AmazonSrcappingController::class, 'findProducts'])->name('amazon.find.products');
    });

    Route::resource('listing', ListingController::class);

    Route::get('inventory', [ListingController::class, 'inventory'])->name('inventory.index');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('users/verified/approved', [UserController::class, 'verified'])->name('verified.users');

    Route::get('settings/blog', [SettingsController::class, 'blog'])->name('settings.blog');

    Route::post('tinymce/upload', [TinyMCEController::class, 'upload'])->name('tinymce.upload');
});

Route::get('/', function () {
    return redirect()->route('dashboard');
});
