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

	$router->get('area/cascading', 'AreaController@cascading');

	//准备删除 $router->get('cities/{id}/edit', 'AreaController@edit');

	//准备删除  $router->get('cities/create', 'AreaController@create');

	// $router->get('city', 'CityController@index');
	// $router->get('city/sight/create', 'SightController@create');
	// $router->get('city/sight/{sight}', 'SightController@show');
	// $router->get('city/sight/{sight}/edit', 'SightController@edit');
	$router->get('city/{city}/addcity', 'CityController@addcity');
	// $router->get('city/{city}/addchildsight', 'SightController@addchildsight');
	$router->get('city/{city}/cityaddsight', 'SightController@cityaddsight');
	$router->post('city/{city}', 'CityController@store');
	// $router->get('city/{city}/edit', 'CityController@edit');

	$router->resource('city', 'CityController');

	//准备删除  $router->get('areas/{id}/add', 'AreaController@addarea');

	$router->resource('area', 'AreaController');

	$router->resource('pictures', 'PictureController');

	$router->resource('prices', 'PriceController');

	// $router->get('sight/city/{city}/addsight', 'SightController@addsight');
	// $router->get('sight/create', 'SightController@addsight');
	// $router->post('sight/city/{city}', 'SightController@store');
	$router->get('city/{city}/sight/{sight}', 'SightController@show');
	// $router->get('city/{city}/sight/{sight}/edit', 'SightController@edit');
	// $router->get('sight/{sight}/addChildSight', 'SightController@addchildsight');
	$router->get('sights/createsight', 'SightController@createsight');
	$router->resource('sights', 'SightController');

	$router->resource('sighttypes', 'SighttypeController');

	$router->resource('pictures', 'PictureController');

	$router->resource('picturetype', 'PicturetypeController');

	$router->resource('comments', 'CommentController');

	$router->resource('catattrs','CatattrController');

});

//子域名路由
// Route::domain('{api}.tourxun.test')->group(function () {
// 	Route::get('categories/{id}', function ($api, $id) {
// 		return 'This is ' . $api . ' page of User ' . $id;
// 	});
// });