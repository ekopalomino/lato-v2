<?php

namespace iteos\Http\Controllers\Apps;

use Illuminate\Http\Request;
use iteos\Http\Controllers\Controller;
use iteos\Models\Product;
use iteos\Models\Inventory;
use iteos\Models\InventoryMovement;
use iteos\Models\Warehouse;
use iteos\Models\UserWarehouse;
use iteos\Models\InternalTransfer;
use iteos\Models\InternalItems;
use iteos\Models\Purchase;
use iteos\Models\PurchaseItem;
use iteos\Models\ReceivePurchase;
use iteos\Models\ReceivePurchaseItem;
use iteos\Models\Delivery;
use iteos\Models\DeliveryService;
use iteos\Models\DeliveryItem;
use iteos\Models\Sale;
use iteos\Models\SaleItem;
use iteos\Models\Retur;
use iteos\Models\ReturItem;
use iteos\Models\ReturReason;
use iteos\Models\UomValue;
use iteos\Models\Contact;
use iteos\Models\Reference;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;
use Auth;
use PDF;

class InventoryManagementController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:Can Access Inventories');
         $this->middleware('permission:Can Create Inventory', ['only' => ['create','store']]);
         $this->middleware('permission:Can Edit Inventory', ['only' => ['edit','update']]);
         $this->middleware('permission:Can Delete Inventory', ['only' => ['destroy']]);
    }

    public function inventoryIndex()
    {
        $data = Inventory::where('warehouse_name','!=','Gudang Pengiriman')
                           ->orderBy('id','asc')
                           ->get();
        
        return view('apps.pages.inventories',compact('data'));
    }

    public function stockCard(Request $request,$id)
    {
        $source = Inventory::where('id',$id)->first();
        $data = InventoryMovement::where('inventory_id',$source->id)->paginate(10);
        
        return view('apps.show.stockCard',compact('data'))->renderSections()['content'];
    }

    public function stockPrint(Request $request,$id)
    {
        $source = Inventory::where('id',$id)->first();
        $data = InventoryMovement::where('product_name',$source->product_name)
                                ->where('warehouse_name',$source->warehouse_name)
                                ->get();
        $filename = Product::where('id',$source->product_id)->first();
        
        $pdf = PDF::loadview('apps.print.stockCard',compact('data','source'));
        return $pdf->download('Stock Card '.$filename->name.'.pdf');
    }

    public function inventoryAdjustIndex()
    {
        $data = Inventory::orderBy('id','asc')->get();
       
        return view('apps.pages.inventoryAdjustment',compact('data'));
    }

    public function makeAdjust($id)
    {
        $data = Inventory::find($id);
 
        return view('apps.edit.makeAdjust',compact('data'))->renderSections()['content'];
    }

    public function storeAdjust(Request $request,$id)
    {
        $this->validate($request, [
            'notes' => 'required',
        ]);
        $getMonth = Carbon::now()->month;
        $getYear = Carbon::now()->year;
        $latestOrder = Reference::where('type','2')->where('month',$getMonth)->where('year',$getYear)->count();
        $ref = 'ADJ/FTI/'.str_pad($latestOrder + 1, 6, "0", STR_PAD_LEFT).'/'.(\GenerateRoman::integerToRoman(Carbon::now()->month)).'/'.(Carbon::now()->year).'';
        $refs = Reference::create([
            'type' => '2',
            'month' => $getMonth,
            'year' => $getYear,
            'ref_no' => $ref,
        ]);
        
        $checkInv = Inventory::where('product_name',$request->input('product_name'))
                               ->where('warehouse_name',$request->input('warehouse_name'))
                               ->orderBy('updated_at','DESC')
                               ->first();
        $checkMove = InventoryMovement::where('product_name',$request->input('product_name'))
                                        ->where('warehouse_name',$request->input('warehouse_name'))
                                        ->orderBy('updated_at','DESC')
                                        ->first();
        
        if(($checkMove) == null) {
            if(($request->input('plus_amount')) == null) {
                $input = [
                    'reference_id' => $ref,
                    'type' => '1', 
                    'inventory_id' => $id,
                    'product_id' => $request->input('product_id'),
                    'product_name' => $request->input('product_name'),
                    'warehouse_name' => $request->input('warehouse_name'),
                    'incoming' => '0',
                    'outgoing' => $request->input('min_amount'),
                    'remaining' => $checkInv->closing_amount - $request->input('min_amount'),
                    'notes' => $request->input('notes'),
                ];
                $movements = InventoryMovement::create($input);
                $source = Inventory::where('id',$id)->update([
                    'closing_amount' => $movements->remaining,
                ]);
                
                $log = 'Stok '.($movements->product_name).' Berhasil Disesuaikan';
                \LogActivity::addToLog($log);
                $notification = array (
                    'message' => 'Stok '.($movements->product_name).' Berhasil Disesuaikan',
                    'alert-type' => 'success'
                );
    
                return redirect()->route('inventory.adjust')->with($notification);
            } elseif (($request->input('min_amount')) == null) {
                $input = [
                    'reference_id' => $ref,
                    'type' => '1', 
                    'inventory_id' => $id,
                    'product_id' => $request->input('product_id'),
                    'product_name' => $request->input('product_name'),
                    'warehouse_name' => $request->input('warehouse_name'),
                    'incoming' => $request->input('plus_amount'),
                    'outgoing' => '0',
                    'remaining' => $checkInv->closing_amount + $request->input('plus_amount'),
                    'notes' => $request->input('notes'),
                ];
                $movements = InventoryMovement::create($input);
                $source = Inventory::where('id',$id)->update([
                    'closing_amount' => $movements->remaining,
                ]);
                
                $log = 'Stok '.($movements->product_name).' Berhasil Disesuaikan';
                \LogActivity::addToLog($log);
                $notification = array (
                    'message' => 'Stok '.($movements->product_name).' Berhasil Disesuaikan',
                    'alert-type' => 'success'
                );
    
                return redirect()->route('inventory.adjust')->with($notification);
            }
        } else {
            if(($request->input('plus_amount')) == null) {
                $input = [
                    'reference_id' => $ref,
                    'type' => '1', 
                    'inventory_id' => $id,
                    'product_id' => $request->input('product_id'),
                    'product_name' => $request->input('product_name'),
                    'warehouse_name' => $request->input('warehouse_name'),
                    'incoming' => '0',
                    'outgoing' => $request->input('min_amount'),
                    'remaining' => ($checkMove->remaining) - ($request->input('min_amount')),
                    'notes' => $request->input('notes'),
                ];
                $movements = InventoryMovement::create($input);
                $source = Inventory::where('id',$id)->update([
                    'closing_amount' => ($checkInv->closing_amount) - ($movements->outgoing),
                ]);
                
                $log = 'Stok '.($movements->product_name).' Berhasil Disesuaikan';
                \LogActivity::addToLog($log);
                $notification = array (
                    'message' => 'Stok '.($movements->product_name).' Berhasil Disesuaikan',
                    'alert-type' => 'success'
                );
    
                return redirect()->route('inventory.adjust')->with($notification);
            } elseif (($request->input('min_amount')) == null) {
                $input = [
                    'reference_id' => $ref,
                    'type' => '1', 
                    'inventory_id' => $id,
                    'product_id' => $request->input('product_id'),
                    'product_name' => $request->input('product_name'),
                    'warehouse_name' => $request->input('warehouse_name'),
                    'incoming' => $request->input('plus_amount'),
                    'outgoing' => '0',
                    'remaining' => ($checkMove->remaining) + ($request->input('plus_amount')),
                    'notes' => $request->input('notes'),
                ];
                $movements = InventoryMovement::create($input);
                $source = Inventory::where('id',$id)->update([
                    'closing_amount' => ($checkInv->closing_amount) + ($movements->incoming),
                ]);
                
                $log = 'Stok '.($movements->product_name).' Berhasil Disesuaikan';
                \LogActivity::addToLog($log);
                $notification = array (
                    'message' => 'Stok '.($movements->product_name).' Berhasil Disesuaikan',
                    'alert-type' => 'success'
                );
    
                return redirect()->route('inventory.adjust')->with($notification);
            }
        }
    }

    public function receiptIndex() 
    {
        $data = ReceivePurchase::orderBy('updated_at','DESC')->get();

        return view('apps.pages.purchaseReceipt',compact('data'));
    }

    public function receiptSearch()
    {
        $purchases = Purchase::where('status','13')
                                ->orWhere('status','6')
                                ->pluck('request_ref','id')
                                ->toArray();
        
        return view('apps.input.receiptOrderSearch',compact('purchases'));
    }

    public function receiptGet(Request $request)
    {
        $purchases = Purchase::with('purchaseItems')->where('id',$request->input('request_ref'))->first();
        $uoms = UomValue::where('is_parent','1')->pluck('name','id')->toArray();
        
        return view('apps.input.receiptOrder',compact('purchases','uoms'));
    }

    public function receiptStore(Request $request)
    {
        $getMonth = Carbon::now()->month;
        $getYear = Carbon::now()->year;
        $lastOrder = Reference::where('type','11')->where('month',$getMonth)->where('year',$getYear)->count();
        $refs = 'GR/'.str_pad($lastOrder + 1, 4, "0", STR_PAD_LEFT).'/'.'FTI'.'/'.(\GenerateRoman::integerToRoman(Carbon::now()->month)).'/'.(Carbon::now()->year).'';
        
        $received = ReceivePurchase::create([
            'ref_no' => $refs,
            'order_ref' => $request->input('request_ref'),
            'status_id' => '6',
            'received_by' => auth()->user()->id
        ]);

        $updatePurchase = Purchase::where('request_ref',$request->input('request_ref'))->update([
            'status' => '6'
        ]);

        $refs = Reference::create([
            'type' => '11',
            'month' => $getMonth,
            'year' => $getYear,
            'ref_no' => $refs,
        ]);
        $items = $request->product_id;
        $products = $request->product;
        $orders = $request->pesanan;
        $delivered = $request->pengiriman;
        $damaged = $request->rusak;
        $uom = $request->uom_id;
        $uom_base = $request->uom_order;
        $harga = $request->price;
        $warehouses = $request->warehouse;
        $wh = $request->warehouse_id;

        foreach($items as $index=>$item) {
            $bases = UomValue::where('id',$uom_base[$index])->first();
            if($bases->is_parent == null) {
                $convertion = ($orders[$index]) * ($bases->value);
            } else {
                $convertion = $orders[$index];
            }
            $productInventory = Inventory::where('product_id',$item)
                                           ->where('warehouse_id',$wh[$index])
                                           ->orderBy('updated_at','DESC')
                                           ->first();
            $productMovement = InventoryMovement::where('product_id',$item)
                                                  ->where('warehouse_name',$warehouses[$index])
                                                  ->orderBy('updated_at','DESC')
                                                  ->first();
            $getProductId = Product::where('id',$item)->first();

            $receiveItem = ReceivePurchaseItem::create([
                'receive_id' => $received->id,
                'product_id' => $item,
                'product_name' => $products[$index],
                'warehouse_id' => $wh[$index],
                'orders' => $orders[$index],
                'uom_order_id' => $uom_base[$index],
                'received' => $delivered[$index],
                'damaged' => $damaged[$index],
                'remaining' => ($convertion - ($delivered[$index] + $damaged[$index])),
                'uom_id' => $uom[$index],
                'sub_total' => $harga[$index] * $delivered[$index]
            ]); 
            
            if(($productInventory) == null) {
                $receiveInventory = Inventory::create([
                    'product_id' => $item,
                    'product_name' => $products[$index],
                    'warehouse_name' => $warehouses[$index],
                    'min_stock' => '0',
                    'opening_amount' => '0',
                    'closing_amount' => $delivered[$index],
                ]);

                $receiveMovement = InventoryMovement::create([
                    'inventory_id' => $receiveInventory->id,
                    'reference_id' => $request->input('request_ref'),
                    'type' => '3',
                    'product_id' => $item,
                    'product_name' => $products[$index],
                    'warehouse_name' => $warehouses[$index],
                    'incoming' => $delivered[$index],
                    'outgoing' => '0',
                    'remaining' => $delivered[$index]
                ]);

            } else {
                $receiveInventory = $productInventory->update([
                    'closing_amount' => ($productInventory->closing_amount) + ($delivered[$index])
                ]);

                if(($productMovement) == null) {
                    $receiveMovement = InventoryMovement::create([
                        'inventory_id' => $productInventory->id,
                        'reference_id' => $request->input('request_ref'),
                        'type' => '3',
                        'product_id' => $item,
                        'product_name' => $products[$index],
                        'warehouse_name' => $warehouses[$index],
                        'incoming' => $delivered[$index],
                        'outgoing' => '0',
                        'remaining' => $delivered[$index]
                    ]); 
                } else {
                    $receiveMovement = InventoryMovement::create([
                        'inventory_id' => $productInventory->id,
                        'reference_id' => $request->input('request_ref'),
                        'type' => '3',
                        'product_name' => $products[$index],
                        'product_id' => $item,
                        'warehouse_name' => $warehouses[$index],
                        'incoming' => $delivered[$index],
                        'outgoing' => '0',
                        'remaining' => ($productMovement->remaining) + ($delivered[$index])
                    ]);
                }
            }                                       
        }
        $log = 'Pembelian '.($received->order_ref).' Berhasil Diterima';
        \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Pembelian '.($received->order_ref).' Berhasil Diterima',
            'alert-type' => 'success'
        );
    
        return redirect()->route('receipt.index')->with($notification);
    }

    public function receiptEdit($id)
    {
        $data = ReceivePurchase::find($id);
        $details = ReceivePurchaseItem::where('receive_id',$id)->where('remaining','>','0')->get();
        $uoms = UomValue::where('is_parent','1')->pluck('name','id')->toArray();
        
        return view('apps.edit.receivedOrder',compact('data','details','uoms'));
    }

    public function receiptUpdate(Request $request,$id)
    {
        $data = ReceivePurchase::find($id);
        $items = $request->product_id;
        $products = $request->product;
        $orders = $request->pesanan;
        $deliveries = $request->pengiriman;
        $accepted = $request->parsial;
        $damaged = $request->rusak;
        $uoms = $request->uom_id;
        $harga = $request->price;
        $warehouses = $request->wh_id;
        
        
        foreach($items as $index=>$item) {
            $bases = UomValue::where('id',$uoms[$index])->first();
            if($bases->is_parent == null) {
                $convertion = ($orders[$index]) * ($bases->value);
                $destroyed = ($damaged) * ($bases->value); 
            } else {
                $convertion = $orders[$index];
                $destroyed = $damaged[$index];
            }
            $details = ReceivePurchaseItem::where('receive_id',$id)->where('warehouse_id',$warehouses[$index])->first();
            $productInventory = Inventory::where('product_id',$item)
                                           ->where('warehouse_id',$warehouses[$index])
                                           ->orderBy('updated_at','DESC')
                                           ->first();
            $productMovement = InventoryMovement::where('product_id',$item)
                                                  ->where('warehouse_name',$productInventory->warehouse_name)
                                                  ->orderBy('updated_at','DESC')
                                                  ->first();
                                                  
            $updateReceive = $details->update([
                                'received' => $accepted[$index],
                                'damaged' => $damaged[$index],
                                'remaining' => $details->remaining - $accepted[$index],
                                'uom_id' => $uoms[$index],
                            ]);
            $receiveMovement = InventoryMovement::create([
                                'inventory_id' => $productInventory->id,
                                'reference_id' => $request->input('order_ref'),
                                'type' => '3',
                                'product_id' => $item,
                                'product_name' => $products[$index],
                                'warehouse_name' => $productInventory->warehouse_name,
                                'incoming' => $accepted[$index],
                                'outgoing' => '0',
                                'remaining' => ($accepted[$index] + $productMovement->remaining)
                            ]);
            $updateStock = $productInventory->update([
                'closing_amount' => $receiveMovement->remaining,
            ]);

            $updateStatus = Purchase::where('request_ref',$data->order_ref)->first();
            $updateStatus->update([
                'status' => '6'
            ]);
        }
        $log = 'Goods Receipt '.($data->order_ref).' Updated';
        \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Goods Receipt '.($data->order_ref).' Updated',
            'alert-type' => 'success'
        );
    
        return redirect()->route('receipt.index')->with($notification);
    }

    public function receiptClose(Request $request,$id)
    {
        $data = ReceivePurchase::find($id);
        $purchases = Purchase::where('order_ref',$data->order_ref)->first();

        $updateData = $data->update([
            'status_id' => '596ae55c-c0fb-4880-8e06-56725b21f6dc'
        ]);
        $updatePurchase = $purchases->update([
            'status' => '596ae55c-c0fb-4880-8e06-56725b21f6dc'
        ]); 

        $log = 'Pembelian '.($data->order_ref).' Selesai Diproses';
        \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Pembelian '.($data->order_ref).' Selesai Diproses',
            'alert-type' => 'success'
        );

        return redirect()->route('receipt.index')->with($notification);
    }

    public function internTransfer()
    {
        $data = InternalTransfer::orderBy('created_at','DESC')->get();
       
        return view('apps.pages.internalTransfer',compact('data'));
    }

    public function searchProduct(Request $request)
    {
        $search = $request->get('product');
        
        $result = Product::join('inventories','inventories.product_id','products.id')->where('products.name','LIKE','%'.$search.'%')
                            ->where('inventories.warehouse_id',auth()->user()->warehouse_id)
                            ->select('products.name','products.name')
                            ->get();
        
        return response()->json($result);
    } 

    public function addTransfer()
    {
        $uoms = UomValue::pluck('name','id')->toArray();
        $getMonth = Carbon::now()->month;
        $getYear = Carbon::now()->year;
        $references = Reference::where('type','1')->where('month',$getMonth)->where('year',$getYear)->count();
        $refs = 'REQ/'.auth()->user()->Warehouses->prefix.'/'.str_pad($references + 1, 4, "0", STR_PAD_LEFT).'/'.(\GenerateRoman::integerToRoman(Carbon::now()->month)).'/'.(Carbon::now()->year).'';
        
        return view('apps.input.internalTransfer',compact('uoms','refs'));
    }

    public function internStore(Request $request)
    { 
        //Get All Data From Submitted Form//
        $items = $request->product;
        $quantity = $request->quantity;
        $uom = $request->uom_id;

        $getMonth = Carbon::now()->month;
        $getYear = Carbon::now()->year;
        
        $data = InternalTransfer::create([
            'order_ref' => $request->input('ref_id'),
            'to_wh' => auth()->user()->Warehouses->name,
            'created_by' => auth()->user()->id,
        ]);
        $refs = Reference::create([
                'type' => '1',
                'month' => $getMonth,
                'year' => $getYear,
                'ref_no' => $request->input('ref_id'),
            ]);

        foreach($items as $index=>$item) {
            /* Check Source Warehouse Stock */
            $getStock = Inventory::where('product_name',$item)
                                ->where('warehouse_id',auth()->user()->warehouse_id)
                                ->orderBy('updated_at','DESC')
                                ->first();
            
            /* Delete Transfer If No Stock Available */
            if ($getStock == NULL ) {
                $d = Reference::where('ref_no',$data->order_ref)->first();
                
                $d->delete();
                $s = InternalTransfer::find($data->id);
                $s->delete();
                $notification = array (
                    'message' => 'No Product Found, Please Try Again',
                    'alert-type' => 'error'
                );
                $data->delete();

                return back()->with($notification);
            } else if (($getStock->closing_amount) < $quantity[$index]) {
                $d = Reference::where('ref_no',$data->order_ref)->first();
                
                $d->delete();
                $s = InternalTransfer::find($data->id);
                $s->delete();
                $notification = array (
                    'message' => 'Stok Produk '.($item).' Di '.(auth()->user()->Branches->branch_name).' Tidak Cukup',
                    'alert-type' => 'error'
                );
                $data->delete();

                return back()->with($notification);
            } else {
                //Check UOM Value//
                $bases = UomValue::where('id',$uom[$index])->first();
                if($bases->is_parent == null) {
                    $convertion = ($quantity[$index]) * ($bases->value); 
                } else {
                    $convertion = $quantity[$index];
                }
                //Get Reference Value From Product//
                $refProduct = Product::where('name',$item)->first();
                /* Base Query */ 
                $source = Inventory::where('product_name',$item)->where('warehouse_id',auth()->user()->warehouse_id)->first();
                $to = InventoryMovement::where('product_name',$item)->where('warehouse_name',$data->to_wh)->orderBy('updated_at','DESC')->first();
                dd($to);

                $items = InternalItems::create([
                    'product_name' => $item,
                    'mutasi_id' => $data->id,
                    'quantity' => $quantity[$index],
                    'uom_id' => $uom[$index],
                ]);

                $outgoing = InventoryMovement::create([
                    'type' => '4',
                    'inventory_id' => $source->id,
                    'reference_id' => $data->order_ref,
                    'product_id' => $source->product_id,
                    'product_name' => $source->product_name,
                    'warehouse_name' => $data->to_wh,
                    'incoming' => '0',
                    'outgoing' => $convertion,
                    'remaining' => ($to->remaining) - ($convertion),
                ]);

                $updateInvent = Inventory::where('product_name',$item)->where('warehouse_name',$data->to_wh)->update([
                    'closing_amount' => ($source->closing_amount) - ($convertion),
                ]);
            } 
        }
        $log = 'Internal Transfer '.($data->order_ref).' Berhasil Dibuat';
            \LogActivity::addToLog($log);
            $notification = array (
                'message' => 'Internal Transfer '.($data->order_ref).' Berhasil Dibuat',
                'alert-type' => 'success'
            );
                
            return redirect()->route('transfer.index')->with($notification);
    }

    public function transferView($id)
    {
        $source = InternalTransfer::find($id);
        $details = InternalItems::where('mutasi_id',$id)->get();
        
        return view('apps.show.internalTransfer',compact('details'))->renderSections()['content'];
    }

    public function transferAccept(Request $request,$id)
    {
        $data = InternalTransfer::find($id);
        $accept = $data->update([
            'status_id' => '314f31d1-4e50-4ad9-ae8c-65f0f7ebfc43',
            'updated_by' => auth()->user()->name,
        ]);
        $log = 'Internal Transfer '.($data->order_ref).' Berhasil Diterima';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Internal Transfer '.($data->order_ref).' Berhasil Diterima',
            'alert-type' => 'success'
        );

        return redirect()->route('transfer.index')->with($notification);
    }
}