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

// Make user's registration available only to logged users with the Admin role
Route::get('/register', ['middleware' => 'role.admin', function()
{
    return view('auth.register');
}]);

Route::get('/home', 'HomeController@index');
