<?php
namespace App\Repositories;

use App\Models\WarehouseService;

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