<?php

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

// Disable default registration and password reset
Route::get('/register', function() { return abort(404); });
Route::get('/password/reset', function() { return abort(404); });

// Make user's registration available only to logged users with the Admin role
Route::group(['middleware' => ['role.admin']], function()
{
    Route::resource('/home/user', 'UserController');
});

// Logged users can view Orders
Route::group(['middleware' => ['auth']], function()
{
    Route::resource('/home/order', 'OrderController');
});

Route::get('/home', 'HomeController@index');
