<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model {
	protected $table = 'tx_prices';

	protected $fillable = [
		'product_id', 'price', 'remark',
	];

	public function product() {
		return $this->belongsTo(Product::class, 'product_id');
	}
}
