<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sighttype extends Model {
	protected $table = 'tx_sighttype';

	protected $fillabel = [
		'name', 'parent_id', 'order', 'description',
	];

	//景点分类多对多
	public function sights() {
		return $this->belongsToMany(Sight::class, 'sight_sighttype');
	}

	//一对多父类
	public function childtypes() {
		return $this->hasMany(Sighttype::class, 'parent_id', 'id');
	}

	//一对多逆向
	public function parenttype() {
		return $this->belongsTo(Sighttype::class, 'parent_id', 'id');
	}

}
