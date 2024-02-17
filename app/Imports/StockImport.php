<?php

namespace iteos\Imports;

use iteos\Models\Inventory;
use iteos\Models\InventoryMovement;
use iteos\Models\InventoryAdjustment;
use iteos\Models\Reference;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class StockImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function  __construct($ref_id)
    {
        $this->reference_id = $ref_id;
    }
    public function model(array $row)
    {
        $checkInv = Inventory::where('product_name',$row['product_name'])
                               ->where('warehouse_name',$row['warehouse_name'])
                               ->orderBy('updated_at','DESC')
                               ->first();

        $data = Inventory::where('product_name',$row['product_name'])
                            ->where('warehouse_name',$row['warehouse_name'])
                            ->orderBy('updated_at','DESC')
                            ->update([
                                'closing_amount' => $row['amount'],
                            ]);

        $input = InventoryMovement::create([
            'reference_id' => $this->reference_id,
            'type' => '5', 
            'inventory_id' => $checkInv->id,
            'product_id' => $checkInv->product_id,
            'product_name' => $row['product_name'],
            'warehouse_name' => $row['warehouse_name'],
            'incoming' => $row['amount'],
            'remaining' => $row['amount'],
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
}
