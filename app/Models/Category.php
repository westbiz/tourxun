<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {
	protected $table = 'tx_category';

	protected $fillable = [
		'name', 'parent_id', 'level', 'description',
	];

	//一对多
	public function parentCategory() {
		return $this->hasMany(Category::class);
	}

	public function childCategory() {
		return $this->belongsTo(Category::class, 'parent_id', 'id');
	}
}
