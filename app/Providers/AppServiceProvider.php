<?php

namespace App\Providers;

use App\Models\Picture;
use App\Models\Product;
use App\Models\Sight;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {
		Resource::withoutWrapping();
		// $this->bootEloquentMorphs();
		Relation::morphMap([
			'Sight' => Sight::class,
			'Product' => Product::class,
			'Picture' => Picture::class,
		]);

	}

	// private function bootEloquentMorphs() {
	// 	Relation::morphMap([
	// 		'Sight' => Sight::class,
	// 		'Product' => Product::class,
	// 	]);

	// }

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		//
	}
}
