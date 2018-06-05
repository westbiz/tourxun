<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model {
	use SoftDeletes;

	protected $table = 'tx_product';

	protected $fillable = ['name', 'category_id', 'day', 'night', 'hotel', 'comment_id', 'price_id', 'star', 'summary', 'content', 'active'];
}
