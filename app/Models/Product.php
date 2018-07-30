<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model {
	use SoftDeletes;

	protected $table = 'tx_products';

	protected $fillable = ['name', 'category_id', 'day', 'night', 'hotel', 'star', 'summary', 'content', 'active'];

	//和图片的 多态关联，
	public function pictures() {
		return $this->morphMany(Picture::class, 'pictureable');
	}

	// public function pictures() {
	// 	return $this->hasOne(Picture::class, 'tx_picture_products', 'picture_id', 'product_id');
	// }

	//产品分类，一对多反向
	public function category() {
		return $this->belongsTo(Category::class, 'category_id', 'id');
	}

	//图片一对多,json格式
	public function graphs() {
		return $this->hasOne(Picture::class, 'product_id');
	}

	//价格一对多
	public function prices() {
		return $this->hasMany(Price::class, 'product_id');
	}

	//多图、文件上传的时候提交的数据为文件路径数组,可以直接用mysql的JSON类型字段存储,定义字段的mutator
	// public function setPicturesAttribute($pictures) {
	// 	if (is_array($pictures)) {
	// 		$this->attributes['pictures'] = json_encode($pictures);
	// 	}
	// }

	// public function getPicturesAttribute($pictures) {
	// 	return json_decode($pictures, true);
	// }
}
