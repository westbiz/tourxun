<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catattr extends Model {
	protected $table = 'p_catattrs';

	protected $fillable = [
		'name', 'parent_id', 'description', 'category_id', 'isrequired', 'inputtype', 'order', 'active',
	];

	//多个属性
	public function attrvalues() {
		return $this->hasMany(Attrvalue::class, 'catattr_id', 'id');
	}

	//分类、属性 多对多
	public function categories() {
		return $this->belongsToMany(Category::class, 'p_catattr_category', 'catattr_id', 'category_id')->withPivot('product_id')->withTimestamps();
	}

	//
	//商品反向
	public function product() {
		return $this->belongsTo(Product::class, 'product_id', 'id');
	}

	//父子一对多
	public function childcatattr() {
		return $this->hasMany(Catattr::class, 'parent_id', 'id');
	}

	//值名反向
	public function parentcatattr() {
		return $this->belongsTo(Catattr::class, 'parent_id', 'id');
	}

}
