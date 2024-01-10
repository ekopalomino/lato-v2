<?php

namespace iteos\Http\Controllers\Apps;

use Illuminate\Http\Request;
use iteos\Http\Controllers\Controller;
use iteos\Models\Sale;
use iteos\Models\Delivery;
use iteos\Models\DeliveryService;
use iteos\Models\Inventory;
use iteos\Models\Purchase;
use iteos\Models\Manufacture;
use iteos\Models\Product;
use iteos\Models\Warehouse;
use iteos\Models\Contact;
use iteos\Models\User;
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
    public function saleTable()
    {
        $getProduct = Product::where('category_id','1')->pluck('name','name')->toArray();
        $getCustomer = Contact::where('type_id','1')->pluck('name','ref_id')->toArray();
        $getSales = User::pluck('name','name')->toArray();
        $getDelivery = Delivery::pluck('do_ref','do_ref')->toArray();
        $getServices = DeliveryService::pluck('delivery_name','id')->toArray();

        return view('apps.pages.saleTable',compact('getProduct','getCustomer','getSales','getServices'));
    }

    public function reportSales(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required',
            'to_date' => 'required',
        ]);

        $customers = $request->input('customer_id');
        $products = $request->input('product_id');
        $sales_ref = $request->input('sales_ref');
        $delivery_ref = $request->input('do_ref');
        $services = $request->input('service_id');

        if($customers == null && $products == null && $sales_ref == null && $delivery_ref == null && $services == null)
        {
            $data = Sale::with('Deliveries')
                      ->where('updated_at','>=',$request->input('from_date'))
                      ->where('updated_at','<=',$request->input('to_date'))
                      ->get();
            
            return view('apps.reports.saleTable',compact('data'));
        } elseif (($customers) == null) {
            $data = Sale::join('deliveries','deliveries.order_ref','sales.order_ref')
                          ->join('delivery_items','delivery_items.delivery_id','deliveries.id')
                          ->where('sales.updated_at','>=',$request->input('from_date'))
                          ->where('sales.updated_at','<=',$request->input('to_date'))
                          ->where('delivery_items.product_name',$products)
                          ->get();
            
        } elseif (($products) == null) {
            $data = Sale::with('Deliveries')
                      ->where('updated_at','>=',$request->input('from_date'))
                      ->where('updated_at','<=',$request->input('to_date'))
                      ->where('client_code',$customers)
                      ->get();
            
        }
        
    }

    public function inventoryTable()
    {
        $getProduct = Product::where('deleted_at',NULL)->pluck('name','name')->toArray();
        $getWarehouse = Warehouse::pluck('name','name')->toArray();

        return view('apps.pages.inventoryTable',compact('getProduct','getWarehouse'));
    }

    public function reportInventory(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required',
            'to_date' => 'required',
            'product' => 'required',
            'location' => 'required',
        ]);

        $getProducts = $request->input('product_id');
        $getWarehouse = $request->input('warehouse_id');

        $data = Inventory::with('Products')
                    ->join('inventory_movements','inventory_movements.inventory_id','inventories.id')
                    ->where('inventories.updated_at','>=',$request->input('from_date'))
                    ->where('inventories.updated_at','<=',$request->input('to_date'))
                    ->groupBy('inventories.product_name')
                    ->selectRaw('sum(distinct(inventories.opening_amount)) as Awal,sum(distinct(inventories.closing_amount)) as Akhir,sum(distinct(inventory_movements.incoming)) as incoming,sum(inventory_movements.outgoing) as outgoing,
                    inventories.product_name')
                    ->get();
        dd($data);
        return view('apps.reports.inventoryTable',compact('data'));
    }

    public function purchaseTable()
    {
        return view('apps.pages.purchaseTable');
    }

    public function reportPurchase(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required',
            'to_date' => 'required',
        ]);

        $data = Purchase::where('updated_at','>=',$request->input('from_date'))
                      ->where('updated_at','<=',$request->input('to_date'))
                      ->get();
        
        return view('apps.show.purchaseTable',compact('data'));
    }

    public function manufactureTable()
    {
        return view('apps.pages.manufactureTable');
    }

    public function reportManufacture(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required',
            'to_date' => 'required',
        ]);
        
        $data = Manufacture::where('updated_at','>=',$request->input('from_date'))
                      ->where('updated_at','<=',$request->input('to_date'))
                      ->get();
        dd($data);
        return view('apps.show.purchaseTable',compact('data'));
    }
}
