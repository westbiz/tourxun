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

	//一对多
	public function parent() {
		return $this->hasMany(Category::class, 'id', 'parent_id');
	}

	public function child() {
		return $this->belongsTo(Category::class, 'parent_id', 'id');
	}
}
