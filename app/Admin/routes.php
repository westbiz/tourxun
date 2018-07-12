<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
	'prefix' => config('admin.route.prefix'),
	'namespace' => config('admin.route.namespace'),
	'middleware' => config('admin.route.middleware'),
], function (Router $router) {

	$router->get('/', 'HomeController@index');

	$router->resource('categories', 'CategoryController');

	$router->resource('products', 'ProductController');

	$router->resource('navs', 'NavController');

	$router->get('areas/cascading', 'AreaController@cascading');

	//准备删除 $router->get('cities/{id}/edit', 'AreaController@edit');

	//准备删除  $router->get('cities/create', 'AreaController@create');

	$router->resource('cities', 'CityController');

	//准备删除  $router->get('areas/{id}/add', 'AreaController@addarea');

	$router->resource('areas', 'AreaController');

	$router->resource('pictures', 'PictureController');

	$router->resource('prices', 'PriceController');

});

//子域名路由
// Route::domain('{api}.tourxun.test')->group(function () {
// 	Route::get('categories/{id}', function ($api, $id) {
// 		return 'This is ' . $api . ' page of User ' . $id;
// 	});
// });