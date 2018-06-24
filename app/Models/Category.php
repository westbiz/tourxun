<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model {

	use SoftDeletes;

	protected $table = 'tx_categories';

	protected $dates = ['deleted_at'];

	protected $fillable = [
		'name', 'parent_id', 'level', 'description',
	];

	public function scopeParents($query) {
		return $query->where('parent_id', 0);
	}

	//一对多，多个产品
	public function products() {
		return $this->hasMany(Product::class, 'category_id', 'id');
	}

	//一对多
	public function childcategories() {
		return $this->hasMany(Category::class, 'parent_id', 'id');
	}

	//一对多，父类 反向
	public function parentcategory() {
		return $this->belongsTo(Category::class, 'parent_id', 'id');
	}

	//所有子类
	public function allchildcategories() {
		return $this->childcategory()->with('allchildcategories');
	}
}
