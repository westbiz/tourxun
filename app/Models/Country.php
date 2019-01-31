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
	public function continentlocation() {
		return $this->belongsToMany(Continent::class, 't_country_continent', 'country_id', 'continent_id');
	}

	//一对多反向，国家
	public function worldcities() {
		return $this->hasMany(Worldcity::class, 'country_id', 'id');
	}


	//目的地，多对一
	public function destination()
	{
		return $this->hasMany(Destination::class, 'country_id', 'id');
	}

	//多对多，分类多国家
	public function categorycountry() {
		return $this->belongsToMany(Category::class, 't_category_countries', 'country_id', 'category_id')->withPivot('line')->wherePivot('active',1);
	}


	//国内
	public function scopeChina()
	{
		return $this->where('id', 101);
	}


	//境外国家地区
	public function scopeOutofchina($query)
	{
		return $query->where('id','<>',101);
	}


	//港澳台
	public function scopeGangaotai($query)
	{
		return $query->where('id','=',100)
					->orWhere('id','=',71)
					->orWhere('id','=',75);
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
