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
        $purchases = Purchase::where('status','458410e7-384d-47bc-bdbe-02115adc4449')
                                ->orWhere('status','314f31d1-4e50-4ad9-ae8c-65f0f7ebfc43')
                                ->pluck('order_ref','order_ref')
                                ->toArray();
        
        return view('apps.input.receiptOrderSearch',compact('purchases'));
    }

    public function receiptGet(Request $request)
    {
        $purchases = Purchase::with('purchaseItems')->where('order_ref',$request->input('order_ref'))->first();
        $locations = Warehouse::pluck('name','name')->toArray();
        $uoms = UomValue::pluck('name','id')->toArray();
        
        return view('apps.input.receiptOrder',compact('purchases','locations','uoms'));
    }

    public function receiptStore(Request $request)
    {
        $getMonth = Carbon::now()->month;
        $getYear = Carbon::now()->year;
        $lastOrder = Reference::where('type','11')->where('month',$getMonth)->where('year',$getYear)->count();
        $refs = 'RP/'.str_pad($lastOrder + 1, 4, "0", STR_PAD_LEFT).'/'.'FTI'.'/'.(\GenerateRoman::integerToRoman(Carbon::now()->month)).'/'.(Carbon::now()->year).'';
        
        $received = ReceivePurchase::create([
            'ref_no' => $refs,
            'order_ref' => $request->input('order_ref'),
            'supplier_id' => $request->input('supplier_id'),
            'warehouse' => $request->input('warehouse_name'),
            'status_id' => '314f31d1-4e50-4ad9-ae8c-65f0f7ebfc43',
            'received_by' => auth()->user()->name
        ]);

        $updatePurchase = Purchase::where('order_ref',$request->input('order_ref'))->update([
            'status' => '314f31d1-4e50-4ad9-ae8c-65f0f7ebfc43'
        ]);

        $refs = Reference::create([
            'type' => '11',
            'month' => $getMonth,
            'year' => $getYear,
            'ref_no' => $refs,
        ]);
        $items = $request->product;
        $orders = $request->pesanan;
        $delivered = $request->pengiriman;
        $damaged = $request->rusak;
        $uom = $request->uom_id;
        $harga = $request->price;

        foreach($items as $index=>$item) {
            $bases = UomValue::where('id',$uom[$index])->first();
            if($bases->is_parent == null) {
                $convertion = ($delivered[$index]) * ($bases->value);
                $destroyed = ($damaged) * ($bases->value); 
            } else {
                $convertion = $delivered[$index];
                $destroyed = $damaged[$index];
            }
            $productInventory = Inventory::where('product_name',$item)
                                           ->where('warehouse_name',$request->input('warehouse_name'))
                                           ->orderBy('updated_at','DESC')
                                           ->first();
            $productMovement = InventoryMovement::where('product_name',$item)
                                                  ->where('warehouse_name',$request->input('warehouse_name'))
                                                  ->orderBy('updated_at','DESC')
                                                  ->first();
            $getProductId = Product::where('name',$item)->first();

            $receiveItem = ReceivePurchaseItem::create([
                'receive_id' => $received->id,
                'product_name' => $item,
                'orders' => $orders[$index],
                'received' => $convertion,
                'damaged' => $destroyed,
                'uom_id' => $uom[$index],
                'sub_total' => $harga[$index] * $convertion
            ]); 
            
            if(($productInventory) == null) {
                $receiveInventory = Inventory::create([
                    'product_id' => $getProductId->id,
                    'product_name' => $item,
                    'warehouse_name' => $request->input('warehouse_name'),
                    'min_stock' => '0',
                    'opening_amount' => '0',
                    'closing_amount' => $convertion,
                ]);

                $receiveMovement = InventoryMovement::create([
                    'inventory_id' => $receiveInventory->id,
                    'reference_id' => $request->input('order_ref'),
                    'type' => '3',
                    'product_name' => $item,
                    'warehouse_name' => $request->input('warehouse_name'),
                    'incoming' => $convertion,
                    'outgoing' => '0',
                    'remaining' => $convertion
                ]);

            } else {
                $receiveInventory = $productInventory->update([
                    'closing_amount' => ($productInventory->closing_amount) + ($convertion)
                ]);

                if(($productMovement) == null) {
                    $receiveMovement = InventoryMovement::create([
                        'inventory_id' => $productInventory->id,
                        'reference_id' => $request->input('order_ref'),
                        'type' => '3',
                        'product_name' => $item,
                        'warehouse_name' => $request->input('warehouse_name'),
                        'incoming' => $convertion,
                        'outgoing' => '0',
                        'remaining' => $convertion
                    ]); 
                } else {
                    $receiveMovement = InventoryMovement::create([
                        'inventory_id' => $productInventory->id,
                        'reference_id' => $request->input('order_ref'),
                        'type' => '3',
                        'product_name' => $item,
                        'warehouse_name' => $request->input('warehouse_name'),
                        'incoming' => $convertion,
                        'outgoing' => '0',
                        'remaining' => ($productMovement->remaining) + ($convertion)
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
        $details = ReceivePurchaseItem::where('receive_id',$id)->get();
        $uoms = UomValue::pluck('name','id')->toArray();
        
        return view('apps.edit.receivedOrder',compact('data','details','uoms'));
    }

    public function receiptUpdate(Request $request,$id)
    {
        $getMonth = Carbon::now()->month;
        $getYear = Carbon::now()->year;
        $lastOrder = Reference::where('type','11')->where('month',$getMonth)->where('year',$getYear)->count();
        $ref = 'RP/'.str_pad($lastOrder + 1, 4, "0", STR_PAD_LEFT).'/'.'FTI'.'/'.(\GenerateRoman::integerToRoman(Carbon::now()->month)).'/'.(Carbon::now()->year).'';

        $refs = Reference::create([
            'type' => '11',
            'month' => $getMonth,
            'year' => $getYear,
            'ref_no' => $ref,
        ]);

        $data = ReceivePurchase::find($id);
        $items = $request->product;
        $orders = $request->pesanan;
        $deliveries = $request->pengiriman;
        $accepted = $request->parsial;
        $damaged = $request->rusak;
        $uoms = $request->uom_id;
        $harga = $request->price;
        
        $data = ReceivePurchase::create([
            'ref_no' => $ref,
            'order_ref' => $data->order_ref,
            'supplier_id' => $data->supplier_id,
            'warehouse' => $data->warehouse,
            'status_id' => $data->status_id,
            'received_by' => auth()->user()->name,

        ]);
        foreach($items as $index=>$item) {
            $bases = UomValue::where('id',$uoms[$index])->first();
            if($bases->is_parent == null) {
                $convertion = ($accepted[$index]) * ($bases->value);
                $destroyed = ($damaged) * ($bases->value); 
            } else {
                $convertion = $accepted[$index];
                $destroyed = $damaged[$index];
            }
            $details = ReceivePurchaseItem::where('receive_id',$id)->where('product_name',$item)->first();
            $productInventory = Inventory::where('product_name',$item)
                                           ->where('warehouse_name',$request->input('warehouse_name'))
                                           ->orderBy('updated_at','DESC')
                                           ->first();
            $productMovement = InventoryMovement::where('product_name',$item)
                                                  ->where('warehouse_name',$request->input('warehouse_name'))
                                                  ->orderBy('updated_at','DESC')
                                                  ->first();
            
            $receiveItem = ReceivePurchaseItem::create([
                'receive_id' => $data->id,
                'product_name' => $item,
                'orders' => $orders[$index],
                'received' => $convertion,
                'damaged' => $destroyed,
                'uom_id' => $uoms[$index],
                'sub_total' => ($harga[$index] / $deliveries[$index])*$convertion,
            ]); 

            $updateInventory = $productInventory->update([
                'closing_amount' => ($productInventory->closing_amount) + ($convertion)
            ]);
            $receiveMovement = InventoryMovement::create([
                'inventory_id' => $productInventory->id,
                'reference_id' => $request->input('order_ref'),
                'type' => '3',
                'product_name' => $item,
                'warehouse_name' => $request->input('warehouse_name'),
                'incoming' => $convertion,
                'outgoing' => '0',
                'remaining' => ($productMovement->remaining) + ($convertion)
            ]);
        }
        $log = 'Pembelian '.($data->order_ref).' Berhasil Diupdate';
        \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Pembelian '.($data->order_ref).' Berhasil Diupdate',
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
        
        $result = Product::where('products.name','LIKE','%'.$search.'%')
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
            'from_wh' => auth()->user()->Branches->branch_name,
            'to_wh' => auth()->user()->Warehouses->name,
            'created_by' => auth()->user()->name,
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
                                ->where('warehouse_name',auth()->user()->Branches->branch_name)
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
            } elseif (($getStock->closing_amount) < $quantity[$index]) {
                $notification = array (
                    'message' => 'Stok Produk '.($item).' Di '.(auth()->user()->Branches->branch_name).' Tidak Cukup',
                    'alert-type' => 'error'
                );
                
                return redirect()->route('transfer.index')->with($notification);
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
                $dest = Inventory::where('product_name',$item)->where('warehouse_name',$data->to_wh)->first();
                $source = Inventory::where('product_name',$item)->where('warehouse_name',$data->from_wh)->first();
                $from = InventoryMovement::where('product_name',$item)->where('warehouse_name',$data->from_wh)->orderBy('updated_at','DESC')->first();
                $to = InventoryMovement::where('product_name',$item)->where('warehouse_name',$data->to_wh)->orderBy('updated_at','DESC')->first();
                       
                $items = InternalItems::create([
                    'product_name' => $item,
                    'mutasi_id' => $data->id,
                    'quantity' => $quantity[$index],
                    'uom_id' => $uom[$index],
                ]);
                /* Check if Product Has Inventory In Destination Warehouse */
                if($dest == null) {
                    /* Create Inventory Data In Destination Warehouse*/
                    $dataInvent = Inventory::create([
                        'product_id' => $source->product_id,
                        'product_name' => $item,
                        'warehouse_name' => $data->to_wh,
                        'min_stock' => $refProduct->min_stock,
                        'opening_amount' => '0',
                        'closing_amount' => $convertion,
                    ]);
                    /* Check If Source Warehouse Has Movement */
                    if($from == null) {
                        $outgoing = InventoryMovement::create([
                            'type' => '4',
                            'inventory_id' => $dataInvent->id,
                            'reference_id' => $data->order_ref,
                            'product_name' => $dataInvent->product_name,
                            'warehouse_name' => $data->from_wh,
                            'incoming' => '0',
                            'outgoing' => $convertion,
                            'remaining' => ($source->closing_amount) - ($convertion),
                        ]);
        
                        $incoming = InventoryMovement::create([
                            'type' => '4',
                            'inventory_id' => $dataInvent->id,
                            'reference_id' => $data->order_ref,
                            'product_name' => $dataInvent->product_name,
                            'warehouse_name' => $dataInvent->warehouse_name,
                            'incoming' => $convertion,
                            'outgoing' => '0',
                            'remaining' => $convertion,
                        ]);
                    } else {
                        $outgoing = InventoryMovement::create([
                            'type' => '4',
                            'inventory_id' => $dataInvent->id,
                            'reference_id' => $data->order_ref,
                            'product_name' => $dataInvent->product_name,
                            'warehouse_name' => $data->from_wh,
                            'incoming' => '0',
                            'outgoing' => $convertion,
                            'remaining' => ($source->closing_amount) - ($convertion),
                        ]);
            
                        $incoming = InventoryMovement::create([
                            'type' => '4',
                            'inventory_id' => $dataInvent->id,
                            'reference_id' => $data->order_ref,
                            'product_name' => $dataInvent->product_name,
                            'warehouse_name' => $dataInvent->warehouse_name,
                            'incoming' => $convertion,
                            'outgoing' => '0',
                            'remaining' => $convertion,
                        ]);
                    }
                    /* Update Source Warehouse Stock */
                    $updateInvent = Inventory::where('product_name',$item)->where('warehouse_name',$data->from_wh)->update([
                        'closing_amount' => ($source->closing_amount) - ($convertion),
                    ]);
                } else {
                    /* Update Source Warehouse Stock */
                    $dataInvent = $dest->update([
                        'closing_amount' => ($dest->closing_amount) + ($convertion),
                    ]);
                    if($to == null) {
                        $outgoing = InventoryMovement::create([
                            'type' => '4',
                            'inventory_id' => $dest->id,
                            'reference_id' => $data->order_ref,
                            'product_name' => $dest->product_name,
                            'warehouse_name' => $from->warehouse_name,
                            'incoming' => '0',
                            'outgoing' => $convertion,
                            'remaining' => ($from->remaining) - ($convertion),
                        ]); 

                        $incoming = InventoryMovement::create([
                            'type' => '4',
                            'inventory_id' => $dest->id,
                            'reference_id' => $data->order_ref,
                            'product_name' => $dest->product_name,
                            'warehouse_name' => $dest->warehouse_name,
                            'incoming' => $convertion,
                            'outgoing' => '0',
                            'remaining' => $convertion,
                        ]);
                    } else {
                        $outgoing = InventoryMovement::create([
                            'type' => '4',
                            'inventory_id' => $dest->id,
                            'reference_id' => $data->order_ref,
                            'product_name' => $dest->product_name,
                            'warehouse_name' => $from->warehouse_name,
                            'incoming' => '0',
                            'outgoing' => $convertion,
                            'remaining' => ($from->remaining) - ($convertion),
                        ]);

                        $incoming = InventoryMovement::create([
                            'type' => '4',
                            'inventory_id' => $dest->id,
                            'reference_id' => $data->order_ref,
                            'product_name' => $dest->product_name,
                            'warehouse_name' => $dest->warehouse_name,
                            'incoming' => $convertion,
                            'outgoing' => '0',
                            'remaining' => ($to->remaining) + ($convertion),
                        ]);
                    }
                    
                    $updateInvent = Inventory::where('product_name',$item)->where('warehouse_name',$data->from_wh)->update([
                        'closing_amount' => ($source->closing_amount) - ($convertion),
                    ]);
                }
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

    public function doIndex()
    {
        $data = Delivery::orderBy('created_at','DESC')->get();
        
        return view('apps.pages.deliveryOrder',compact('data'));
    }

    public function doSearch()
    {
        $sales = Sale::where('status_id','458410e7-384d-47bc-bdbe-02115adc4449')
                       ->orWhere('status_id','e3f73f52-00f7-47a6-9831-3a81b36f65e8')
                       ->pluck('order_ref','order_ref')->toArray();
        $services = DeliveryService::pluck('delivery_name','id')->toArray();

        return view('apps.input.deliveryOrderSearch',compact('sales','services'));
    }

    public function doGet(Request $request)
    {
        $sales = Sale::where('order_ref',$request->input('order_ref'))->first();
        $details = SaleItem::join('inventories','inventories.product_id','sale_items.product_id')
                             ->where('sale_items.sales_id',$sales->id)
                             ->where('inventories.warehouse_name','Gudang Utama')
                             ->get();
        $services = DeliveryService::pluck('delivery_name','id')->toArray();
        $uoms = UomValue::pluck('name','id')->toArray();
        
        return view('apps.input.deliveryOrder',compact('sales','details','services','uoms'));
    }

    public function doStore(Request $request)
    {
        /*Create Reference Number Based On Trans Count*/
        $getMonth = Carbon::now()->month;
        $getYear = Carbon::now()->year;
        $latestOrder = Reference::where('type','4')->where('month',$getMonth)->where('year',$getYear)->count();
        $refs = 'DO/'.str_pad($latestOrder + 1, 4, "0", STR_PAD_LEFT).'/'.'FTI'.'/'.(\GenerateRoman::integerToRoman(Carbon::now()->month)).'/'.(Carbon::now()->year).'';
        $getSales = Sale::where('order_ref',$request->input('order_ref'))->first();
        
        /*Create Base Data Point*/
        $orders = Delivery::create([
            'do_ref' => $refs,
            'order_ref' => $request->input('order_ref'),
            'del_service_id' => $request->input('delivery_service'),
            'delivery_cost' => $request->input('delivery_cost'),
            'status_id' => 'c2fdba02-e765-4ee8-8c8c-3073209ddd26',
            'created_by' => auth()->user()->name,
        ]);
        $refs = Reference::create([
            'type' => '4',
            'month' => $getMonth,
            'year' => $getYear,
            'ref_no' => $refs,
        ]);
        $tg = InternalTransfer::create([
            'order_ref' => $request->input('order_ref'),
            'from_wh' => 'Gudang Utama',
            'to_wh' => 'Gudang Pengiriman',
            'status_id' => '314f31d1-4e50-4ad9-ae8c-65f0f7ebfc43',
            'created_by' => auth()->user()->name,
            'updated_by' => auth()->user()->name,
            'received_by' => 'SYSTEM'
        ]);

        /*Get Input Array Table Value*/
        $items = $request->product;
        $quantity_order = $request->pesanan;
        $quantity_shipment = $request->pengiriman;
        $uoms = $request->uom_id;
        $prices = $request->sale_price;
        $totals = $request->sub_total;
        foreach($items as $index=>$item) {
            /* Check UOM Value Convertion */
            $bases = UomValue::where('id',$uoms[$index])->first();
            if($bases->is_parent == null) {
                $shipment = ($quantity_shipment[$index]) * ($bases->value); 
            } else {
                $shipment = $quantity_shipment[$index];
            }
            /* Get Basic Data From Array Input*/
            $inventories = Inventory::where('product_name',$item)->where('warehouse_name','Gudang Utama')->first();
            $lastMove = InventoryMovement::where('product_name',$item)->where('warehouse_name','Gudang Utama')->orderBy('updated_at','DESC')->first();
            $incoming = Inventory::where('product_name',$item)->where('warehouse_name','Gudang Pengiriman')->first();
            $getProductId = Product::where('name',$item)->first();
            
            if($lastMove == null) {
                $tgItems = InternalItems::create([
                    'mutasi_id' => $tg->id,
                    'product_name' => $item,
                    'quantity' => $quantity_shipment[$index],
                    'uom_id' => $uoms[$index],
                ]);

                $orderItems = DeliveryItem::create([
                    'delivery_id' => $orders->id,
                    'product_name' => $item,
                    'quantity' => $quantity_order[$index],
                    'shipping' => $quantity_shipment[$index],
                    'uom_id' => $uoms[$index],
                ]);
            
                $updateInv = $inventories->update([
                    'closing_amount' => ($inventories->closing_amount) - ($quantity_shipment[$index]),
                ]);
                $updateSaleItem = SaleItem::where('sales_id',$getSales->id)->where('product_name',$item)->update([
                    'remain' => ($quantity_order[$index]) - ($quantity_shipment[$index]),
                    'shipping' => $quantity_shipment[$index],
                ]);

                $movements = InventoryMovement::create([
                        'inventory_id' => $inventories->id,
                        'reference_id' => $request->input('order_ref'),
                        'type' => '4',
                        'product_name' => $item,
                        'warehouse_name' => 'Gudang Utama',
                        'incoming' => '0',
                        'outgoing' => $quantity_shipment[$index],
                        'remaining' => $quantity_shipment[$index]
                ]);
            /* Check If Destination Warehouse Exist*/
                if(($incoming) == null) {
                    $makeInv = Inventory::create([
                        'product_id' => $getProductId->id,
                        'product_name' => $item,
                        'warehouse_name' => 'Gudang Pengiriman',
                        'min_stock' => '0',
                        'opening_amount' => '0',
                        'closing_amount' => $quantity_shipment[$index],
                    ]);
                    $makeMove = InventoryMovement::create([
                        'inventory_id' => $makeInv->id,
                        'reference_id' => $request->input('order_ref'),
                        'type' => '4',
                        'product_name' => $item,
                        'warehouse_name' => 'Gudang Pengiriman',
                        'incoming' => $quantity_shipment[$index],
                        'outgoing' => '0',
                        'remaining' => $quantity_shipment[$index]
                    ]);
                } else {
                    $updateInv = $incoming->update([
                        'closing_amount' => ($incoming->closing_amount) + ($quantity_shipment[$index])
                    ]);
                    $makeMove = InventoryMovement::create([
                        'inventory_id' => $incoming->id,
                        'reference_id' => $request->input('order_ref'),
                        'type' => '4',
                        'product_name' => $item,
                        'warehouse_name' => 'Gudang Pengiriman',
                        'incoming' => $quantity_shipment[$index],
                        'outgoing' => '0',
                        'remaining' => $quantity_shipment[$index]
                    ]);
                }
            } else {
                $tgItems = InternalItems::create([
                    'mutasi_id' => $tg->id,
                    'product_name' => $item,
                    'quantity' => $quantity_shipment[$index],
                    'uom_id' => $uoms[$index],
                ]);

                $orderItems = DeliveryItem::create([
                    'delivery_id' => $orders->id,
                    'product_name' => $item,
                    'quantity' => $quantity_order[$index],
                    'shipping' => $quantity_shipment[$index],
                    'uom_id' => $uoms[$index],
                ]);
            
                $updateInv = $inventories->update([
                    'closing_amount' => ($inventories->closing_amount) - ($quantity_shipment[$index]),
                ]);

                $updateSaleItem = SaleItem::where('sales_id',$getSales->id)->where('product_name',$item)->update([
                    'remain' => ($quantity_order[$index]) - ($quantity_shipment[$index]),
                    'shipping' => $quantity_shipment[$index],
                ]);

                $movements = InventoryMovement::create([
                        'inventory_id' => $inventories->id,
                        'reference_id' => $request->input('order_ref'),
                        'type' => '4',
                        'product_name' => $item,
                        'warehouse_name' => 'Gudang Utama',
                        'incoming' => '0',
                        'outgoing' => $quantity_shipment[$index],
                        'remaining' => ($lastMove->remaining) - ($quantity_shipment[$index])
                ]);
            /* Check If Destination Warehouse Exist*/
                if(($incoming) == null) {
                    $makeInv = Inventory::create([
                        'product_id' => $getProductId->id,
                        'product_name' => $item,
                        'warehouse_name' => 'Gudang Pengiriman',
                        'min_stock' => '0',
                        'opening_amount' => '0',
                        'closing_amount' => $quantity_shipment[$index],
                    ]);
                    $makeMove = InventoryMovement::create([
                        'inventory_id' => $makeInv->id,
                        'reference_id' => $request->input('order_ref'),
                        'type' => '4',
                        'product_name' => $item,
                        'warehouse_name' => 'Gudang Pengiriman',
                        'incoming' => $quantity_shipment[$index],
                        'outgoing' => '0',
                        'remaining' => $quantity_shipment[$index]
                    ]);
                } else {
                    $updateInv = $incoming->update([
                        'closing_amount' => ($incoming->closing_amount) + ($quantity_shipment[$index])
                    ]);
                    $makeMove = InventoryMovement::create([
                        'inventory_id' => $incoming->id,
                        'reference_id' => $request->input('order_ref'),
                        'type' => '4',
                        'product_name' => $item,
                        'warehouse_name' => 'Gudang Pengiriman',
                        'incoming' => $quantity_shipment[$index],
                        'outgoing' => '0',
                        'remaining' => $quantity_shipment[$index]
                    ]);
                }
            }
        }

        $salesUpdate = Sale::where('order_ref',$request->input('order_ref'))->update([
            'status_id' => 'c2fdba02-e765-4ee8-8c8c-3073209ddd26',
        ]);

        $log = 'Delivery Order '.($orders->do_ref).' Sedang Diproses';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Delivery Order '.($orders->do_ref).' Sedang Diproses',
            'alert-type' => 'success'
        );
    
        return redirect()->route('delivery.index')->with($notification);
    }

    public function doEdit($id)
    {
        $deliveries = Delivery::find($id);
        
        return view('apps.edit.deliveryOrder',compact('deliveries','items'));
    }

    public function doCancel(Request $request,$id)
    {
        $data = Delivery::find($id);
        $items = DeliveryItem::where('delivery_id',$id)->get();
        foreach($items as $item) {
            $inventories = Inventory::where('product_name',$item->product_name)->where('warehouse_name','Gudang Utama')->first();
            $lastMove = InventoryMovement::where('product_name',$item->product_name)->where('warehouse_name','Gudang Utama')->orderBy('updated_at','DESC')->first();

            $backInventory = $inventories->update([
                'closing_amount' => ($inventories->closing_amount) + ($item->product_shipment),
            ]);

            $backMovement = InventoryMovement::create([
                'inventory_id' => $inventories->id,
                'reference_id' => $data->do_ref,
                'type' => '8',
                'product_name' => $item->product_name,
                'warehouse_name' => 'Gudang Utama',
                'incoming' => $item->product_shipment,
                'outgoing' => '0',
                'remaining' => ($lastMove->remaining) + ($item->product_shipment),
            ]);      
        }

        $sales = Sale::where('order_ref',$data->order_ref)->update([
            'status_id' => '8447cd63-c7e7-4b26-81fc-d2eb3aceec97'
        ]);
        $log = 'Delivery Order '.($data->order_ref).' Berhasil Dibatalkan';
         \LogActivity::addToLog($log);
        $data->update([
            'status_id' => '8447cd63-c7e7-4b26-81fc-d2eb3aceec97'
        ]);
        
        $notification = array (
            'message' => 'Delivery Order '.($data->order_ref).' Berhasil Dibatalkan',
            'alert-type' => 'success'
        );
    
        return redirect()->route('delivery.index')->with($notification);

    }

    public function doShow($id)
    {
        $data = Delivery::find($id);
        $items = DeliveryItem::where('delivery_id',$id)->get();
        
        return view('apps.show.deliveryOrder',compact('items','data'))->renderSections()['content'];
    }

    public function doPrint($id)
    {
        $source = Delivery::find($id);
        $data = Sale::where('order_ref',$source->order_ref)
                        ->first();             
        $details = DeliveryItem::where('delivery_id',$id)->get();
        
        $pdf = PDF::loadview('apps.print.doNew',compact('source','data','details'));
        return $pdf->download(''.$source->do_ref.'.pdf');                 
    }

    public function doReceipt($id)
    { 
        $data = Delivery::find($id);

        return view('apps.edit.deliveryReceipt',compact('data'))->renderSections()['content'];
    }
    public function doDone(Request $request,$id)
    {
        $data = Delivery::find($id);
        $items = DeliveryItem::where('delivery_id',$id)->get();

        if($request->input('delivery_type') == '1') {
            $done = $data->update([
                'receipt' => $request->input('receipt'),
                'status_id' => 'e9395add-e815-4374-8ed3-c0d5f4481ab8',
                'updated_by' => auth()->user()->name,
            ]);
            $sales = Sale::where('order_ref',$data->order_ref)->update([
                'status_id' => 'e9395add-e815-4374-8ed3-c0d5f4481ab8',
            ]);
        } else {
            $done = $data->update([
                'receipt' => $request->input('receipt'),
                'status_id' => 'e3f73f52-00f7-47a6-9831-3a81b36f65e8',
                'updated_by' => auth()->user()->name,
            ]);
            $sales = Sale::where('order_ref',$data->order_ref)->update([
                'status_id' => 'e3f73f52-00f7-47a6-9831-3a81b36f65e8',
            ]);
        }
        
        foreach($items as $item) {
            $inventories = Inventory::where('product_name',$item->product_name)
                                      ->where('warehouse_name','Gudang Pengiriman')
                                      ->orderBy('updated_at','DESC')
                                      ->first();
            $movements = InventoryMovement::where('product_name',$item->product_name)
                                            ->where('warehouse_name','Gudang Pengiriman')
                                            ->orderBy('updated_at','DESC')
                                            ->first();
            $upInv = $inventories->update([
                'closing_amount' => ($inventories->closing_amount) - ($item->product_shipment),
            ]);
            $upMove = InventoryMovement::create([
                'inventory_id' => $inventories->id,
                'reference_id' => $data->do_ref,
                'type' => '5',
                'product_name' => $item->product_name,
                'warehouse_name' => 'Gudang Pengiriman',
                'incoming' => '0',
                'outgoing' => $item->product_shipment,
                'remaining' => ($movements->remaining) - ($item->product_shipment),
            ]);
        }

        

        $log = 'Delivery Order '.($data->order_ref).' Berhasil Dikirimkan';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Delivery Order '.($data->order_ref).' Berhasil Dikirimkan',
            'alert-type' => 'success'
        );
    
        return redirect()->route('delivery.index')->with($notification);
    }

    public function returItem()
    {
        $data = Retur::where('status_id','e9f870d8-ebe8-462e-a6b6-c03f4f5bd8eb')->get();
        return view('apps.pages.saleRetur',compact('data'));
    }

    public function returSearch()
    {
        $sales = Sale::where('status_id','!=','af0e1bc3-7acd-41b0-b926-5f54d2b6c8e8')->pluck('order_ref','order_ref')->toArray();
        $deliveries = Delivery::where('status_id','e9395add-e815-4374-8ed3-c0d5f4481ab8')->pluck('do_ref','do_ref')->toArray();

        return view('apps.input.deliveryReturSearch',compact('sales','deliveries'));
    }

    public function returGet(Request $request) 
    {
        if(($request->input('delivery_order')) == null)
        {
            $findSale = Sale::where('order_ref',$request->input('sales_order'))->first();
            $findDelivery = Delivery::where('order_ref',$request->input('sales_order'))->first();
            $findItem = SaleItem::where('sales_id',$findSale->id)->get();
            $uoms = UomValue::pluck('name','id')->toArray();
            $reasons = ReturReason::pluck('name','id')->toArray();

            return view('apps.input.salesRetur',compact('findSale','findDelivery','findItem','uoms','reasons'));
        } else {
            $findDelivery = Delivery::where('do_ref',$request->input('delivery_order'))->first();
            $findItem = DeliveryItem::where('delivery_id',$findDelivery->id)->get();
            $uoms = UomValue::pluck('name','id')->toArray();
            $reasons = ReturReason::pluck('name','id')->toArray();

            return view('apps.input.deliveryRetur',compact('findDelivery','findItem','uoms','reasons'));
        }
    }

    public function returStore(Request $request)
    {
        $baseData = Retur::create([
            'sales_order' => $request->input('sales_order'),
            'delivery_order' => $request->input('delivery_order'),
            'status_id' => 'e9f870d8-ebe8-462e-a6b6-c03f4f5bd8eb',
            'created_by' => auth()->user()->name,
        ]);

        $getMonth = Carbon::now()->month;
        $getYear = Carbon::now()->year;
        $latestOrder = Reference::where('type','12')->where('month',$getMonth)->where('year',$getYear)->count();
        $ref = 'RET/FTI/'.str_pad($latestOrder + 1, 4, "0", STR_PAD_LEFT).'/'.(\GenerateRoman::integerToRoman(Carbon::now()->month)).'/'.(Carbon::now()->year).'';

        $items = $request->product;
        $pesanan = $request->pesanan;
        $retur_qty = $request->retur_qty;
        $reason = $request->reason;
        $uoms = $request->uom_id;

        foreach($items as $index=>$item)
        {
            $baseInventory = Inventory::where('product_name',$item)->where('warehouse_name','Gudang Retur')->orderBy('updated_at','DESC')->first();
            $baseMovement = InventoryMovement::where('product_name',$item)->where('warehouse_name','Gudang Retur')->orderBy('updated_at','DESC')->first();
            $getProduct = Product::where('name',$item)->first();

            $returItem = ReturItem::create([
                'retur_id' => $baseData->id,
                'product_name' => $item,
                'sales_qty' => $pesanan[$index],
                'retur_qty' => $retur_qty[$index],
                'retur_reason' => $reason[$index],
            ]);
            if(($baseInventory) == null)
            {
                $inventory = Inventory::create([
                    'product_id' => $getProduct->id,
                    'product_name' => $item,
                    'warehouse_name' => 'Gudang Retur',
                    'min_stock' => '0',
                    'opening_amount' => '0',
                    'closing_amount' => $retur_qty[$index],
                ]);

                $movement = InventoryMovement::create([
                    'inventory_id' => $inventory->id,
                    'reference_id' => $ref,
                    'type' => '10',
                    'product_name' => $item,
                    'warehouse_name' => 'Gudang Retur',
                    'incoming' => $retur_qty[$index],
                    'outgoing' => '0',
                    'remaining' => $retur_qty[$index],
                ]);
            } else {
                $inventory = $baseInventory::update([
                    'closing_amount' => ($baseInventory->closing_amount) + ($retur_qty[$index]),
                ]);

                $movement = InventoryMovement::create([
                    'inventory_id' => $baseInventory->id,
                    'reference_id' => $ref,
                    'type' => '10',
                    'product_name' => $item,
                    'warehouse_name' => 'Gudang Retur',
                    'incoming' => $retur_qty[$index],
                    'outgoing' => '0',
                    'remaining' => ($baseMovement->remaining) + ($retur_qty[$index]),
                ]);
            }
        }

        $log = 'Retur '.($data->order_ref).' Berhasil Disimpan';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Retur '.($data->order_ref).' Berhasil Disimpan',
            'alert-type' => 'success'
        );
    
        return redirect()->route('deliveryRetur.index')->with($notification);
    }
}