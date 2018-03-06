<?php
/**
 * Created by PhpStorm.
 * User: pengpn
 * Date: 2017/12/26
 * Time: 下午9:24
 */

namespace App\Models\Product;


use Illuminate\Database\Eloquent\Model;

class ProductPicture extends Model
{
    protected $table = 'ss_product_pictures';
    protected $primaryKey = 'photoID';
    protected $fillable = ['productID','filename','thumbnail','newthumbnail','image_HD','smallthumbnail','photoOrder',
        'disableInEbay','disableInAmazon','origin_image'];
}