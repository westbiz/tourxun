<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model {

	protected $table = 't_profiles';

	protected $fillable = [
		'userid', 'nickname', 'gender', 'birthdate', 'mobile', 'address', 'image',
	];

	public function user() {
		return $this->belongsTo(User::class, 'userid', 'id');
	}
}
