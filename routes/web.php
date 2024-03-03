<?php

use App\Http\Controllers\WatermarkController;
use App\Http\Controllers\CollageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\RoleController;
use App\Services\GoogleService;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BackupListingsController;
use App\Http\Controllers\DatabaseListingController;

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

Route::post('/auth/google', [GoogleController::class, 'redirectToGoogle'])
    ->name('auth.google');

Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::post('/auth/google/refresh', [GoogleController::class, 'refreshGoogle'])
    ->name('google.refresh.token');

Route::match(['get', 'post'], '/verify/otp', [LoginController::class, 'authenticateOTP'])->name('verify.otp');

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'web']], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::post('profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::resource('roles', RoleController::class);

    Route::get('roles/all/view', [RoleController::class, 'view'])
        ->name('view.roles');

    Route::get('change/password', [UserController::class, 'updatePassword'])
        ->name('change.user.password');

    Route::post('update/password', [UserController::class, 'updatePassword'])
        ->name('update.user.password');

    Route::resource('listing', ListingController::class);

    Route::resource('database-listing', DatabaseListingController::class);

    Route::group(['prefix' => 'inventory'], function () {
        Route::get('', [ListingController::class, 'inventory'])
            ->name('inventory.index');

        Route::get('review', [ListingController::class, 'reviewInventory'])
            ->name('inventory.review');

        Route::get('drafted', [ListingController::class, 'draftedInventory'])
            ->name('inventory.drafted');
    });

    Route::get('blog/publish/{id}', [ListingController::class, 'publishBlog'])
        ->name('blog.publish');

    Route::get('dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::group(['prefix' => 'images'], function() {
        Route::get('watermark/create', [WatermarkController::class, 'create'])->name('image.watermark.create');

        Route::post('watermark/store', [WatermarkController::class, 'store'])->name('image.watermark.store');

        Route::get('collage/create', [CollageController::class, 'create'])->name('image.collage.create');

        Route::post('collage/store', [CollageController::class, 'store'])->name('image.collage.store');
    });

    Route::resource('users', UserController::class);

    Route::get('users/verified/approved', [UserController::class, 'verified'])
        ->name('verified.users');

    Route::get('edit/users/status/{id}', [UserController::class, 'editStatus'])
        ->name('edit.users.status');

    Route::post('update/users/status/{id}', [UserController::class, 'updateStatus'])
        ->name('update.users.status');

    Route::group(['prefix' => 'settings'], function () {
        Route::get('blog', [SettingsController::class, 'blog'])
            ->name('settings.blog');

        Route::get('site', [SettingsController::class, 'site'])
            ->name('settings.site');

        Route::post('update/site', [SettingsController::class, 'update'])
            ->name('settings.site.update');
    });

    Route::match(['get', 'post'], 'process/image', [GoogleService::class, 'processImageAndDownload'])
        ->name('process.image');

    Route::post('convert/image', [GoogleService::class, 'downloadProcessedImage'])
        ->name('convert.image');

    Route::post('drafted/posts', [GoogleService::class, 'draftedInventory'])
        ->name('drafted.posts');

    Route::post('live/posts', [GoogleService::class, 'posts'])
        ->name('live.posts');

    Route::get('posts', [DashboardController::class, 'getStats'])
        ->name('get.posts.count');

    Route::get('set/session/id', [UserController::class, 'setSessionId'])
        ->name('user.session.id');

    Route::group(['prefix' => 'backup'], function () {
        Route::get('listings', [BackupListingsController::class, 'backupListings'])
            ->name('backup.listings');

        Route::get('logs', [BackupListingsController::class, 'getLoggerFile'])
            ->name('backup.logs');

        Route::get('emails', [BackupListingsController::class, 'backupEmail'])
            ->name('settings.emails');

        Route::post('emails', [BackupListingsController::class, 'saveEmail'])
            ->name('settings.emails.save');

        Route::get('delete/emails/{id}', [BackupListingsController::class, 'deleteEmail'])
            ->name('backup.emails.delete');
    });

    Route::get('export', [BackupListingsController::class, 'downloadExcel'])
        ->name('backup.export');
});

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');

Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');

Route::post('password/reset', [ResetPasswordController::class, 'reset']);

Route::post('update/password', [ResetPasswordController::class, 'updatePassword'])->name('password.update');

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/check-session-status', function () {
    return response()->json(['active' => auth()->check()]);
})->name('check.session');
