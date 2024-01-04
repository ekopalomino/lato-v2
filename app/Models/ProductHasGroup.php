<?php

namespace iteos\Models;

use Illuminate\Database\Eloquent\Model;

class ProductHasGroup extends Model
{
    protected $fillable = [
        'category_id',
        'material_id',
    ];

    public function Parent()
    {
    	return $this->belongsTo(ProductCategory::class,'category_id');
    }

    public function Materials()
    {
        return $this->belongsTo(MaterialGroup::class,'material_id');
    }
}
