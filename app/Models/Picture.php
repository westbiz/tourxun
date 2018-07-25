<?php

namespace App\Models;

use App\Models\Picture;
use App\Models\Product;
use App\Models\Sight;
use Illuminate\Database\Eloquent\Model;

class Picture extends Model {
	protected $table = 'tx_pictures';

	protected $fillable = [
		'pictureuri', 'description',
	];

	//多态关联
	// public function pictureable() {
	// 	return $this->morphTo();
	// }

	public function products() {
		return $this->belongsToMany(Product::class, 'tx_picture_products', 'picture_id', 'product_id');
	}

	public function product() {
		return $this->belongsTo(Product::class, 'product_id');
	}

	//一对多反向
	public function sight() {
		return $this->belongsTo(Sight::class, 'picture_id');
	}

	public function setPictureuriAttribute($pictureuri) {
		if (is_array($pictureuri)) {
			$this->attributes['pictureuri'] = json_encode($pictureuri);
		}
	}

	public function getPictureuriAttribute($pictureuri) {
		return json_decode($pictureuri, true);
	}
}
