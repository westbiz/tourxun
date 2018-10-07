<?php

namespace App\Models;

use App\Models\Sight;
use Illuminate\Database\Eloquent\Model;

class Area extends Model {
	protected $table = 't_areas';

	protected $fillable = [
		'areaCode', 'areaName', 'level', 'center', 'parent_id',
	];

	public function scopeShengqu() {
		return $this->where('level',1);
	}

	public function scopeChengshi(){
		return $this->where('level',2);
	}

	public function scopeQuxian() {
		return $this->where('level',3);
	}


	public function parent() {
		return $this->belongsTo(Area::class, 'parent_id');
	}

	public function children() {
		return $this->hasMany(Area::class, 'parent_id');
	}

	public function brothers() {
		return $this->parent->children();
	}

	public static function options($id) {
		if (! $self = static::find($id)) {
			return [];
		}
		return $self->brothers()->pluck('areaName','id');
	}



	//一对多，区域有多个城市
	public function cities() {
		return $this->hasMany(Area::class, 'parent_id', 'id');
	}


	//一对一逆向，多个相对的关联
	public function province() {
		return $this->belongsTo(Area::class, 'parent_id', 'id');
	}

	
	//一对多，城市多个景点
	public function sights() {
		return $this->hasMany(Sight::class, 'city_id', 'id');
	}



	// public function getCenterAttribute($value) {
	// 	return explode(',', $value);
	// }
	// public function setCenterAttribute($value) {
	// 	$this->attributes['center'] = implode(',',  $value);
	// }



}
