<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
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
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function()  {
    Route::resource('users', UserController::class);
});