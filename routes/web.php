<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

// Registration Routes...
Route::get('customer/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('customer/register', 'Auth\RegisterController@register');

Route::get('admin/register', 'Auth\RegisterController@showAdminRegistrationForm')->name('register');
Route::post('admin/register', 'Auth\RegisterController@register');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
