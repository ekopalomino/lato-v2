<?php

namespace iteos\Models;

use iteos\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Uuid;

    protected $fillable = [
        'sap_code',
        'name',
        'material_group_id',
        'category_id',
        'uom_id',
        'image',
        'min_stock',
        'price',
        'specification',
        'status_id',
        'created_by',
        'updated_by',
        'deleted_at',
    ];

    public $incrementing = false;

    public function Author()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function Editor()
    {
        return $this->belongsTo(User::class,'updated_by');
    }

    public function Categories()
    {
        return $this->belongsTo(ProductCategory::class,'category_id');
    }

    public function Materials()
    {
        return $this->belongsTo(MaterialGroup::class,'material_group_id');
    }

    public function Uoms()
    {
        return $this->belongsTo(UomValue::class,'uom_id');
    }

    public function Invent()
    {
        return $this->hasMany(Inventory::class,'product_id');
    }

    public function Details()
    {
        return $this->hasMany(ProductBom::class,'product_id');
    }
}
