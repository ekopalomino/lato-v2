<?php

namespace iteos\Exports;

use iteos\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class ProductExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::with('categories','uoms','materials')->where('deleted_at',NULL)->select('id','sap_code','name','category_id','uom_id',
        'min_stock','price','specification','status_id','updated_at','created_by')->get();
    }

    public function map($product) : array {
        return [
            $product->sap_code,
            $product->name,
            $product->categories->name,
            $product->uoms->name,
            $product->min_stock,
            $product->price,
            $product->specification,
            Carbon::parse($product->updated_at)->toFormattedDateString(),
            $product->author->name,
        ] ;
 
 
    }

    public function headings(): array
    {
        return [
            'SAP Code',
            'Name',
            'Category',
            'UOM',
            'Min Stock',
            'Price',
            'Specification',
            'Last Update',
            'Created By'
        ];
    }
}
