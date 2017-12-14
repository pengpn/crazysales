<?php
/**
 * Created by PhpStorm.
 * User: pengpn
 * Date: 2017/11/20
 * Time: 下午9:35
 */

namespace App\Libraries\ProductOperate;

use App\Models\ProductAdditional;
use App\Models\ProductBrand;
use Carbon\Carbon;
use DB;
use Auth;
use App\Repositories\ProductRepository;
use App\Repositories\ShippingCompanyRepository;

class AddProduct
{
    public $productRepository;
    public $admin;
    public $errorMessage = '';
    protected $_product;
    protected $_productParams;
    protected $_productAdditionalParams;
    protected $_isKittingProduct;
    protected $_partProductsInfo = [];
    protected $_partProductsArray;



    //the product data need to write in file
    private $_needParams = [
        'product_code_type' => null,
        'product_code' => null,
        'name' => null,
        'categoryID' => null,
        'Price' => null,
        'scClassID' => null,
        'weight' => null,
        'warehouseID' => 'UK',
    ];

    //the product additional data need to write in file
    private $_needAdditionalParams = [
        'cost' => null
    ];

    //the kitting product's data base on part product, can not write.
    private $_kittingProductAutoAdd = [
        'cubic' => null,
        'weight' => null,
        'noparcel' => null,
        'in_stock' => null,
        'handling' => null
    ];

    public function __construct(Product $product)
    {

        $this->productRepository = new ProductRepository();
        $this->_product = $product;
        $this->admin = Auth::user()->login;

        //product data param
        $setProductParamsResult = $product->setProductParams();
        if ($setProductParamsResult !== true) {
            $this->errorMessage .= $setProductParamsResult;
            return $this->errorMessage;
        } else {
            $this->_productParams = $product->getProductParams();
        }

        //product additional params
        $product->setProductAdditionalParams();
        $this->_productAdditionalParams = $product->getProductAdditionalParams();

        //is kitting product
        $this->_isKittingProduct = $this->_productParams['product_code_type'] == 1 ? true : false;
    }

    public function addProduct()
    {
        $this->_validateAddedData();

        //validate kitting product
        if ($this->_isKittingProduct) {
            $this->_validateKittingProduct();
        }

        //if appear error return error message
        if ($this->errorMessage != '') {
            return $this->errorMessage;
        }

        //set product params
        $product = $this->_productParams;
        $time = Carbon::now()->toDateTimeString();
        $product['added_admin'] = $this->admin;
        $product['date_added'] = $product['date_modified'] = $time;
        $product['enabled'] = 0;

        //in_stock can't be null
        if (!isset($product['in_stock']) || empty($product['in_stock'])) {
            $product['in_stock'] = 0;
        }

        $product['supplierID'] = is_null($product['supplierID']) ? 0 : $product['supplierID'];

        $productID = $this->productRepository->add($product);

        if (isset($product['brand'])) {
            $brandCount = ProductBrand::where('brand_name',$product['brand'])->count();
            if ($brandCount <= 0) {
                $brandArray = [
                    'brand_name' => $product['brand'],
                    'brand_created' => Carbon::now()->toDateString(),
                ];
                $brandID = ProductBrand::insertGetId($brandArray);

                if (!$brandID) {
                    $this->errorMessage .= "add new brand error . need check data.\n";
                }
            }
        }

        //add product additional
        $productAdditionalArray = $this->_productAdditionalParams;
        if (!empty($productAdditionalArray)) {
            $productAdditionalArray['productID'] = $productID;
            $productAdditionalID = ProductAdditional::insertGetId($productAdditionalArray);
            if (!$productAdditionalID) {
                $this->errorMessage .= "insert product addition error . need check data.\n";
            }

            \App\Models\Product::find($productID)->update(['additional_id',$productAdditionalID]);
        }

        //save supplier info

        //add images
        $this->_product->setProductImage();
        $imageArray = $this->_product->getProductImage();
        $this->_product->setCleanPhotoUrl();
        $cleanPhotoUrl = $this->_product->getCleanPhotoUrl();
        if (!empty($imageArray)) {

        }

    }

    /*
     * validate added product data
     */
    protected function _validateAddedData()
    {
        //check need product data params
        foreach ($this->_needParams as $key => $value) {
            if ($this->_isKittingProduct && array_key_exists($key, $this->_kittingProductAutoAdd)) {
                continue;
            }

            if ($key == 'product_code') {
                $product = Product::where('product_code', $this->_productParams[$key])->get();
                if (!$product->isEmpty()) {
                    $this->errorMessage .= "SKU have existed in system . \n";
                    echo $this->errorMessage;
                }
            }

            if (is_null($this->_productParams[$key])) {
                $this->errorMessage .= "need {$key} data .\n";
                echo $this->errorMessage;
            }
        }

        //check need additional data params
        foreach ($this->_needAdditionalParams as $key => $value) {
            if ($this->_isKittingProduct && array_key_exists($key, $this->_kittingProductAutoAdd)) {
                continue;
            }

            if (is_null($this->_productAdditionalParams[$key])) {
                $this->errorMessage .= "need {$key} data .\n";
            }
        }

        //validate some data
        $validateDataResult = $this->_product->validateData();
        if ($validateDataResult != true) {
            $this->errorMessage .= $validateDataResult;
        }

        //check price and cost
        $cost = trim($this->_productAdditionalParams['cost']);
        $price = trim($this->_productParams['Price']);

        if (!is_null($cost)) {
            $cost = round(floatval($cost), 2);
            $this->_productAdditionalParams['cost'] = $cost;
        }

        if (!is_null($price)) {
            $price = round(floatval($cost), 2);
            $this->_productParams['Price'] = $price;
        }

        if (!is_null($price) && !is_null($cost) && ($cost > $price)) {
            $this->errorMessage .= "Price is lower than cost .\n";
            echo $this->errorMessage;
        }

        if (!is_null($price) && $price <= 0) {
            $this->errorMessage .= "Only gift item can be set $0.\n";
        }

        $result = $this->errorMessage != '' ? $this->errorMessage : true;

        return $result;


    }

    /*
     * validate kitting product
     */
    protected function _validateKittingProduct()
    {
        $productAdditionalParams = $this->_productAdditionalParams;

        $this->_product->setPartProductsString();
        $partProductString = $this->_product->getPartProductsString();

        //check part product data
        if (!$partProductString) {
            $this->errorMessage .= "part product is null.\n";
        }

        //get part product data
        $partProductsArray = [];
        $filterPartProducts = explode(';', $partProductString);
        foreach ($filterPartProducts as $key => $value) {
            $partProductsArray[] = explode('|', $value);
        }

        //check part products data
        foreach ($partProductsArray as $key => $value) {
            $sku = trim($value[0]);
            $partQty = trim($value[1]);

            if (empty($sku)) {
                $this->errorMessage .= "Part product SKU is null. \n";
            }

            if (intval($partQty) < 0 || is_null($partQty)) {
                $this->errorMessage .="Part Qty is Invalid. \n";
            }

            foreach($partProductsArray as $validateKey => $validateValue) {
                if ($key != $validateKey && $value == $validateValue) {
                    $this->errorMessage .= "Duplicate SKU in part products. \n";
                }
            }

            if ($this->errorMessage != '') {
                return $this->errorMessage;
            }

            $productRepository = new ProductRepository();
            $partProductInfo = $productRepository->bySku($sku);

            if ($partProductInfo) {
                $this->_partProductsInfo[] = $partProductInfo;
            } else {
                $this->errorMessage .= "{$sku} not exist. \n";
            }

            $partProductsArray[$key]['sku'] = $sku;
            $partProductsArray[$key]['part_qty'] = $partQty;
            $this->_partProductsArray = $partProductsArray;

        }

        //check kitting product cost
        $validateCostResult = $this->validateKittingProductCostToBeInserted($partProductsArray, $productAdditionalParams['cost']);
        if (!$validateCostResult) {
            $this->errorMessage .= "Cost is smaller than the sum if parts!\n";
        }

        $result = $this->errorMessage != '' ? $this->errorMessage : true;
        return $result;

    }

    /**
     * Check if the cost of a New Kitting Product is bigger than the sum of cost of part product
     *
     * @param array $partProductArr  - Kitting Relations for a New Kitting product
     * @param float $kittingCost - cost of a New Kitting product
     *
     * @return boolean true | false - success | fail
     * @author Sean
     */
    public function validateKittingProductCostToBeInserted($partProductArr, $kittingCost)
    {
        $kittingCost = $kittingCost + 0;
        $totalCost = 0;

        if (!is_array($partProductArr) || $kittingCost < 0) {
            return false;
        }

        foreach ($partProductArr as $partProduct) {
            $data = DB::table('ss_products p')
                ->join('ss_product_additional a','p.productID','=','a.productID')
                ->select('format(cost,2)')
                ->where('product_code','=',$partProduct['sku'])
                ->get();

            $totalCost += ($data['cost'] * $partProduct['part_qty']);

        }

        if ($totalCost > $kittingCost) {
            return false;
        }

        return true;
    }
}