<?php

namespace iteos\Exports;

use iteos\Models\Inventory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StockExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Inventory::where('branch_id',auth()->user()->branch_id)->select('id','product_name','warehouse_name','closing_amount')->get();
    }

    public function map($product) : array {
        return [
            $product->product_name,
            $product->warehouse_name,
            $product->closing_amount,
        ] ;
    }

    public function headings(): array
    {
        return [
            'Product Name',
            'Warehouse Name',
            'Amount',
        ];
    }
}
