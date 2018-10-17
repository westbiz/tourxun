<?php

namespace App\Models;

use App\Models\Area;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Sight;
use Illuminate\Database\Eloquent\Model;

class Sight extends Model {

	protected $table = 'tx_sights';

	protected $casts = [
		'extra' => 'json',
	];

	protected $fillable = [
		'name', 'rate', 'avatar', 'extra', 'city_id', 'parent_id', 'summary', 'content',
	];

	//多态关联，图片
	public function pictures() {
		return $this->morphMany(Picture::class, 'tx_pictures', 'pictureable_type', 'pictureable_id', 'id');
	}

	//区域一对多逆向
	public function city() {
		return $this->belongsTo(Area::class);
	}

	//一对多逆向
	public function scenic() {
		return $this->belongsTo(Sight::class, 'parent_id', 'id');
	}

	//一对多
	public function spot() {
		return $this->hasMany(Sight::class, 'parent_id', 'id');
	}

	//景点分类 多对多，逆向
	public function categories() {
		return $this->belongsToMany(Category::class, 'category_sight', 'sight_id', 'category_id');
	}

	//景点分类多对多
	public function sighttype() {
		return $this->belongsToMany(Sighttype::class, 'sight_sighttype', 'sight_id', 'type_id');
	}

	//评论
	public function comments() {
		return $this->morphMany(Comment::class, 'commentable');
	}

	//设置图片json属性
	// public function setPictureuriAttribute($pictureuri) {
	// 	if (is_array($pictureuri)) {
	// 		$this->attributes['pictureuri'] = json_encode($pictureuri);
	// 	}
	// }

	// public function getPictureuriAttribute($pictureuri) {
	// 	return json_decode($pictureuri, true);
	// }

}
