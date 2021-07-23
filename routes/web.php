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
Auth::routes();

Route::redirect('/', '/home');
Route::get('/home', 'App\Http\Controllers\AccountsController@index');

Route::get('/group/created', 'App\Http\Controllers\GroupsController@createdGroups');
Route::get('/group/joined', 'App\Http\Controllers\GroupsController@joinedGroups');
Route::get('/group/{group}/join', 'App\Http\Controllers\GroupsController@join');
Route::resource('/group', 'App\Http\Controllers\GroupsController');

Route::resource('/permissions', 'App\Http\Controllers\GroupPermissionsController', ['only' => ['index', 'store', 'show', 'update']]);
Route::get('/permissions/{group}/create', 'App\Http\Controllers\GroupPermissionsController@create');
Route::get('/notifications', 'App\Http\Controllers\GroupPermissionsController@notifications');

Route::resource('/account', 'App\Http\Controllers\AccountsController', ['only' => ['index', 'create', 'store', 'edit', 'update']]);
Route::get('/account/edit', 'App\Http\Controllers\AccountsController@edit');
