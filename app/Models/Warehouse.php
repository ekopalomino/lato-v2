<?php

namespace iteos\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'material_group_id',
        'branch_id',
        'name',
        'prefix',
        'created_by',
        'updated_by',
        'deleted_at'
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

    public function Materials()
    {
        return $this->belongsTo(MaterialGroup::class,'material_group_id');
    }

    public function Branches()
    {
        return $this->belongsTo(Branch::class,'branch_id');
    }
}
