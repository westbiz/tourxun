<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model {
	protected $table = 't_countries';

	protected $fillable = [
		'continent_id', 'name', 'lower_name', 'country_code', 'full_name', 'cname', 'full_name', 'remark',
	];

	public function continent() {
		return $this->belongsTo(Continent::class, 'continent_id', 'id');
	}
}
