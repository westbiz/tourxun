<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Worldcity extends Model {
	protected $table = 't_world_cities';

	protected $fillable = [
		'country_id', 'state', 'name', 'lower_name', 'cn_state', 'cn_city', 'city_code', 'state_code',
	];

	//一对多反向，国家
	public function country() {
		return $this->belongsTo(Country::class, 'country_id', 'id');
	}

	//

}
