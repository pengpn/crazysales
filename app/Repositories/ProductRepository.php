<?php
namespace App\Repositories;
use App\Models\Product\Product;

class ProductRepository
{
    public function byId($id)
    {
        return Product::find($id);
    }

    public function bySku($sku)
    {
        return Product::select('product_code',$sku)->get();
    }

    public function explodeDescription($description)
    {
        $result = [];

        $arr = explode('<!---->',$description);
        $count = count($arr);



        for ($i = 0; $i < $count; $i += 2) {
            $title = ucwords(trim($arr[$i]));
            $title = str_replace(PHP_EOL, '', $title);
            $title_pos = strripos($title, '<!--');
            $limitation = trim(substr($title,$title_pos));

            if ($limitation == '<!--ebay-->') {
                $ebay = true;
                $crazySales = false;
                $title = substr($title ,0 ,$title_pos);
            } elseif ($limitation == '<!--crazysales-->') {
                $ebay = false;
                $crazySales = true;
                $title = substr($title, 0, $title_pos);
            } elseif ($limitation == '<!--none-->') {
                $ebay = false;
                $crazySales = false;
                $title = substr($title, 0, $title_pos);
            } else {
                $ebay = true;
                $crazySales = true;
            }

            if ($i + 1 < $count) {
                $result[$title] = [
                    'title'      => $title,
                    'body'       => $arr[$i + 1],
                    'crazysales' => $crazySales,
                    'ebay'       => $ebay
                ];
            }
        }
        return $result;
    }

    public function formatDescription($oldDescription,$description)
    {
        # desc_Features
        $newDescArr = [];
        $result = '';
        $i = 0;
        foreach ($oldDescription as $oldDesc) {
            $title = $oldDesc->title;
            $newDescTitle = 'desc_' . $title;
            $newDescArr[$i]['title'] = $title;
            $newDescArr[$i]['body'] = $description[$newDescTitle];
            $newDescArr[$i]['crazysales'] = $oldDesc->crazysales;
            $newDescArr[$i]['ebay'] = $oldDesc->ebay;
            $i++;
        }

        for ($i = 0; $i < count($newDescArr); $i++) {
            $title = trim($newDescArr[$i]["title"]);
            $body = trim($newDescArr[$i]["body"]);
            if(0 == strlen($title) && 0 == strlen($body)) continue;
            if("" != $result) $result .= "<!---->\n";
            if(($newDescArr[$i]["crazysales"]) && ($newDescArr[$i]["ebay"]))
                $limition = "";
            elseif($newDescArr[$i]["crazysales"])
                $limition = "\n<!--crazysales-->\n";
            elseif($newDescArr[$i]["ebay"])
                $limition = "\n<!--ebay-->\n";
            else
                $limition = "\n<!--none-->\n";

            $result .= $title . $limition . "\n<!---->\n" . $body . "\n";
        }
        return $result;
    }

    public function prdFormatDescription($descArray)
    {
        if (!is_array($descArray) || count($descArray) == 0) {
            return null;
        }

        $result = '';

        for ($i = 0; $i < count($descArray); $i++) {
            $title = trim($descArray[$i]["title"]);
            $body = trim($descArray[$i]["body"]);
            if(0 == strlen($title) && 0 == strlen($body)) continue;
            if("" != $result) $result .= "<!---->\n";
            if(($descArray[$i]["crazysales"]) && ($descArray[$i]["ebay"]))
                $limition = "";
            elseif($descArray[$i]["crazysales"])
                $limition = "\n<!--crazysales-->\n";
            elseif($descArray[$i]["ebay"])
                $limition = "\n<!--ebay-->\n";
            else
                $limition = "\n<!--none-->\n";

            $result .= $title . $limition . "\n<!---->\n" . $body . "\n";
        }

        return $result;
    }

    public function paginate($num = 20)
    {
        return Product::paginate($num);
    }

    public function findByProductID($productID)
    {
        return Product::where('productID',$productID)->paginate(20);
    }

    public function findBySKU($productCode)
    {
        return Product::where('product_code','like','%' . $productCode . '%')->paginate(20);
    }

    public function add($productArray)
    {
        $productID = Product::insertGetId($productArray);
        return $productID;
    }
}