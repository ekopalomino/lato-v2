<?php

namespace iteos\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'product_id',
        'product_name',
        'warehouse_name',
        'min_stock',
        'opening_amount',
        'closing_amount',
    ];

    public function Products()
    {
        return $this->belongsTo(Product::class);
    }

    public function Locations()
    {
        return $this->belongsTo(Warehouse::class,'warehouse_id');
    }

    public function Parent()
    {
        return $this->hasMany(InventoryMovement::class);
    }
}
