<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Worldcity extends Model {
	protected $table = 't_world_cities';

	protected $fillable = [
		'country_id', 'state', 'name', 'lower_name', 'cn_state', 'cn_name', 'city_code', 'state_code',
	];

	//一对多反向，国家
	public function country() {
		return $this->belongsTo(Country::class, 'country_id', 'id');
	}



	//联动
	public function parent() {
		return $this->belongsTo(Country::class, 'country_id', 'id');
	}

	public function children() {
		return $this->hasMany(Worldcity::class, 'country_id', 'id');
	}

	public function brothers() {
		return $this->parent->children();
	}

	//
	public static function options($id) {
		if (!$self = static::find($id)) {
			return [];
		}
		return $self->brothers()->pluck('cn_name', 'id');
	}








	//
	public function scopeChinacities() {
		return $this->where('country_id', 101);
	}

	public function scopeWorldcities() {
		return $this->where('country_id', '<>', 101);
	}

	public function scopeGangaotai() {
		return $this->where('country_id', 75)
			->orWhere('country_id', 71)
			->orWhere('country_id', 100);
	}

}
