<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Area extends Model
{
    protected $table = 't_areas';


    public function cities()
    {
    	return $this->hasMany(Area::class,'parent_id','id');
    }


    public function province()
    {
    	return $this->belongsTo(Area::class,'parent_id','id');
    }



}
