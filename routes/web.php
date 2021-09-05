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
Route::pattern('user', '^[a-zA-Z0-9.-_]*$');
Route::pattern('group', '^[a-zA-Z0-9.-_]*$');

Auth::routes([
    'login' => false,
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
  ]);

Route::get('/cas/login', 'App\Http\Controllers\CasController@login');
Route::get('/login', 'App\Http\Controllers\CasController@login');
Route::get('/register', 'App\Http\Controllers\CasController@login');

// these should remain above the resource to prevent issues with account names
Route::get('/home', 'App\Http\Controllers\AccountsController@show');
Route::get('/account/{user}/view', 'App\Http\Controllers\AccountsController@show');
Route::get('/account/{user}/created', 'App\Http\Controllers\GroupsController@created');
Route::get('/account/{user}/joined', 'App\Http\Controllers\GroupsController@joined');
Route::resource('/account', 'App\Http\Controllers\AccountsController', ['except' => ['index', 'show']]);

// these should remain above the resource to prevent issues with group names
Route::get('/group/{group}/join', 'App\Http\Controllers\GroupsController@join');
Route::get('/group/{group}/view', 'App\Http\Controllers\GroupsController@show');
Route::resource('/group', 'App\Http\Controllers\GroupsController', ['except' => ['index', 'show']]);

Route::get('/', 'App\Http\Controllers\SearchController@search');
Route::get('/search', 'App\Http\Controllers\SearchController@search');
Route::match(['GET', 'POST'], '/results', 'App\Http\Controllers\SearchController@results');

Route::get('/notifications', 'App\Http\Controllers\PermissionsController@index');
Route::get('/permissions/{group}/create', 'App\Http\Controllers\PermissionsController@create');
Route::resource('/permissions', 'App\Http\Controllers\PermissionsController', ['only' => ['store', 'show', 'update']]);

Route::get('/contact', 'App\Http\Controllers\ContactController@create');
Route::match('POST', '/contact/create', 'App\Http\Controllers\ContactController@store');

Route::fallback(function () {
    return abort(404);
});