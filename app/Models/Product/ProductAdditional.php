<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class ProductAdditional extends Model
{
    protected $table = 'ss_product_additional';
    protected $primaryKey = 'id';
    protected $fillable = ['cost'];
    public $timestamps = false;//must be public

    public function product()
    {
        return $this->hasOne('App\Models\Product\Product','productID','id');
    }
}
