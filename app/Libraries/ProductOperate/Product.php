<?php
/**
 * Created by PhpStorm.
 * User: pengpn
 * Date: 2017/11/20
 * Time: 下午9:30
 */

namespace App\Libraries\ProductOperate;


use App\Libraries\Picture;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ShippingCompanyRepository;

class Product
{
    public $errorMessage = '';
    public $shippingCompany;
    public $shippingCompanyIds = [];
    public $categoryList;
    public $categoryIds = [];
    public $suppliers;
    public $supplierIds = [];

    //product data array
    protected $_productArray;
    //for add kitting product
    protected $_partProductsString;
    //is set product tags
    protected $_productTags;
    //is set clean photo
    protected $_defaultCleanPhotoUrl;

    protected $_productRepository;

    //product params in ss_products table
    protected $_productParams = [
        'productID' => null, //cant update
        'product_code' => null, //cant update
        'categoryID' => null,
        'name' => null,
        'description' => null,
        'Price' => null,
        'in_stock' => null,
        'brief_description' => null,
        'weight' => null,
        'meta_description' => null,
        'meta_keywords' => null,
        'scClassID' => null,
        'handling' => null,
        'supplierID' => null,
        'brand' => null,
        'short_name' => null,
        'product_code_type' => null,//cant update
        'warehouseID' => null
    ];

    //product additional params in ss_product_additional table
    protected $_productAdditionalParams = [
        'productID' => null,//cant update
        'supplier_name' => null,
        'supplier_type' => null,
        'purchase_agent' => null,
        'listing_agent' => null,
        'review_agent' => null,
        'lead_time' => null,
        'cost' => null,
        'item_location' => null,
        'discontinued' => null,
        'remark' => null,
        'vparcel' => null,
        'cgroup' => null
    ];

    //product dimension params in ss_product_dimension table
    protected $_productDimensionParams = [
        'length' => null,
        'width' => null,
        'height' => null
    ];

    //description params from uploaded file
    protected $_descriptionArray = [
        'desc_title_0' => null,
        'desc_body_0' => null,
        'desc_domain_0' => null,
        'desc_title_1' => null,
        'desc_body_1' => null,
        'desc_domain_1' => null,
        'desc_title_2' => null,
        'desc_body_2' => null,
        'desc_domain_2' => null,
        'desc_title_3' => null,
        'desc_body_3' => null,
        'desc_domain_3' => null,
        'desc_title_4' => null,
        'desc_body_4' => null,
        'desc_domain_4' => null
    ];

    //image params from uploaded file
    protected $_imageArray = [
        'img_url_0' => null,
        'img_url_1' => null,
        'img_url_2' => null,
        'img_url_3' => null,
        'img_url_4' => null,
        'img_url_5' => null,
        'img_url_6' => null,
        'img_url_7' => null,
        'img_url_8' => null,
        'img_url_9' => null,
        'img_url_10' => null,
        'img_url_11' => null,
        'img_url_12' => null,
    ];

    public function __construct($productArray, ProductRepository $productRepository)
    {
        $this->_productArray = $productArray;
        $this->_productRepository = $productRepository;
    }

    /*
    *  set product params
    */
    public function setProductParams()
    {
        $productArray = $this->_productArray;

        foreach ($this->_productParams as $key => $value) {
            if (isset($productArray[$key]) || !empty($productArray[$key])) {
                $this->_productParams[$key] = trim($productArray[$key],'\"');
            } else {
                unset($this->_productParams[$key]);
            }
        }

        $this->setProductDescription();

        $result = $this->errorMessage != '' ? $this->errorMessage : true;
        return $result;

    }

    /*
     * get product params
     */
    public function getProductParams()
    {
        return $this->_productParams;
    }

    /*
     *  set product description
     */
    public function setProductDescription()
    {
        $desc = [];
        $productArray = $this->_productArray;

        foreach ($this->_descriptionArray as $key => $value) {
            if (isset($productArray[$key]) || !empty($productArray[$key])) {
                $descriptionValue = trim($productArray[$key],'\"');
                $this->_descriptionArray[$key] = $descriptionValue;

                list($prefix, $title, $index) = explode('_', $key);

                if ($title == 'title') {
                    $desc[$index]['title'] = $descriptionValue;
                }

                if ($title == 'body') {
                    $desc[$index]['body'] = $descriptionValue;
                }

                if ($title == 'domain') {
                    $desc[$index]['crazysales'] = 0;
                    $desc[$index]['ebay'] = 0;
                    switch ($descriptionValue) {
                        case 0:
                            $desc[$index]['crazysales'] = 1;
                            break;
                        case 1:
                            $desc[$index]['ebay'] = 1;
                            break;
                        case 2:
                            $desc[$index]['crazysales'] = 1;
                            $desc[$index]['ebay'] = 1;
                            break;
                        default:
                            $desc[$index]['crazysales'] = 1;
                            break;
                    }
                }
            } else {
                unset($this->_descriptionArray[$key]);
            }
        }

        if (count($desc)) {
            $this->_productParams['description'] = $this->_productRepository->prdFormatDescription($desc);
        }

        $result = $this->errorMessage != '' ? $this->errorMessage : true;
        return $result;
    }

    /*
     *  set product additional data
     */
    public function setProductAdditionalParams()
    {
        $productArray = $this->_productArray;
        foreach ($this->_productAdditionalParams as $key => $value) {
            if (isset($productArray[$key]) || !empty($productArray[$key])) {
                $this->_productAdditionalParams[$key] = trim($productArray[$key],'\"');
            } else {
                unset($this->_productAdditionalParams[$key]);
            }
        }
    }

    /*
     * get Product Additional Params
     */
    public function getProductAdditionalParams()
    {
        return $this->_productAdditionalParams;
    }

    /*
     *  set Part Products String
     */
    public function setPartProductsString()
    {
        $productArray = $this->_productArray;
        $this->_partProductsString = trim($productArray['part_products']) ? $productArray['part_products'] : null;
    }

    /*
    * get Part Products String
    */
    public function getPartProductsString()
    {
        return $this->_partProductsString;
    }


    /*
     * validate shipping Company Ids & validate Category Ids & validate supplier Ids
     */
    public function validateData()
    {
        $shippingCompanyRepository = new ShippingCompanyRepository();
        $categoryRepository = new CategoryRepository();

        $this->shippingCompany = $shippingCompanyRepository->getShippingCompanies();
        foreach ($this->shippingCompany as $shippingCompany) {
            $this->shippingCompanyIds[] = $shippingCompany->scClassID;
        }

        $this->categoryList = $categoryRepository->getAllCategories();
        foreach ($this->categoryList as $category) {
            $this->categoryIds[] = $category->categoryID;
        }

        $this->supplierIds[] = [];

        //check property
        $typeError = '';
        $requiredError = '';
        $this->checkProperty($requiredError, $typeError, $this->_productParams, $this->shippingCompanyIds, $this->categoryIds, $this->supplierIds);

        if ($typeError != '' || $requiredError != '') {
            $this->errorMessage .= $typeError;
            $this->errorMessage .= $requiredError;
        }

        $result = $this->errorMessage != '' ? $this->errorMessage : true;
        return $result;

    }

    public function checkProperty(&$required_msg,&$type_msg,$product,$shipping_ids,$category_ids,$supplier_ids)
    {
        $required_arr = array('product_code' => array('required' => true, 'match' => '/^[^\s]+$/', 'msg' => "has space within it"),
            'name' => array('required' => true),
            'categoryID' => array('required' => true, 'match' => '/^[\d]+$/', 'msg' => 'can only have 0-9 digital numbers.'),
            'cost' => array('required' => true, 'match' => '/^[0-9]+[\.]?[0-9]+$|^[\d]+$/', 'msg' => 'is not valid money format.'),
            'Price' => array('required' => true, 'match' => '/^[0-9]+[\.]?[0-9]+$|^[\d]+$/', 'msg' => 'is not valid money format.'),
            'scClassID' => array('required' => true, 'match' => '/^[\d]+$/', 'msg' => 'can only have 0-9 digital numbers.'),
            'maxiInParcel' => array('required' => true, 'match' => '/^[\d]+$/', 'msg' => 'can only have 0-9 digital numbers.'),
            'supplier' => array('match' => '/^[\d]+$/', 'msg' => 'can only have 0-9 digital numbers.'),
            'in_stock' => array('match' => '/^[\d]+$/', 'msg' => 'can only have 0-9 digital numbers.'),
            'handling' => array('match' => '/^[0-9]+[\.]?[0-9]+$|^[\d]+$/', 'msg' => 'is not valid money format.'),
            'sort_order' => array('match' => '/^[\d]+$/', 'msg' => 'can only have 0-9 digital numbers.'),
            'shipping_freight' => array('match' => '/^[0-9]+[\.]?[0-9]+$|^[\d]+$/', 'msg' => 'is not valid money format.'),
            'list_price' => array('match' => '/^[0-9]+[\.]?[0-9]+$|^[\d]+$/', 'msg' => 'is not valid money format.'),
            'drop_shipping_rate' => array('match' => '/^[0-9]+[\.]?[0-9]+$|^[\d]+$/', 'msg' => 'is not valid money format.'),
            'weight' => array('match' => '/^[0-9]+[\.]?[0-9]+$|^[\d]+$/', 'msg' => 'is invalid'),
            'vparcel' => array('match' => '/^[0-9]*\.?[0-9]+$/', 'msg' => 'invalid'),
            'consignment' => array('match' => '/^[01]$/', 'msg' => 'invalid'),
            'noParcel' => array('match' => '/^[\d]+$/', 'msg' => 'can only have 0-9 digital numbers.')
        );

        foreach ($product as $property => $value) {
            if (array_key_exists($property, $required_arr)) {
                if (isset($required_arr[$property]['required'])) {
                    if (strlen($value) == 0) {
                        $required_msg .= $property."\n";
                    }
                }

                if(isset($required_arr[$property]['match'])&& strlen($value)){
                    if(!preg_match($required_arr[$property]['match'],$value)){
                        $type_msg .= $property.' '.$required_arr[$property]['msg'].'\n';
                    }
                }

                if($property == 'categoryID'){
                    if(!in_array($value,$category_ids)){
                        $type_msg .= "categoryID doesn't exist.\n";
                    }
                }

                if($property == 'scClassID'){
                    if(!in_array($value,$shipping_ids)){
                        $type_msg .="Invalid scClassID.\n";
                    }
                }

                if($property == 'supplier'){
                    if(!in_array($value,$supplier_ids) && !empty($value)){
                        $type_msg .="Invalid supplier id.\n";
                    }
                }
            }


        }
    }

    /*
     * set product image array
     */
    public function setProductImage()
    {
        $productArray = $this->_productArray;
        foreach ($this->_imageArray as $key => $value) {
            if (isset($productArray[$key]) || !empty($productArray[$key])) {
                $this->_imageArray[$key] = $productArray[$key];
            } else {
                unset($this->_imageArray);
            }
        }
    }

    /*
     * get product image
     */
    public function getProductImage()
    {
        return $this->_imageArray;
    }

    /*
     * set default clean photo
     */
    public function setCleanPhotoUrl()
    {
        $productArray = $this->_productArray;
        $this->_defaultCleanPhotoUrl = trim($productArray['default_clean_photo_url']) ?
            $productArray['default_clean_photo_url'] : null;
    }

    /*
     * get Clean Photo Url
     */
    public function getCleanPhotoUrl()
    {
        return $this->_defaultCleanPhotoUrl;
    }

    /*
     * add product images
     */
    public function addProductImages($productId,$imagesArray,$defaultCleanPhotoUrl = false)
    {
        foreach ($imagesArray as $image) {
            if (!empty($image)) {
                if (Picture::isImageExist($image)) {
                    $img = file_get_contents($image);
                    if ($img) {
                        $revImg = strrev($image);
                        $tempImgUrl = '/tmp/'.time().".".strrev(substr($revImg,0,strpos($revImg,'.')));
                        file_put_contents($tempImgUrl,$img);

                    }
                }
            }
        }
    }


}