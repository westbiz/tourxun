<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sighttype extends Model {
	protected $table = 'tx_sighttype';

	public function parenttype() {
		return $this->hasMany(Sighttype::class, 'parent_id', 'id');
	}

	public function childrentypes() {
		return $this->belongsTo(Sighttype::class, 'parent_id', 'id');
	}

}
