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


Route::get('/home', 'App\Http\Controllers\AccountsController@show');
Route::resource('/account', 'App\Http\Controllers\AccountsController');

Route::get('/account/{user}/created', 'App\Http\Controllers\GroupsController@created');
Route::get('/account/{user}/joined', 'App\Http\Controllers\GroupsController@joined');

Route::get('/group/{group}/join', 'App\Http\Controllers\GroupsController@join');
Route::resource('/group', 'App\Http\Controllers\GroupsController');

Route::get('/', 'App\Http\Controllers\SearchController@search');
Route::get('/search', 'App\Http\Controllers\SearchController@search');
Route::match(array('GET', 'POST'), '/results', 'App\Http\Controllers\SearchController@results');

Route::get('/notifications', 'App\Http\Controllers\PermissionsController@index');
Route::get('/permissions/{group}/create', 'App\Http\Controllers\PermissionsController@create');
Route::resource('/permissions', 'App\Http\Controllers\PermissionsController', ['only' => ['index', 'store', 'show', 'update']]);

Route::fallback(function () {
    return abort(404);
});