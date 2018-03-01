<?php
/**
 * Created by PhpStorm.
 * User: pengpn
 * Date: 2017/12/26
 * Time: 下午9:24
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ProductPicture extends Model
{
    protected $table = 'ss_product_pictures';
    protected $primaryKey = 'photoID';
}