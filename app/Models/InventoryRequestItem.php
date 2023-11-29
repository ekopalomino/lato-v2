<?php

namespace iteos\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryRequestItem extends Model
{
    protected $fillable = [
        'request_id',
        'product_name',
        'request_qty',
        'received_qty',
        'uom_id'
    ];

    public function Parent()
    {
        return $this->belongsTo(InventoryRequest::class,'request_id');
    }

    public function Uoms()
    {
         return $this->belongsTo(UomValue::class,'uom_id');
    }
}
