<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductBrand extends Model
{
    protected $table = 'ss_product_brand';

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }
}
