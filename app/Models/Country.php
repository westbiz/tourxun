<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model {
	protected $table = 't_countries';

	protected $fillable = [
		'continent_id', 'name', 'lower_name', 'country_code', 'full_name', 'cname', 'full_name', 'remark',
	];

	//一对多，大洲
	public function continent() {
		return $this->belongsTo(Continent::class, 'continent_id', 'id');
	}

	//多对多，大洲国家地区地理位置
	public function continentlocated() {
		return $this->belongsToMany(Continent::class, 't_country_continent', 'country_id', 'continent_id');
	}

	//一对多反向，国家
	public function worldcities() {
		return $this->hasMany(Worldcity::class, 'country_id', 'id');
	}

	//多对多，分类多国家
	public function categorycountry() {
		return $this->belongsToMany(Category::class, 't_category_countries', 'country_id', 'category_id');
	}

}
