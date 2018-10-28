<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catattr extends Model
{
    protected $table = 'p_catattrs';

    protected $fillable = [
    	'name','fieldname','note','category_id', 'parent_id', 'displayname','isrequired','inputtype',
    ];


    //多个属性
    public function attrvalues()
    {
    	return $this->hasMany(Attrvalue::class, 'catattr_id', 'id');
    }





    //父子一对多
    public function childcatattr()
    {
    	return $this->hasMany(Catattr::class, 'parent_id', 'id');
    }



    //值名反向
    public function parentcatattr()
    {
    	return $this->belangsTo(Catattr::class, 'parent_id', 'id');
    }
}
