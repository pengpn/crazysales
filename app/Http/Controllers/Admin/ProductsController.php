<?php

namespace App\Http\Controllers\Admin;

use App\Excel\ProductImport;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product\Product;
use App\Models\ProductAdditional;
use App\Models\Product\ProductBrand;
use App\Models\Product\ProductPicture;
use App\Repositories\AdminRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\WarehouseRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Excel;

class ProductsController extends Controller
{
    protected $categoryRepository;
    protected $productRepository;
    protected $warehouseRepository;
    protected $adminRepository;



    public function __construct(CategoryRepository $categoryRepository,
                                ProductRepository $productRepository,
                                WarehouseRepository $warehouseRepository,
                                AdminRepository $adminRepository)
    {
        $this->middleware(['auth']);
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
        $this->warehouseRepository = $warehouseRepository;
        $this->adminRepository = $adminRepository;
    }

    public function index()
    {
        $products = $this->productRepository->paginate();

        return view('admin.products.index',compact('products'));
    }

    public function search(Request $request)
    {

        if ($request->productID) {
            $products = $this->productRepository->findByProductID($request->productID);
        } elseif($request->product_code) {
            $products = $this->productRepository->findBySKU($request->product_code);
        }
        return view('admin.products.index',compact('products'));
    }

    public function edit($id)
    {
        $product = $this->productRepository->byId($id);
        $desc = arrayToObject($this->productRepository->explodeDescription($product->description));
        $warehouses = $this->warehouseRepository->getWarehouseList();
        $admins = $this->adminRepository->geAllAdmin();
        $tree = $this->categoryRepository->recursiveCategory();
        $brandList = ProductBrand::select(['id','brand_name'])->get();
        return view('admin.products.edit',compact('product','tree','brandList',
                                                        'desc','warehouses','admins'));
    }

    public function update(UpdateProductRequest $request,$id)
    {

        $product = $this->productRepository->byId($id);
        $productAdditional = ProductAdditional::find($product->additional_id);


        $description = [
            'desc_Product' => $request->get('desc_Product'),
            'desc_Features' => $request->get('desc_Features'),
            'desc_Specification' => $request->get('desc_Specification'),
            'desc_Warranty' => $request->get('desc_Warranty'),
        ];

        $descObject = arrayToObject($this->productRepository->explodeDescription($product->description));


        $newDescArr = [];
        $i = 0;
        foreach ($descObject as $oldDesc) {
            $title = $oldDesc->title;
            $newDescTitle = 'desc_' . $title;
            $newDescArr[$i]['title'] = $title;
            $newDescArr[$i]['body'] = $description[$newDescTitle];
            $newDescArr[$i]['crazysales'] = $oldDesc->crazysales;
            $newDescArr[$i]['ebay'] = $oldDesc->ebay;
            $i++;
        }


//        $formatDesc = $this->productRepository->formatDescription($desc,$description);
        $formatDesc = $this->productRepository->prdFormatDescription($newDescArr);

        $product->update([
            'categoryID' => $request->get('category'),
            'name' => $request->get('title'),
            'Price' => $request->get('price'),
            'brandID' => $request->get('brandID'),
            'description' => $formatDesc,
        ]);
        $productAdditional->update([
            'cost' => $request->get('cost'),
            'listing_agent' => $request->get('listing_agent'),
            'purchase_agent' => $request->get('purchase_agent'),
            'review_agent' => $request->get('review_agent')
        ]);


        admin_toastr(Config::get('constants.update_succeeded'));

        return redirect($request->get('_previous_'));


//        return redirect('/home');
    }

    public function showImportModule()
    {
        return view('admin.products.import');
    }

    public function doImport(Request $request)
    {
//        $file = Input::file('importProductCSV');
//        Excel::load($file, function ($reader) {
//            $sheet = $reader->getActiveSheet();
//            $letters = $reader->get()[0]->keys();
//
//            foreach ($letters as &$letter) {
//                $letter = strstr($letter, '*') ? ltrim($letter,'*') : $letter;
//            }
//
//
//            foreach ($reader->get() as $row => $result) {
//                if ($row == 0) continue;
//                $data = array_combine($letters,$result);
//                foreach ($data as $column => $cell_value) {
//
//                    if (in_array($column, (new Product)->getFillable())) {
//
//                    } elseif (in_array($column, (new ProductAdditional)->getFillable())) {
//
//                    } elseif (in_array($column, (new ProductDimension)->getFillable())) {
//
//                    } elseif (in_array($column, (new ProductPicture)->getUploadField())) {
//
//                    } elseif (in_array($column,(new Product)->getDescUploadField())) {
//
//                    }
//                }
//            }
//        });

//       $productsList = $productImport->get();
//       foreach ($productsList as $product ){
//           print_r($product->brand);
//       }
//       exit();
//       dd($csv->toArray());



//       foreach ($csv as $item) {
//           foreach ($item as $key => $cell) {
//               print_r($key . "-" . $cell);
//           }
//       }
       exit();
    }
}
