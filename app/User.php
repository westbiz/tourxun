<?php

namespace App;

use App\Models\Profile;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable {
	use HasApiTokens, Notifiable;

	protected $table = 't_users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'avatar', 'email', 'password',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		// 'password',
		'remember_token',
	];

	public function profile() {
		return $this->hasOne(Profile::class, 'id', 'id');
	}
}
