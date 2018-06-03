<?php

use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
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

// Route::prefix('api/v1')->group(function () {
// 	Route::get('categories/{id}', function ($api, $id) {
// 		return Category::find($id);
// 	});
// });

Route::get('categories/{id}', function ($id) {
	// return Category::find($id);
	return new CategoryResource(Category::findOrFail($id));
});

Route::get('categories', function () {
	return new CategoryCollection(Category::all());
	// return $categories = Category::all()->pluck('name', 'id');
	// dd($categories);
	// $categories = Category::all(['id', 'name']);
	// // return $categories->toArray();
	// return response()->json($categories);
});
// Route::controller('categories', 'CategoryController');