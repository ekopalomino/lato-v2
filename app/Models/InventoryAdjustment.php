<?php

namespace iteos\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryAdjustment extends Model
{
    protected $fillable = [
        'remarks',
        'branch_id',
        'status_id',
        'created_by',
    ];
    
    public function Statuses()
    {
        return $this->belongsTo(Status::class,'status_id');
    }

    public function Branches()
    {
        return $this->belongsTo(Branch::class,'branch_id');
    }
    
    public function Creator()
    {
        return $this->belongsTo(User::class,'created_by');
    }
}
