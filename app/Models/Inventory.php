<?php

namespace iteos\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'product_id',
        'product_name',
        'warehouse_id',
        'warehouse_name',
        'material_group_id',
        'min_stock',
        'opening_amount',
        'closing_amount',
    ];

    public function Products()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function Locations()
    {
        return $this->belongsTo(Warehouse::class,'warehouse_id');
    }

    public function Materials()
    {
        return $this->belongsTo(MaterialGroup::class,'material_group_id');
    }

    public function Child()
    {
        return $this->hasMany(InventoryMovement::class);
    }
}
