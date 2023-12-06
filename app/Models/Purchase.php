<?php

namespace iteos\Models;

use iteos\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use Uuid;

    protected $fillable = [
        'request_ref',
        'request_title',
        'request_wh_id',
        'quantity',
        'total',
        'status',
        'created_by',
        'updated_by',
        'received_by',
        'received_at',
    ];

    public $incrementing = false;

    public function Locations()
    {
        return $this->belongsTo(Warehouse::class,'request_wh_id');
    }

    public function Author()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function Editor()
    {
        return $this->belongsTo(User::class,'updated_by');
    }

    public function Receiver()
    {
        return $this->belongsTo(User::class,'received_by');
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function Statuses()
    {
        return $this->belongsTo(Status::class,'status');
    }
}
