<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Customer;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisterController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// Customer Routes
Route::group(['prefix' => 'customer'], function () {
    Route::get('register',  [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register',  [RegisterController::class, 'register']);
    Route::get('dashboard',  [RegisterController::class, 'showRegistrationForm'])->name('customer-dash');
    Route::group(['middleware' => ['role:customer']], function () {
        Route::get('dashboard', [Customer\DashboardController::class, 'index']);
    });
});


// Admin Routes
Route::group(['prefix' => 'admin'], function () {
    Route::get('register',  [RegisterController::class, 'showAdminRegistrationForm']);
    Route::post('register',  [RegisterController::class, 'register'])->name('admin-register');
    Route::group(['middleware' => ['role:admin']], function () {
        Route::get('dashboard',[Admin\DashboardController::class, 'index']);
        Route::post('user/invite',[UserController::class, 'inviteNewUser']);
    });
});
Route::group(['middleware' => 'guest'], function () {
    Route::get('/signup/{token}', [UserController::class, 'signup']);
    Route::post('/signup/{token}', [UserController::class, 'completeSignUp']);
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
