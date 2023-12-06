<?php

namespace iteos\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialGroup extends Model
{
    protected $fillable = [
        'account_id',
        'material_name',
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

    public function Coas()
    {
        return $this->belongsTo(ChartOfAccount::class,'account_id');
    }
}
