<?php

namespace iteos\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'dept_name',
        'branch_id',
        'created_by',
        'updated_by',
        'deleted_at',
    ];

    public function Author()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function Editor()
    {
        return $this->belongsTo(User::class,'updated_by');
    }

    public function Branches()
    {
        return $this->belongsTo(Branch::class,'branch_id');
    }
}
