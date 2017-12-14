<?php
/**
 * Created by PhpStorm.
 * User: pengpn
 * Date: 2017/11/16
 * Time: 下午9:29
 */

namespace App\Excel;




use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Files\ExcelFile;

class ProductImport extends ExcelFile
{

    protected $delimiter  = ',';
    protected $enclosure  = '"';
    protected $lineEnding = '\r\n';

    public function getFile()
    {
        //Import a user provided file
        $file = Input::file('importProductCSV');
        $filename = $file->getRealPath();
        return $filename;

    }
}