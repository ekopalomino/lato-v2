<?php

namespace iteos\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryRequest extends Model
{
    protected $fillable = [
        'request_ref',
        'request_name',
        'from_wh',
        'to_wh',
        'status_id',
        'created_by',
        'updated_by',
        'approved_by'
    ];

    public function Author()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function Editor()
    {
        return $this->belongsTo(User::class,'updated_by');
    }

    public function Approval()
    {
        return $this->belongsTo(User::class,'approved_by');
    }

    public function Statuses()
    {
        return $this->belongsTo(Status::class,'status_id');
    }

    public function Child()
    {
        return $this->hasMany(InventoryRequestItem::class);
    }

    public function From()
    {
        return $this->belongsTo(Warehouse::class,'from_wh');
    }

    public function To()
    {
        return $this->belongsTo(Branch::class,'to_wh');
    }
}
