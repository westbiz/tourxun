<?php

namespace App\Models;

use App\Models\Picture;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Picture extends Model {
	protected $table = 'tx_pictures';

	protected $fillable = [
		'pictureable_id', 'pictureable_type', 'title', 'pictureuri', 'description',
	];

	// 多态关联
	public function pictureable() {
		return $this->morphTo();
	}

	// public function products() {
	// 	return $this->belongsToMany(Product::class, 'tx_picture_products', 'picture_id', 'product_id');
	// }

	public function product() {
		return $this->belongsTo(Product::class, 'product_id');
	}

	//多对多，多景区
	public function sights() {
		return $this->belongsToMany(Sight::class, 'tx_picture_sights', 'picture_id', 'sight_id');
	}

	//图、文件上传的时候提交的数据为文件路径数组,可以直接用mysql的JSON类型字段存储,定义字段的mutator
	public function setPictureuriAttribute($pictureuri) {
		if (is_array($pictureuri)) {
			$this->attributes['pictureuri'] = json_encode($pictureuri);
		}
	}

	public function getPictureuriAttribute($pictureuri) {
		return json_decode($pictureuri, true);
	}
}
