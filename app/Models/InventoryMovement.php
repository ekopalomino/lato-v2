<?php

namespace iteos\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    protected $fillable = [
        'type',
        'reference_id',
        'inventory_id',
        'product_id',
        'product_name',
        'warehouse_name',
        'incoming',
        'outgoing',
        'remaining',
        'notes',
    ];
    
    public function Items()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function Parent()
    {
        return $this->belongsTo(Inventory::class,'inventory_id');
    }
}
