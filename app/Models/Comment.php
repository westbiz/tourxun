<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {
	protected $table = 'tx_comments';

	protected $fillable = [
		'content', 'commentable_id', 'commentable_type', 'pictureuri',
	];

	//多态
	public function commentable() {
		return $this->morphTo();
	}

}
