<?php

namespace App\Models;

use App\Models\Area;
use App\Models\Sight;
use Illuminate\Database\Eloquent\Model;

class Sight extends Model {
	protected $table = 'tx_sights';

	protected $fillable = [
		'name', 'picture', 'city_id', 'summary', 'content',
	];

	//图片，多态关联
	// public function pics() {
	// 	return $this->morphMany(Picture::class, 'pictureable', 'pictureable_type', 'pictureable_id', 'id');
	// }

	//多对多
	// public function pictures() {
	// 	return $this->belongsToMany(Picture::class, 'tx_picture_sights', 'sight_id', 'picture_id');
	// }

	public function city() {
		return $this->belongsTo(Area::class, 'city_id', 'id');
	}

	//设置图片json属性
	public function setPictureuriAttribute($pictureuri) {
		if (is_array($pictureuri)) {
			$this->attributes['pictureuri'] = json_encode($pictureuri);
		}
	}

	public function getPictureuriAttribute($pictureuri) {
		return json_decode($pictureuri, true);
	}

}
