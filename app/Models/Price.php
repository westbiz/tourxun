<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model {
	protected $table = 'tx_prices';

	protected $casts = [
		'attributes' => 'json',
	];

	protected $fillable = [
		'product_id', 'name', 'shop_id', 'departure_id', 'star', 'sku', 'price', 'start_date', 'end_date', 'quantity', 'schedule', 'remark', 'attributes',
	];

	public function product() {
		return $this->belongsTo(Product::class, 'product_id');
	}

	//远层一对多
	// public function category() {
	// 	return $this->hasManyThrough(Category::class, Product::class);
	// }

	//
	// public function getAttributesAttribute($attributes)
	//    {
	//        return array_values(json_decode($attributes, true) ?: []);
	//    }

	//    public function setAttributesAttribute($attributes)
	//    {
	//        $this->attributes['attributes'] = json_encode(array_values($attributes));
	//    }
}
