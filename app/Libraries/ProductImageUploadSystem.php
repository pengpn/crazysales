<?php
/**
 * Created by PhpStorm.
 * User: pengpn
 * Date: 2017/12/26
 * Time: 下午9:02
 */

namespace App\Libraries;


use App\Models\ProductPricture;
use Illuminate\Support\Facades\DB;

class ProductImageUploadSystem
{
    const PATH = DIRECTORY_SEPARATOR.'products_pictures'.DIRECTORY_SEPARATOR;
    const Table = 'ss_product_pictures';

    private $productID;
    private $tokenId;
    private static $folder;
    private static $targetDir;

    /* Sizes of the image */
    private static $size = array(
        array("width"=>450,"height"=>395,"sign"=>"F"),
        array("width"=>105,"height"=>92,"sign"=>"T"),
        array("width"=>47,"height"=>44,"sign"=>"ES"),
        array("width"=>166,"height"=>166,"sign"=>"N"),
        array("width"=>1200,"height"=>1200,"sign"=>"HD"),
        array("sign"=>"OR"),//origin image
    );

    /* Sizes of the image */
    private static $square_size = array(
        array("width"=>450,"height"=>450,"sign"=>"F"),
        array("width"=>105,"height"=>105,"sign"=>"T"),
        array("width"=>47,"height"=>47,"sign"=>"ES"),
        array("width"=>166,"height"=>166,"sign"=>"N"),
        array("width"=>1200,"height"=>1200,"sign"=>"HD"),
        array("sign"=>"OR"),//origin image
    );

    public function __construct($source,$productID,$tokenId=7){
        $this->sourceimage = new Image($source);
        $this->productID = $productID;

        self::$folder = floor($this->productID/100).DIRECTORY_SEPARATOR.$tokenId.DIRECTORY_SEPARATOR;

        self::$targetDir = base_path().self::PATH.self::$folder;
        $this->tokenId = $tokenId;
    }

    public function createThumbnails($squareImage = false)
    {
        $photoID = ProductPricture::insertGetId(['productID' => $this->productID]);
    }
}