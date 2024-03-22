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
use App\Http\Controllers\ImageMakerController;

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

    Route::group(['prefix' => 'profile'], function () {
        Route::get('', [ProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::get('listings', [ProfileController::class, 'listings'])
            ->name('profile.listing');

        Route::post('', [ProfileController::class, 'update'])
            ->name('profile.update');
    });

    Route::group(['prefix' => 'inventory'], function () {
        Route::get('', [ListingController::class, 'inventory'])
            ->name('inventory.index');

        Route::get('review', [ListingController::class, 'reviewInventory'])
            ->name('inventory.review');

        Route::get('drafted', [ListingController::class, 'draftedInventory'])
            ->name('inventory.drafted');
    });

    Route::group(['prefix' => 'images'], function () {
        Route::get('single/create', [ImageMakerController::class, 'singleImage'])->name('image.single.create');

        Route::get('combo/create', [ImageMakerController::class, 'comboImage'])->name('image.combo.create');

        Route::get('gallery', [ImageMakerController::class, 'imageGallery'])->name('image.gallery');

        Route::get('watermark/create', [WatermarkController::class, 'create'])->name('image.watermark.create');

        Route::post('watermark/store', [WatermarkController::class, 'store'])->name('image.watermark.store');

        Route::get('collage/create', [CollageController::class, 'create'])->name('image.collage.create');

        Route::post('collage/store', [CollageController::class, 'store'])->name('image.collage.store');
    });

    Route::group(['prefix' => 'settings'], function () {
        Route::get('blog', [SettingsController::class, 'blog'])
            ->name('settings.blog');

        Route::get('site', [SettingsController::class, 'site'])
            ->name('settings.site');

        Route::post('update/site', [SettingsController::class, 'update'])
            ->name('settings.site.update');

        Route::group(['prefix' => 'keywords'], function () {
            Route::get('validate', [SettingsController::class, 'FieldsValidate'])
                ->name('settings.keywords.validate');

            Route::get('validations', [SettingsController::class, 'fieldsValidations'])
                ->name('settings.keywords.valid');

            Route::post('validations', [SettingsController::class, 'keywordsNotAllowed'])
                ->name('settings.keywords.notallowed');

            Route::get('delete/{id}', [SettingsController::class, 'keywordsDelete'])
                ->name('settings.keywords.delete');

            Route::get('update/{id}', [SettingsController::class, 'updateKeywords'])
                ->name('settings.keywords.update');
        });
    });

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

        Route::get('manually', [BackupListingsController::class, 'manuallyRunBackup'])
            ->name('manually.backup');

        Route::get('dropbox', [BackupListingsController::class, 'dropBox']);
        Route::post('dropbox/submit', [BackupListingsController::class, 'uploadfile'])->name('upload.file');
    });

    Route::group(['prefix' => 'google/products'], function () {
        Route::get('list', [GoogleController::class, 'listProducts'])
            ->name('google.products.list');
    });

    Route::resource('roles', RoleController::class);

    Route::get('roles/all/view', [RoleController::class, 'view'])
        ->name('view.roles');

    Route::get('change/password', [UserController::class, 'updatePassword'])
        ->name('change.user.password');

    Route::post('update/password', [UserController::class, 'updatePassword'])
        ->name('update.user.password');

    Route::resource('listing', ListingController::class);

    Route::resource('database-listing', DatabaseListingController::class);

    Route::get('blog/publish/{id}', [ListingController::class, 'publishBlog'])
        ->name('blog.publish');

    Route::get('dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('update/status', [DatabaseListingController::class, 'updateStatus'])->name('listing.status');

    Route::resource('users', UserController::class);

    Route::get('users/verified/approved', [UserController::class, 'verified'])
        ->name('verified.users');

    Route::get('edit/users/status/{id}', [UserController::class, 'editStatus'])
        ->name('edit.users.status');

    Route::post('update/users/status/{id}', [UserController::class, 'updateStatus'])
        ->name('update.users.status');

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

    Route::get('export', [BackupListingsController::class, 'export'])
        ->name('backup.export');
});

Route::group(['prefix' => 'password'], function () {
    Route::get('reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');

    Route::post('email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');

    Route::post('reset', [ResetPasswordController::class, 'reset']);

    Route::post('update', [ResetPasswordController::class, 'updatePassword'])->name('password.update');
});


Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/check-session-status', function () {
    session()->flash('error', 'Your session has expired. Please log in again.');

    return response()->json(['active' => auth()->check()]);
})->name('check.session');
