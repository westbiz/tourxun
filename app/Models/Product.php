<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model {
	use SoftDeletes;

	protected $table = 'tx_products';

	protected $fillable = ['name', 'category_id', 'day', 'night', 'hotel', 'star', 'summary', 'content', 'active'];

	//产品分类，一对多反向
	public function category() {
		return $this->belongsTo(Category::class, 'category_id', 'id');
	}

	public function setPicturesAttribute($pictures) {
		if (is_array($pictures)) {
			$this->attributes['pictures'] = json_encode($pictures);
		}
	}

	public function getPicturesAttribute($pictures) {
		return json_decode($pictures, true);
	}
}
