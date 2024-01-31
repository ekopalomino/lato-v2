<?php

namespace iteos\Http\Controllers\Apps;

use Illuminate\Http\Request;
use iteos\Http\Controllers\Controller;
use iteos\Models\Inventory;
use iteos\Models\InventoryMovement;
use iteos\Models\Product;
use iteos\Models\Warehouse;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ReportManagementController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Can Access Report');
        $this->middleware('permission:Can Create Report', ['only' => ['create','store']]);
    }

    /*----Sales Reports--------------------*/
    public function inventoryTable()
    {
        $getProduct = Product::where('deleted_at',NULL)->get();
        $getLocation = Warehouse::where('deleted_at',NULL)->get();

        return view('apps.pages.reportsInventory',compact('getProduct','getLocation'));
    }

    public function inventoryReport(Request $request)
    {
        $this->validate($request, [
            'product' => 'required',
            'location' => 'required',
            'from_date' => 'required',
            'to_date' => 'after:start_date'
        ]);

        
        $data = InventoryMovement::where('product_id',$request->input('product'))
                                ->where('warehouse_name',$request->input('location'))
                                ->where('updated_at','>=',$request->input('from_date'))
                                ->where('updated_at','<=',$request->input('to_date'))
                                ->get();
        
        return view('apps.reports.inventoryMovement',compact('data'));
    }
    
}
