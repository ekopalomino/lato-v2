<?php

namespace iteos\Models;

use iteos\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class ReceivePurchase extends Model
{
    use Uuid;

    protected $fillable = [
        'ref_no',
    	'order_ref',
    	'total_qty',
        'total_price',
        'status_id',
        'received_by',
    ];

    public $incrementing = false;

    public function Child()
    {
    	return $this->hasMany(ReceivePurchaseItem::class,'receive_id');
    }

    public function Statuses()
    {
        return $this->belongsTo(Status::class,'status_id');
    }
}
