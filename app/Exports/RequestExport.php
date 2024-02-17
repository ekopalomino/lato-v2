<?php

namespace iteos\Exports;

use iteos\Models\Inventory;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromQuery;


class RequestExport implements FromQuery
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        return Inventory::join('products','products.id','inventories.product_id')->join('warehouses','warehouses.id','inventories.warehouse_id')
        ->where('inventories.closing_amount','<=','inventories.min_stock')
        ->select('products.id as id_product','products.name as product_name','warehouses.id as from_wh_id','warehouses.name as from_wh','warehouses.prefix as wh_code','inventories.closing_amount');
    }
}
