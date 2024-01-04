<?php

namespace iteos\Models;

use Illuminate\Database\Eloquent\Model;

class ReceivePurchaseItem extends Model
{
    protected $fillable = [
    	'receive_id',
        'product_id',
    	'product_name',
        'warehouse_id',
    	'orders',
        'uom_order_id',
    	'received',
    	'damaged',
        'remaining',
        'uom_id',
        'sub_total'
    ];

    public function Parent()
    {
    	return $this->belongsTo(ReceivePurchase::class,'receive_id');
    }

    public function Uoms()
    {
    	return $this->belongsTo(UomValue::class,'uom_id');
    }

    public function UomOrder()
    {
    	return $this->belongsTo(UomValue::class,'uom_order_id');
    }

    public function Warehouses()
    {
    	return $this->belongsTo(Warehouse::class,'warehouse_id');
    }

    public function OrderUom()
    {
    	return $this->belongsTo(UomValue::class,'uom_order_id');
    }

    public function Products()
    {
    	return $this->belongsTo(Product::class,'product_id');
    }
}
