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
        return Product::with('categories','branches','locations','departments','author','warranties')->where('deleted_at',NULL)->select('id','sap_code','name','category_id','branch_id',
        'location_id','department_id','price','specification','purchase_date','warranty_period','updated_at','created_by')->get();
    }

    public function map($product) : array {
        return [
            $product->id,
            $product->name,
            $product->categories->name,
            $product->branches->name,
            $product->locations->location_name,
            $product->departments->name,
            $product->price,
            $product->specification,
            Carbon::parse($product->purchase_date)->toFormattedDateString(),
            $product->warranties->warranty_name,
            Carbon::parse($product->updated_at)->toFormattedDateString(),
            $product->author->name,
        ] ;
 
 
    }

    public function headings(): array
    {
        return [
            'Product EPC',
            'Name',
            'Category',
            'Branch',
            'Location',
            'Department',
            'Price',
            'Specification',
            'Purchase Date',
            'Warranty Period',
            'Last Update',
            'Created By'
        ];
    }
}
