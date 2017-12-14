<?php
namespace App\Repositories;
use DB;

class ShippingCompanyRepository
{
    public function getShippingCompanies()
    {
        return DB::table('ss_shipping_companies')
                    ->select('scClassID','name','fullName')
                    ->where('isEnabled','1')
                    ->get();
    }
}