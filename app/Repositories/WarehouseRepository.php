<?php
namespace App\Repositories;

use App\Models\Warehouse\WarehouseService;

class WarehouseRepository
{
    public function getWarehouseList()
    {
        $warehouses = [];
        $warehouseServices = WarehouseService::with('warehouses')->get();
        foreach ($warehouseServices as $service) {
            $warehouseList = $service->warehouses;
            foreach ($warehouseList as $warehouse) {
                $warehouses[$warehouse->id] = $warehouse;
            }
        }
        return $warehouses;
    }
}