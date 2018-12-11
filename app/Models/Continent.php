<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Continent extends Model {
	protected $table = 't_continents';

	protected $fillable = [
		'cn_name', 'parent_id', 'en_name',
	];

	//一对多，多个国家
	public function countries() {
		return $this->hasmany(Country::class, 'continent_id', 'id');
	}

	//一对多，多个子类
	public function childrencontinent()
	{
		return $this->hasMany(Continent::class, 'parent_id', 'id');
	}

	//反向
	public function parentcontinent()
	{
		return $this->belongsTo(Continent::class, 'parent_id', 'id');
	}
}
