<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sight extends Model {
	protected $table = 'tx_sights';

	protected $fillable = [
		'name', 'city_id', 'picture_id', 'summary', 'content',
	];

	//图片，多态关联
	public function pics() {
		return $this->morphMany(Picture::class, 'pictureable', 'pictureable_type', 'pictureable_id', 'id');
	}

	//一对多
	// public function pictures(){
	// 	return $this->hasMany(Pictures::class,'picture_id');
	// }

}
