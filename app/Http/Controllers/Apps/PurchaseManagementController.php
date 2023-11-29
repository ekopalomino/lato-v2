<?php

namespace iteos\Http\Controllers\Apps;

use Illuminate\Http\Request;
use iteos\Http\Controllers\Controller;
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
        if(auth()->user()->hasAnyPermission(['Can View All Request']))
        {
            $data = InventoryRequest::where('to_wh',auth()->user()->branch_id)->orderBy('created_at','DESC')->get();
        } else {
            $data = InventoryRequest::where('from_wh',auth()->user()->warehouse_id)->orderBy('created_at','DESC')->get();
        }
        
        return view('apps.pages.request',compact('data'));
    }

    public function requestCreate()
    {
        $uoms = UomValue::pluck('name','id')->toArray();
        $products = Product::where('deleted_at',NULL)->pluck('name','id')->toArray();
        $getMonth = Carbon::now()->month;
        $getYear = Carbon::now()->year;
        $references = Reference::where('type','1')->where('month',$getMonth)->where('year',$getYear)->count();
        $refs = 'REQ/'.auth()->user()->Warehouses->prefix.'/'.str_pad($references + 1, 4, "0", STR_PAD_LEFT).'/'.(\GenerateRoman::integerToRoman(Carbon::now()->month)).'/'.(Carbon::now()->year).'';

        return view('apps.input.request',compact('uoms','products','refs'));
    }

    public function requestStore(Request $request)
    {
        $this->validate($request, [
            'request_name' => 'required|unique:inventory_requests,request_name',
        ]);
        
        $input = [
            'request_ref' => $request->input('request_ref'),
            'request_name' => $request->input('request_name'),
            'from_wh' => auth()->user()->warehouse_id,
            'to_wh' => auth()->user()->branch_id,
            'created_by' => auth()->user()->id,
        ];

        $getMonth = Carbon::now()->month;
        $getYear = Carbon::now()->year;

        $refs = Reference::create([
            'type' => '1',
            'month' => $getMonth,
            'year' => $getYear,
            'ref_no' => $request->input('request_ref')
        ]);
        
        $data = InventoryRequest::create($input);
        $items = $request->product_id;
        $quantity = $request->quantity;
        $uoms = $request->uom_id;
        $request_id = $data->id;
        
        foreach($items as $index=>$item) {
            if (isset($item)) {
                $names = Product::where('id',$item)->first();
                $items = InventoryRequestItem::create([
                    'request_id' => $request_id,
                    'product_name' => $names->name,
                    'request_qty' => $quantity[$index],
                    'uom_id' => $uoms[$index],
                ]);
            } 
        }
        
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
        $data = InventoryRequest::find($id);
        $details = InventoryRequestItem::where('request_id',$id)->get();

        return view('apps.edit.request',compact('data','details'));
    }

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

    public function requestApprove(Request $request,$id)
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
