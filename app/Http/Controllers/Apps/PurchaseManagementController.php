<?php

namespace iteos\Http\Controllers\Apps;

use Illuminate\Http\Request;
use iteos\Http\Controllers\Controller;
use iteos\Models\Branch;
use iteos\Models\Purchase;
use iteos\Models\PurchaseItem;
use iteos\Models\InventoryRequest;
use iteos\Models\InventoryRequestItem;
use iteos\Models\Inventory;
use iteos\Models\InventoryMovement;
use iteos\Models\Product;
use iteos\Models\UomValue;
use iteos\Models\Reference;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;
use Auth;
use DB;
use PDF;
use iteos\Exports\PurchaseRequestExport;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseManagementController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Can Access Purchasing');
        $this->middleware('permission:Can Create Purchase', ['only' => ['create','store']]);
        $this->middleware('permission:Can Edit Purchase', ['only' => ['edit','update']]);
        $this->middleware('permission:Can Delete Purchase', ['only' => ['destroy']]);
    }

    public function index()
    {
        $data = Purchase::orderBy('created_at','DESC')->get();

        return view('apps.pages.purchase',compact('data'));
    }

    public function requestCreate()
    {
        if (auth()->user()->hasRole('Administrator')) {
            $getMonth = Carbon::now()->month;
            $getYear = Carbon::now()->year;
            $references = Reference::where('type','2')->where('month',$getMonth)->where('year',$getYear)->count();
            $refs = 'PR/ADM/'.str_pad($references + 1, 4, "0", STR_PAD_LEFT).'/'.(\GenerateRoman::integerToRoman(Carbon::now()->month)).'/'.(Carbon::now()->year).'';
            $uoms = UomValue::pluck('name','id')->toArray();
            $products = DB::table('inventories')
                        ->join('products','products.id','inventories.product_id')->join('warehouses','warehouses.id','inventories.warehouse_id')
                        ->where([
                            ['inventories.closing_amount','=<','inventories.min_stock']
                        ])
                        ->select('products.id as id_product','products.name as product_name','warehouses.id as from_wh_id','warehouses.name as from_wh','warehouses.prefix as wh_code')
                        ->get();

            return view('apps.input.request',compact('uoms','products','refs'));

        } else {
            $uoms = UomValue::pluck('name','id')->toArray();
            $getMonth = Carbon::now()->month;
            $getYear = Carbon::now()->year;
            $references = Reference::where('type','2')->where('month',$getMonth)->where('year',$getYear)->count();
            $refs = 'PR/'.auth()->user()->Branches->prefix.'/'.str_pad($references + 1, 4, "0", STR_PAD_LEFT).'/'.(\GenerateRoman::integerToRoman(Carbon::now()->month)).'/'.(Carbon::now()->year).'';
            $products = DB::table('inventories')
                            ->join('products','products.id','inventories.product_id')->join('warehouses','warehouses.id','inventories.warehouse_id')
                            ->where([
                                ['warehouses.branch_id',auth()->user()->branch_id],
                                ['inventories.closing_amount','=<','inventories.min_stock']
                            ])
                            ->select('products.id as id_product','products.name as product_name','warehouses.id as from_wh_id','warehouses.name as from_wh','warehouses.prefix as wh_code')
                            ->get();
            /* $products = Inventory::join('products','products.id','inventories.product_id')->join('warehouses','warehouses.id','inventories.warehouse_id')->where([
                ['warehouses.branch_id',auth()->user()->branch_id],
                ['inventories.closing_amount','=<','inventories.min_stock']
                ])->get(); */
            
            return view('apps.input.request',compact('uoms','products','refs'));
        }
    }

    public function requestStore(Request $request)
    {
        $this->validate($request, [
            'request_title' => 'required|unique:purchases,request_title',
        ]);
        
        $input = [
            'request_ref' => $request->input('request_ref'),
            'request_title' => $request->input('request_title'),
            'branch_id' => auth()->user()->warehouse_id,
            'created_by' => auth()->user()->id,
        ];
        
        $getMonth = Carbon::now()->month;
        $getYear = Carbon::now()->year;

        $refs = Reference::create([ 
            'type' => '2',
            'month' => $getMonth,
            'year' => $getYear,
            'ref_no' => $request->input('request_ref')
        ]);
        
        $data = Purchase::create($input);
        $items = $request->product_id;
        $quantity = $request->quantity;
        $uoms = $request->uom_id;
        $request_id = $data->id;
        $warehouses = $request->warehouse_id;
        
        foreach($items as $index=>$item) {
            if (isset($item)) {
                $bases = UomValue::where('id',$uoms[$index])->first();
                    if($bases->is_parent == null) {
                        $convertion = ($quantity[$index]) * ($bases->value);
                    } else {
                        $convertion = $quantity[$index];
                    }
                $names = Inventory::where('product_id',$item)->where('warehouse_id',$warehouses[$index])->first();
                $items = PurchaseItem::create([
                    'purchase_id' => $request_id,
                    'account_id' => $names->Materials->account_id,
                    'material_group_id' => $names->Materials->id,
                    'warehouse_id' => $warehouses[$index],
                    'product_id' => $names->product_id,
                    'product_name' => $names->product_name,
                    'quantity' => $quantity[$index],
                    'remaining_qty' => $quantity[$index],
                    'purchase_price' => $names->Products->price,
                    'sub_total' => ($convertion) * ($names->Products->price),
                    'uom_id' => $uoms[$index],
                ]);
            } 
        }
        $qty = PurchaseItem::where('purchase_id',$request_id)->sum('quantity');
        $price = PurchaseItem::where('purchase_id',$request_id)->sum('sub_total');
        
        $purchaseData = DB::table('purchases')
                        ->where('id',$request_id)
                        ->update(['quantity' => $qty, 'total' => $price]);
        
        $log = 'Request '.($data->request_ref).' Created';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Request '.($data->request_ref).' Created',
            'alert-type' => 'success'
        );

        return redirect()->route('request.index')->with($notification);
    }

    public function requestShow($id)
    {
        $data = Purchase::find($id);
        $details = PurchaseItem::where('purchase_id',$id)->get();
        $uoms = UomValue::pluck('name','id')->toArray();
        
        return view('apps.edit.request',compact('data','details','uoms'));
    }

    public function requestExcel(Request $request)
    {
        return Excel::download(new PurchaseRequestExport($request->id), 'purchaseRequest.xlsx');
    }

    public function requestProcess(Request $request,$id)
    {
        $this->validate($request, [
            'status' => 'required',
        ]);
        $process = [
            'status' => $request->input('status'),
            'updated_by' => auth()->user()->id,
        ];

        $data = Purchase::find($id);
        $data->update($process);

        $log = 'Request '.($data->request_ref).' Processed';
        \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Request '.($data->request_ref).' Processed',
            'alert-type' => 'success'
        );

        return redirect()->route('request.index')->with($notification);
        
    }

    /* public function requestProcess(Request $request,$id)
    {
        $data = InventoryRequest::find($id);
        $inventories = Inventory::where('product_name',$data->Parent->product_name)->where('warehouse_name',$data->To->name)->first();
        dd($inventories);
        
        $process = [
            'status_id' => $request->input('status_id'),
            'approve_by' => auth()->user()->id,
        ];

        $updates = InventoryRequest::find($id);
        $updates->update($process);
        if ($updates->status_id == '10') {
            $items = $request->product_id;
            $quantity = $request->quantity;
            $uoms = $request->uom_id;
            $request_id = $data->id;
            
            foreach($items as $index=>$item) {
                if (isset($item)) {
                    $names = Product::where('id',$item)->first();
                    $items = InventoryRequestItem::where('request_id',$id)->update([
                        'received_qty' => $quantity[$index],
                    ]);


                } 
            }
            $log = 'Request '.($updates->request_ref).' Completed';
            \LogActivity::addToLog($log);
            $notification = array (
                'message' => 'Request '.($updates->request_ref).' Completed',
                'alert-type' => 'success'
            );

            return redirect()->route('request.index')->with($notification);
        } elseif ($updates->status_id == '11') {
            $items = $request->product_id;
            $quantity = $request->quantity;
            $uoms = $request->uom_id;
            $request_id = $data->id;
            
            foreach($items as $index=>$item) {
                if (isset($item)) {
                    $names = Product::where('id',$item)->first();
                    $items = InventoryRequestItem::where('request_id',$id)->update([
                        'received_qty' => $quantity[$index],
                    ]);


                } 
            }
            $log = 'Request '.($updates->request_ref).' Completed Partial';
            \LogActivity::addToLog($log);
            $notification = array (
                'message' => 'Request '.($updates->request_ref).' Completed Partial',
                'alert-type' => 'success'
            );

            return redirect()->route('purchase.index')->with($notification);
        } else {
            $log = 'Request '.($updates->request_ref).' On Hold';
            \LogActivity::addToLog($log);
            $notification = array (
                'message' => 'Request '.($updates->request_ref).' On Hold',
                'alert-type' => 'success'
            );
        }
    } */

    public function requestForm($id)
    {
        $purchase = Purchase::find($id);
        $data = PurchaseItem::where('purchase_id',$id)->get();
        $id = Purchase::where('id',$id)->first();
        return view('apps.edit.purchaseApprove',compact('purchase','data','id'));
    }

    public function purchaseShow($id)
    {
        $data = Purchase::find($id);
        $details = PurchaseItem::where('purchase_id',$id)->get();

        return view('apps.show.purchaseOrder',compact('data','details'));
    }

    public function requestApproveOld(Request $request,$id)
    {
        $data = Purchase::find($id);
        $getMonth = Carbon::now()->month;
        $getYear = Carbon::now()->year;
        $references = Reference::where('type','8')->where('month',$getMonth)->where('year',$getYear)->count();
        $reference = Purchase::where('status','!=','8083f49e-f0aa-4094-894f-f64cd2e9e4e9')->count();
        $ref = 'PO/FTI/'.str_pad($reference + 1, 4, "0", STR_PAD_LEFT).'/'.($data->supplier_code).'/'.(\GenerateRoman::integerToRoman(Carbon::now()->month)).'/'.(Carbon::now()->year).'';
        $details = Contact::where('ref_id',$request->input('supplier_code'))->first();
        $process = [
            'status' => '458410e7-384d-47bc-bdbe-02115adc4449',
            'order_ref' => $ref,
            'updated_by' => auth()->user()->name,
        ];

        $refs = Reference::create([
            'type' => '8',
            'month' => $getMonth,
            'year' => $getYear,
            'ref_no' => $ref,
        ]);

        $updates = Purchase::find($id);
        $updates->update($process);
        $log = 'Pengajuan '.($updates->order_ref).' Berhasil Diproses';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Pengajuan '.($updates->order_ref).' Berhasil Diproses',
            'alert-type' => 'success'
        );

        return redirect()->route('purchase.index')->with($notification);
    }

    public function requestRejected($id)
    {
        $data = Purchase::find($id);
        $reject = $data->update([
            'status' => 'af0e1bc3-7acd-41b0-b926-5f54d2b6c8e8',
            'updated_by' => auth()->user()->name,
        ]);
        $log = 'Pengajuan '.($data->order_ref).' Berhasil Ditolak';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Pengajuan '.($data->order_ref).' Berhasil Ditolak',
            'alert-type' => 'success'
        );

        return redirect()->route('purchase.index')->with($notification);
    }

    public function requestPrint($id)
    {
        $data = Purchase::find($id);
        $details = PurchaseItem::where('purchase_id',$id)->get();

        $pdf = PDF::loadview('apps.print.prNew',compact('data','details'))
                    ->setPaper('a4','portrait');
        return $pdf->download(''.$data->order_ref.'.pdf');
    }

    public function purchasePrint($id)
    {
        $data = Purchase::find($id);
        $details = PurchaseItem::where('purchase_id',$id)->get();

        $pdf = PDF::loadview('apps.print.purchaseOrder',compact('data','details'))
                    ->setPaper('a4','portrait');
        return $pdf->download(''.$data->order_ref.'.pdf');
    }

    public function purchaseClose(Request $request,$id)
    {
        $data = Purchase::find($id);
        $data->update([
            'status' => '596ae55c-c0fb-4880-8e06-56725b21f6dc',
            'updated_by' => auth()->user()->name
        ]);
        $log = 'Pembelian '.($data->order_ref).' Selesai Diproses';
        \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Pembelian '.($data->order_ref).' Selesai Diproses',
            'alert-type' => 'success'
        );

        return redirect()->route('purchase.index')->with($notification);
    }
}
