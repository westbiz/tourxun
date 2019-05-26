<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Worldcity extends Model {
	protected $table = 't_world_cities';

	protected $fillable = [
		'country_id', 'state', 'name', 'lower_name', 'cn_state', 'cn_name', 'city_code', 'state_code', 'active', 'is_island', 'promotion', 'capital', 'is_departure',
	];

	public function scopeChina() {
		return $this->where('country_id', 101);
	}

	// //商品多对多
	// //
	// public function products() {
	// 	return $this->belongsToMany(Product::class, 'tx_country_products', 'city_id', 'product_id');
	// }

	//海岛
	public function scopeIsland() {
		return $this->where('is_island', 1);
	}

	//开放始发地城市
	public function scopeDeparture() {
		return $this->where('active',1)->where('is_departure', 1);
	}


	//首府
	public function scopeCapital()
	{
		return $this->where('capital',1);
	}

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

	//国内城市 激活的
	public function scopeChinacities() {
		return $this->where('country_id', 101)->where('active', 1);
	}

	//世界城市
	public function scopeWorldcities() {
		return $this->where('country_id', '<>', 101)->where('active', 1);
	}

	//港澳台
	public function scopeGangaotai($query) {
		$areas = collect([71, 75, 100]);
		return $query->whereIn('country_id', $areas)->where('active', 1);
	}

}
