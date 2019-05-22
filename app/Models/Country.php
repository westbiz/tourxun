<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Country extends Model {
	protected $table = 't_countries';

	protected $fillable = [
		'continent_id', 'name', 'lower_name', 'country_code', 'full_name', 'cname', 'full_name', 'remark', 'active', 'is_island',
	];

	//海岛
	public function scopeIsland() {
		return $this->where('is_island', 1);
	}

	//一对多，大洲
	public function continent() {
		return $this->belongsTo(Continent::class, 'continent_id', 'id');
	}

	//多对多，大洲国家地区地理位置
	public function continentlocation() {
		return $this->belongsToMany(Continent::class, 't_country_continent', 'country_id', 'continent_id');
	}

	//一对多反向，国家
	public function cities() {
		return $this->hasMany(Worldcity::class, 'country_id', 'id');
	}

	//目的地，多对一
	public function destination() {
		return $this->hasMany(Destination::class, 'country_id', 'id');
	}

	//商品多对多
	//
	public function products() {
		return $this->belongsToMany(Product::class);
	}

	//多对多，分类多国家
	public function categorycountry() {
		return $this->belongsToMany(Category::class, 't_category_countries', 'country_id', 'category_id')->withPivot('line')->wherePivot('active', 1);
	}

	//国内
	public function scopeChina() {
		return $this->where('id', 101)->where('active', 1);
	}

	//境外国家地区
	public function scopeAbroad($query) {
		//100台湾, 101中国, 71澳门, 75香港
		$areas = collect([71, 75, 100, 101]);
		return $query->whereNotIn('id', $areas)->where('active', 1);
	}

	//港澳台
	public function scopeGangaotai($query) {
		$areas = collect([71, 75, 100]);
		return $query->whereIn('id', $areas)->where('active', 1);
	}

	//联动
	public function parent() {
		return $this->belongsTo(Country::class, 'country_id');
	}

	public function children() {
		return $this->hasMany(Worldcity::class, 'country_id');
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

}
