<?php

namespace iteos\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $fillable = [
        'purchase_id',
        'account_id',
        'material_group_id',
        'product_name',
        'quantity',
        'received_qty',
        'remaining_qty',
        'uom_id',
        'purchase_price',
        'sub_total',
    ];

    public function Uoms()
    {
         return $this->belongsTo(UomValue::class,'uom_id');
    }

    public function Coas()
    {
        return $this->belongsTo(ChartOfAccount::class,'account_id');
    }

    public function Materials()
    {
        return $this->belongsTo(MaterialGroup::class,'material_group_id');
    }
}
