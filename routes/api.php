<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => 'auth'
], function($router) {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::get('profile', 'AuthController@profile');
    Route::post('refresh', 'AuthController@refresh');
});

Route::group([
    'prefix' => 'todos'
], function($router) {
    Route::get('/', 'TodoController@index')->name('todos.index');
    Route::get('/{todo}', 'TodoController@show')->name('todos.show');
    Route::post('/', 'TodoController@store')->name('todos.store');
    Route::put('/{todo}', 'TodoController@update')->name('todos.update');
    Route::delete('/{todo}', 'TodoController@destroy')->name('todos.destroy');
    Route::put('/{todo}/complete', 'TodoController@complete')->name('todos.complete');

    Route::post('/{todo}/categories/add', 'TodoController@toggleCategories')->name('todos.categories.add');

});

Route::group([
    'prefix' => 'users'
], function($router) {
    Route::get('/', 'UserController@index')->name('users.index');
    Route::get('/{user}', 'UserController@show')->name('users.show');
    Route::post('/', 'UserController@store')->name('users.store');
    Route::put('/{user}', 'UserController@update')->name('users.update');
    Route::delete('/{user}', 'UserController@destroy')->name('users.destroy');
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
