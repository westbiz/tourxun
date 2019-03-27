<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attrvalue extends Model {
	protected $table = 'p_attrvalues';

	protected $fillable = [
		'catattr_id', 'attrvalue', 'order', 'status',
	];

	//
	//属于多个属性
	public function catattr() {
		return $this->belongsTo(Catattr::class, 'catattr_id', 'id');
	}

	//
	//多个商品属性值
	public function product() {
		return $this->belongsToMany(Product::class, 'p_attrvalue_products', 'attrvalue_id', 'product_id');
	}

}
