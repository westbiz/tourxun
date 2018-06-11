<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model {

	use SoftDeletes;

	protected $table = 'tx_category';

	protected $dates = ['deleted_at'];

	protected $fillable = [
		'name', 'parent_id', 'level', 'description',
	];

	public function scopeParents($query) {
		return $query->where('parent_id', 0);
	}

	//一对多
	public function childcategory() {
		return $this->hasMany(Category::class, 'parent_id', 'id');
	}

	public function parentcategory() {
		return $this->belongsTo(Category::class, 'parent_id', 'id');
	}

	// public function getChild() {
	// 	return $this->child()->with('child')->get();
	// }
}
