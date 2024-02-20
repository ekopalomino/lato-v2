<?php

namespace iteos\Imports;

use iteos\Models\Product;
use Ramsey\Uuid\Uuid;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $data = Product::create([
            'id' => Uuid::uuid4(),
            'sap_code' => $row['sap_code'],
            'name' => $row['name'],
            'category_id' => $row['category_id'],
            'uom_id' => $row['uom_id'], 
            'min_stock' => $row['min_stock'],
            'price' => $row['price'],
            'specification' => $row['specification'],
            'created_by' => Auth()->user()->id,
        ]);
    }

    public function chunkSize(): int
    {
        return 50;
    }

    public function batchSize(): int
    {
        return 50;
    }

    public function rules(): array
    {
        return [
            'sap_code' => 'unique:products,sap_code',
        ];
    }
}
