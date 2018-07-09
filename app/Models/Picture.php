<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model {
	protected $table = 'tx_pictures';

	protected $fillable = [
		'product_id', 'imageurl', 'description',
	];

	public function product() {
		return $this->belongsTo(Product::class, 'product_id');
	}

	public function setImageurlAttribute($imageurl) {
		if (is_array($imageurl)) {
			$this->attributes['imageurl'] = json_encode($imageurl);
		}
	}

	public function getImageurlAttribute($imageurl) {
		return json_decode($imageurl, true);
	}
}
