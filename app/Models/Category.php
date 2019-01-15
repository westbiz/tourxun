<?php

namespace App\Models;

use App\Models\Catattr;
use App\Models\Category;
use App\Models\Destination;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model {

	use SoftDeletes;

	protected $table = 'tx_categories';

	protected $dates = ['deleted_at'];

	// protected $casts = [
	// 	'category_id' => 'array',
	// ];

	protected $fillable = [
		'name', 'parent_id', 'order', 'promotion', 'description',
	];

	public function scopeParents($query) {
		return $query->where('parent_id', 0);
	}

	public function countries() {
		return $this->belongsToMany(Country::class, 't_category_countries', 'country_id', 'category_id');
	}

	public function destinations() {
		return $this->belongsToMany(Destination::class, 'tx_category_destinations', 'category_id', 'destination_id')->withTimestamps();
	}

	//一对多，多个产品
	public function products() {
		return $this->hasMany(Product::class, 'category_id', 'id');
	}

	// //景点分类多对多
	// public function sights() {
	// 	return $this->belongsToMany(Sight::class, 'category_sight');
	// }

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

	//多个属性
	//分类、属性 多对多
	public function catattrs() {
		return $this->belongsToMany(Catattr::class, 'p_catattr_category', 'category_id', 'catattr_id');
	}

	public function parent() {
		return $this->belongsTo(Category::class, 'parent_id');
	}

	public function children() {
		return $this->hasMany(Category::class, 'parent_id');
	}

	public function brothers() {
		return $this->parent->children();
	}

	//
	public static function options($id) {
		if (!$self = static::find($id)) {
			return [];
		}
		return $self->brothers()->pluck('areaName', 'id');
	}

	// //属性值远层一对多
	// public function attrvalues() {
	// 	return $this->hasManyThrough(Attrvalue::class, Catattr::class,
	// 		'category_id', 'catattr_id', 'id', 'id');
	// }

	//定义修改器访问器
	// public function getCategoryIdAttribute($value) {
	// 	return explode(',', $value);
	// }

	// public function setCategoryIdAttribute($value) {
	// 	$this->attributes['category_id'] = implode(',', $value);
	// }

	//设置select默认值
	// public static function getSelectOptions() {
	// 	$options = Category::select('id', 'name as text')->get();
	// 	$selectOption = [];
	// 	foreach ($options as $option) {
	// 		$selectOption[$option->id] = $option->text;
	// 	}
	// 	return $selectOption;
	// }

}
