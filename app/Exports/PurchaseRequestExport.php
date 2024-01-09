<?php

namespace iteos\Exports;

use iteos\Models\Purchase;
use iteos\Models\PurchaseItem;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PurchaseRequestExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $id;

    function __construct($id) {
        $this->id = $id;
    }

    public function view(): View
    {
        return view('apps.print.purchaseRequest', [
            'items' => PurchaseItem::where('purchase_id',$this->id)->get()
        ]);
    }
}
