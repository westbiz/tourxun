<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model {

	protected $table = 'tx_destinations';

	// protected $hidden = ['pivot'];

	protected $fillable = [
		'name', 'parent_id', 'country_id', 'city_id', 'order', 'promotion', 'description',
	];

	//多对多，父类
	public function categories() {
		return $this->belongsToMany(Category::class, 'tx_category_destinations', 'destination_id', 'category_id');
	}

	//多对多
	public function products() {
		return $this->belongsToMany(Product::class, 'tx_destination_products', 'destination_id', 'product_id');
	}

	public function country() {
		return $this->belongsTo(Country::class, 'country_id', 'id');
	}

	public function city() {
		return $this->belongsTo(Worldcity::class, 'city_id', 'id');
	}

	//一对多，反向
	public function parent() {
		return $this->belongsTo(Category::class, 'id');
	}

	public function children() {
		return $this->hasMany(Destination::class, 'id');
	}

	public function brothers() {
		return $this->parent->children();
	}

	//
	public static function options($id) {
		if (!$self = static::find($id)) {
			return [];
		}
		return $self->brothers()->pluck('name', 'id');
	}

}
