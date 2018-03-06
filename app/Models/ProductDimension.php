<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDimension extends Model
{
    protected $table = 'ss_product_dimension';
    protected $primaryKey = 'id';
    protected $fillable = ['productID','width','height','length'];
    public $timestamps = false;//must be public

    public function product()
    {
        return $this->hasOne('App\Models\Product','productID','id');
    }
}
