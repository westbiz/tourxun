<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sight extends Model
{
    protected $table = 'tx_sights';

    protected $fillable = [
    	'name','city_id','picture_id','summary','content',
    ];

    //一对多
    public function pictures(){
    	return $this->hasMany(Pictures::class,'picture_id');
    }

}
