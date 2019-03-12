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

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/user/{id}', 'UserController@show')->middleware('auth');

Route::get('cookie/add', function () {
	$minutes = 24 * 60;
	return response('欢迎来到 Laravel 学院')->cookie('name', '学院君', $minutes);
});

Route::get('cookie/get', function (\Illuminate\Http\Request $request) {
	$cookie = $request->cookie('name');
	dd($cookie);
});

Route::group([
	'middleware' => 'auth',
], function () {
	Route::resource('user', 'UserController');
});
