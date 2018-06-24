<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nav extends Model {
	protected $table = 'tx_navs';

	protected $fillable = [
		'name', 'parent_id', 'level', 'description',
	];

	public function childrenNavs() {
		return $this->hasMany(Nav::class, 'parent_id', 'id');
	}

	public function parentNav() {
		return $this->belongsTo(Nav::class, 'parent_id', 'id');
	}
}
