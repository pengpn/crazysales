<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseService extends Model
{
    protected $table = 'ss_shipping_warehouse_service';

    public function warehouses()
    {
        return $this->hasMany('App\Models\Warehouse','service_id');
    }
}
