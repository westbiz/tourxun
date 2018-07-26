<?php

namespace App\Providers;

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
		$this->bootEloquentMorphs();

	}

	private function bootEloquentMorphs() {
		Relation::morphMap([
			'Sight' => Sight::class,
			'Picture' => Picture::class,
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
