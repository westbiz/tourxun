<?php

use App\Http\Resources\CategoryCollection;
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

Route::get('categories/all', 'CategoryController@index')->name('all');

Route::get('categories/list', 'CategoryController@list')->name('list');

Route::get('categories/groups', 'CategoryController@groups')->name('groups');

// Route::get('categories/{id}', function ($id) {
// 	// return Category::find($id);
// 	return new CategoryResource(Category::find($id));
// });

// Route::get('categories/{id}', 'Admin\CategoryController@children');

// Route::get('categories/{id}', 'CategoryController@children');

// Route::get('categories', function () {
// 	return CategoryResource::collection(Category::paginate());
// 	// return $categories = Category::all(['id', 'name as text']);
// 	// return $categories = Category::all()->pluck('name', 'id');
// 	// dd($categories);
// 	// $categories = Category::all(['id', 'name']);
// 	// // return $categories->toArray();
// 	// return response()->json($categories);
// });

// Route::get('categories/children/{id}', function () {
// 	return new CategoryResource(Category::find($id));
// });

Route::get('/categories', function () {
	return new CategoryCollection(Category::find(1));
});
Route::get('categories/ajax', 'CategoryController@categoryajax')->name('ajax');
Route::get('categories/children', 'CategoryController@children')->name('children');

Route::get('categories/{category}', 'CategoryController@show')->name('show');

Route::put('categories/{category}', 'CategoryController@update');

Route::get('area/city', 'AreaController@city')->name('city');

Route::get('area/district', 'AreaController@district')->name('district');

Route::get('area/province', 'AreaController@province')->name('province');

Route::get('worldcities/all', 'WorldcityController@allcities')->name('allcities');

Route::get('chinacities/ajax', 'WorldcityController@chinacities')->name('chinacities');

Route::get('worldcities/ajax', 'WorldcityController@worldcities')->name('worldcities');

Route::get('countries/ajax', 'CountryController@countryajax')->name('ajax');
