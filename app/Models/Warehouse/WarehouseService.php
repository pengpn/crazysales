<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Model;

class WarehouseService extends Model
{
    protected $table = 'ss_shipping_warehouse_service';

    public function warehouses()
    {
        return $this->hasMany('App\Models\Warehouse\Warehouse','service_id');
    }
}
