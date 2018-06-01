<?php

use App\Models\Category;
use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});

Route::get('test', function () {
	return 'hello world';
});

Route::get('categories', function () {
	// return Category::all(['id', 'name']);
	$categories = Category::all(['id', 'name']);
	return response()->json($categories);
});

Route::get('categories/{id}', function ($id) {
	return Category::find($id);
});