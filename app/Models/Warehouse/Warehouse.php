<?php

namespace App\Models\Warehouse\Warehouse;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $table = 'ss_shipping_warehouse';

    public function warehouseService()
    {
        return $this->belongsTo('App\Models\Warehouse\WarehouseService');
    }
}
