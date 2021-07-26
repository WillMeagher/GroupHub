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

Route::get('/group/created', 'App\Http\Controllers\GroupsController@created');
Route::get('/group/joined', 'App\Http\Controllers\GroupsController@joined');
Route::get('/group/search', 'App\Http\Controllers\GroupsController@search');
Route::get('/group/{group}/join', 'App\Http\Controllers\GroupsController@join');
Route::match(array('GET', 'POST'),'/group/results', 'App\Http\Controllers\GroupsController@results');
Route::resource('/group', 'App\Http\Controllers\GroupsController');

Route::resource('/permissions', 'App\Http\Controllers\PermissionsController', ['only' => ['index', 'store', 'show', 'update']]);
Route::get('/permissions/{group}/create', 'App\Http\Controllers\PermissionsController@create');
Route::get('/notifications', 'App\Http\Controllers\PermissionsController@index');

Route::resource('/account', 'App\Http\Controllers\AccountsController', ['only' => ['index', 'create', 'store', 'edit', 'update']]);
Route::get('/account/edit', 'App\Http\Controllers\AccountsController@edit');
