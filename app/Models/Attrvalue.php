<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attrvalue extends Model {
	protected $table = 'p_attrvalues';

	protected $fillable = [
		'product_id', 'catattr_id', 'attrvalue', 'order', 'status',
	];

	//
	//属于多个属性
	public function catattr() {
		return $this->belongsTo(Catattr::class, 'catattr_id', 'id');
	}

	//
	//多个商品属性值
	public function product() {
		return $this->belongsToMany(Product::class, 'product_attrbutevalues', 'product_id', 'attribute_id');
	}

}
