<?php

namespace App\Models;

use App\Models\Picture;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model {
	use SoftDeletes;

	protected $table = 'tx_products';

	protected $casts = [
		'attributes' => 'json',
	];

	protected $fillable = ['name', 'category_id', 'day', 'night', 'star', 'attributes', 'summary', 'route', 'content', 'active'];

	//多态关联，图片
	public function pictures() {
		return $this->morphMany(Picture::class, 'tx_pictures', 'pictureable_type', 'pictureable_id', 'id');
	}

	// public function pictures() {
	// 	return $this->hasOne(Picture::class, 'tx_picture_products', 'picture_id', 'product_id');
	// }

	//产品分类，一对多反向
	public function category() {
		return $this->belongsTo(Category::class, 'category_id', 'id');
	}

	//多个属性值 多对多
	public function catavalues() {
		return $this->belongsToMany(Attrvalue::class, 'p_attrvalue_products',
			'product_id', 'attrvalue_id');
	}

	//多个属性值 映射 往返交通
	public function transportations() {
		return $this->catavalues();
	}

	//多个属性值 映射 出行方式
	public function travelmodes() {
		return $this->catavalues();
	}

	//多个属性值 映射 购物
	public function shoppings() {
		return $this->catavalues();
	}

	//多个属性值 映射 接送服务
	public function transfers() {
		return $this->catavalues();
	}

	//多个属性值 映射 适宜人群
	public function persons() {
		return $this->catavalues();
	}

	//多个属性值 映射 酒店等级
	public function hotels() {
		return $this->catavalues();
	}

	//多个属性值 映射 自费项目
	public function extraitems() {
		return $this->catavalues();
	}

	//多个属性值 映射 签证杂费
	public function visataxes() {
		return $this->catavalues();
	}

	//多个属性
	public function catattrs() {
		return $this->hasMany(Catattr::class, 'product_id', 'id');
	}

	//价格一对多
	public function prices() {
		return $this->hasMany(Price::class, 'product_id', 'id');
	}

	//产品评论
	public function comments() {
		return $this->morphMany(Comment::class, 'commentable');
	}

	//多对多
	public function destinations() {
		return $this->belongsToMany(Destination::class, 'tx_destination_products', 'product_id', 'destination_id');
	}

	//多图、文件上传的时候提交的数据为文件路径数组,可以直接用mysql的JSON类型字段存储,定义字段的mutator
	public function setPictureuriAttribute($pictureuri) {
		if (is_array($pictureuri)) {
			$this->attributes['pictureuri'] = json_encode($pictureuri);
		}
	}

	public function getPictureuriAttribute($pictureuri) {
		return json_decode($pictureuri, true);
	}

}
