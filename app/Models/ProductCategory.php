<?php

namespace iteos\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = [
        'name',
        'material_group_id',
        'created_by',
        'updated_by',
    ];

    public function Author()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function Editor()
    {
        return $this->belongsTo(User::class,'updated_by');
    }

    public function Materials()
    {
        return $this->belongsTo(MaterialGroup::class,'material_group_id');
    }
}
