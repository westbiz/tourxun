<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model {
	protected $table = 'tx_destinations';

	protected $fillable = [
		'name', 'parent_id', 'order', 'promotion', 'description',
	];

	//多对多，父类
	public function categories() {
		return $this->belongsToMany(Category::class, 'tx_category_destinations', 'category_id', 'destination_id')->withTimestamps();
	}

	//一对多，反向

}
