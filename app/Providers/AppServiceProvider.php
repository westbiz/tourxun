<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Product;
use App\Models\Picture;
use App\Models\Sight;

class AppServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {
		Resource::withoutWrapping();
		$this->bootEloquentMorphs();

	}


	private function bootEloquentMorphs(){
		Relation::morphMap([
			'sight' => Sight::class,
			'product' => Product::class,
		]);

	}



	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		//
	}
}
