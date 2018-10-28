<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attrvalue extends Model
{
    protected $table = 'p_attrvalues';


    protected $fillable = [
    	'product_id','catattr_id','attrvalue', 'order', 'status',
    ];


    public function catattr()
    {
    	return $this->belongsTo(Catattr::class, 'catattr_id', 'id');
    }
}
