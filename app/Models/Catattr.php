<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catattr extends Model {
	protected $table = 'p_catattrs';

	// protected $casts = [
 //        'list' => 'json',
 //    ];

    protected $extra = [
        'list' => 'json',
    ];

	protected $fillable = [
		'name', 'en_name', 'parent_id', 'category_id', 'isrequired', 'inputtype', 'inputformat', 'order', 'active', 'extra',
	];

	//多个属性
	public function attrvalues() {
		return $this->hasMany(Attrvalue::class, 'catattr_id', 'id');
	}

	//分类、属性 多对多
	public function categories() {
		return $this->belongsToMany(Category::class, 'p_catattr_category', 'catattr_id', 'category_id');
	}


	//一对多
	public function category()
	{
		return $this->belongsTo(Category::class,'category_id');
	}

	//
	//商品反向
	public function product() {
		return $this->belongsTo(Product::class, 'product_id', 'id');
	}

	//父子一对多
	public function childcatattr() {
		return $this->hasMany(Catattr::class, 'parent_id', 'id');
	}

	//值名反向
	public function parentcatattr() {
		return $this->belongsTo(Catattr::class, 'parent_id', 'id');
	}


	public function getExtraAttribute($extra)
    {
        return array_values(json_decode($extra, true) ?: []);
    }

    public function setExtraAttribute($extra)
    {
        $this->attributes['extra'] = json_encode(array_values($extra));
    }

}
