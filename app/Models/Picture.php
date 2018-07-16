<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model {
	protected $table = 'tx_pictures';

	protected $fillable = [
		'product_id', 'picture_type', 'pictureuri', 'description',
	];

	public function product() {
		return $this->belongsTo(Product::class, 'product_id');
	}

	//一对多反向
	public function sight(){
		return $this->belongsTo(Sight::class,'picture_id');
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
