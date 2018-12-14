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

	//一对多反向，国家
	public function worldcities() {
		return $this->hasMany(Country::class, 'country_id', 'id');
	}

	//多对多，分类多家
	public function categories() {
		return $this->belongsToMany(Category::class, 't_category_countries');
	}

}
