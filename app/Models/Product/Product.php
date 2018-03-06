<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'ss_products';
    protected $primaryKey = 'productID';
    protected $fillable = [
        'categoryID','name','Price','brief_description','description',
        'product_code','default_picture','created_at','updated_at',
        'weight','meta_description','meta_keywords','scClassID',
        'handling','cubic','brand','supplierID','added_admin',
        'modified_admin','short_name','product_type','product_code_type',
        'isEbayGroup','attribute_str','is_presale','presale_date','is_return',
        'isValid','warehouseID','amazon_listing','amazon_sold',
    ];

    /*
     * 获取一个产品的分类
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category\Category','categoryID','categoryID');
    }

    public function productAdditional()
    {
        return $this->belongsTo('App\Models\ProductAdditional','additional_id','productID');
    }

    public function productBrand()
    {
        return $this->belongsTo('App\Models\Product\ProductBrand','brandID','id');
    }

    public function productDimension()
    {
        return $this->belongsTo('App\Models\Product\ProductDimension','dimensionID','id');
    }

}
