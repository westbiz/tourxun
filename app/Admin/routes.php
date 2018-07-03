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

	$router->get('areas/selectone','AreaController@selectone');

	$router->resource('areas', 'AreaController');

});

//子域名路由
// Route::domain('{api}.tourxun.test')->group(function () {
// 	Route::get('categories/{id}', function ($api, $id) {
// 		return 'This is ' . $api . ' page of User ' . $id;
// 	});
// });