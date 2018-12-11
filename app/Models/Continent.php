<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Continent extends Model {
	protected $table = 't_continents';

	protected $fillable = [
		'cn_name', 'parent_id', 'en_name',
	];

	public function countries() {
		return $this->hasmany(Country::class, 'continent_id', 'id');
	}
}
