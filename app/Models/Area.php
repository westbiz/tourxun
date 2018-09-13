<?php

namespace App\Models;

use App\Models\Sight;
use Illuminate\Database\Eloquent\Model;

class Area extends Model {
	protected $table = 't_areas';

	protected $fillable = [
		'areaCode', 'areaName', 'level', 'center', 'parent_id',
	];


	//一对多，区域有多个城市
	public function cities() {
		return $this->hasMany(Area::class, 'parent_id', 'id');
	}


	//一对一逆向，多个相对的关联
	public function province() {
		return $this->belongsTo(Area::class, 'parent_id', 'id');
	}

	
	//一对多，城市有多个景点
	public function sights() {
		return $this->hasMany(Sight::class, 'city_id', 'id');
	}

}
