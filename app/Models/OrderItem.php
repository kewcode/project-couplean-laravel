<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $guarded = [];  

    public $timestamps = false;


    public function varian()
    {
        return $this->hasOne('App\Models\VarianProduct', 'id', 'varian_product_id');
    }
   

}
