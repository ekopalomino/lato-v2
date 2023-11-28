<?php

namespace iteos\Http\Controllers\Apps;

use Illuminate\Http\Request;
use iteos\Http\Controllers\Controller;
use iteos\Models\Product;
use iteos\Models\ProductCategory;
use iteos\Models\UomValue;
use iteos\Models\Warehouse;
use iteos\Models\Inventory;
use Carbon\Carbon;
use Auth;
use PDF;
use File;

class ProductManagementController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Can Access Products');
        $this->middleware('permission:Can Create Product', ['only' => ['create','store']]);
        $this->middleware('permission:Can Edit Product', ['only' => ['edit','update']]);
        $this->middleware('permission:Can Delete Product', ['only' => ['destroy']]);
    }

    public function categoryIndex()
    {
        $data = ProductCategory::orderBy('name','asc')->get();

        return view('apps.pages.productCategory',compact('data'));
    }

    public function categoryStore(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:product_categories,name',
        ]);

        $input = [
            'name' => $request->input('name'),
            'created_by' => auth()->user()->id,
        ];
        $data = ProductCategory::create($input);
        $log = 'Category '.($data->name).' Created';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Category '.($data->name).' Created',
            'alert-type' => 'success'
        );

        return redirect()->route('product-cat.index')->with($notification);
    }

    public function categoryEdit($id)
    {
        $data = ProductCategory::find($id);

        return view('apps.edit.productCategory',compact('data'))->renderSections()['content'];
    }

    public function categoryUpdate(Request $request,$id)
    {
        $this->validate($request, [
            'name' => 'required|unique:product_categories,name',
        ]);

        $input = [
            'name' => $request->input('name'),
            'updated_by' => auth()->user()->id,
        ];
        $data = ProductCategory::find($id);
        $data->update($input);
        $log = 'Category '.($input->name).' Updated';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Category '.($input->name).' Updated',
            'alert-type' => 'success'
        );

        return redirect()->route('product-cat.index')->with($notification);
    }

    public function categoryDestroy($id)
    {
        $data = ProductCategory::find($id);
        $destroy = [
            'deleted_at' => Carbon::now()->toDateTimeString(),
            'updated_by' => auth()->user()->id,
        ];
        $data->update($destroy);
        $log = 'Category '.($data->name).' Deleted';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Category '.($data->name).' Deleted',
            'alert-type' => 'success'
        );

        return redirect()->route('product-cat.index')->with($notification);
    }

    public function productIndex()
    {
    	$data = Product::orderBy('name','asc')->get();

    	return view('apps.pages.products',compact('data'));
    }

    public function productCreate()
    {
        $categories = ProductCategory::pluck('name','id')->toArray();
        $uoms = UomValue::pluck('name','id')->toArray();
         
        return view('apps.input.products',compact('categories','uoms'));
    }

    public function productStore(Request $request)
    {
        $this->validate($request, [
            'sap_code' => 'required|unique:products,sap_code',
            'name' => 'required|unique:products,name',
            'category_id' => 'required',
            'uom_id' => 'required',
            'image' => 'nullable|file|image',
            'min_stock' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file_name = $file->getClientOriginalName();
            $size = $file->getSize();
            $ext = $file->getClientOriginalExtension();
            $destinationPath = 'products';
            $extension = $file->getClientOriginalExtension();
            $filename=$file_name.'product.'.$extension;
            $uploadSuccess = $request->file('image')
            ->move($destinationPath, $filename);

            $input = [ 
                'sap_code' => $request->input('sap_code'),
                'name' => $request->input('name'),
                'category_id' => $request->input('category_id'),
                'uom_id' => $request->input('uom_id'),
                'image' => $filename,
                'min_stock' => $request->input('min_stock'),
                'price' => $request->input('price'),
                'specification' => $request->input('specification'),
                'created_by' => auth()->user()->id,
            ];
        } else {
            $input = [
                'sap_code' => $request->input('sap_code'),
                'name' => $request->input('name'),
                'category_id' => $request->input('category_id'),
                'uom_id' => $request->input('uom_id'),
                'min_stock' => $request->input('min_stock'),
                'price' => $request->input('price'),
                'specification' => $request->input('specification'),
                'created_by' => auth()->user()->id,
            ];
        }
        
        $data = Product::create($input);
        $stocks = Inventory::create([
            'product_id' => $data->id,
            'product_name' => $data->name,
            'warehouse_name' => auth()->user()->Warehouses->name,
            'min_stock' => $data->min_stock,
            'opening_amount' => '0',
            'closing_amount' => '0', 
        ]);
        $log = 'Product '.($data->name).' Created';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Product '.($data->name).' Created',
            'alert-type' => 'success'
        );

        return redirect()->route('product.index')->with($notification);
    }

    public function productEdit($id)
    {
        $data = Product::find($id);
        $categories = ProductCategory::pluck('name','id')->toArray();
        $uoms = UomValue::pluck('name','id')->toArray();
        $locations = Warehouse::pluck('name','id')->toArray();

        return view('apps.edit.products',compact('data','categories','uoms','locations'));
    }

    public function productUpdate(Request $request,$id)
    {
        $this->validate($request, [
            'sap_code' => 'required',
            'name' => 'required|unique:products,name',
            'category_id' => 'required',
            'uom_id' => 'required',
            'image' => 'nullable|file|image',
            'min_stock' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file_name = $file->getClientOriginalName();
            $size = $file->getSize();
            $ext = $file->getClientOriginalExtension();
            $destinationPath = 'products';
            $extension = $file->getClientOriginalExtension();
            $filename=$file_name.'product.'.$extension;
            $uploadSuccess = $request->file('image')
            ->move($destinationPath, $filename);

            $input = [ 
                'sap_code' => $request->input('sap_code'),
                'name' => $request->input('name'),
                'category_id' => $request->input('category_id'),
                'uom_id' => $request->input('uom_id'),
                'image' => $filename,
                'min_stock' => $request->input('min_stock'),
                'price' => $request->input('price'),
                'specification' => $request->input('specification'),
                'updated_by' => auth()->user()->id,
            ];
        } else {
            $input = [ 
                'sap_code' => $request->input('sap_code'),
                'name' => $request->input('name'),
                'category_id' => $request->input('category_id'),
                'uom_id' => $request->input('uom_id'),
                'min_stock' => $request->input('min_stock'),
                'price' => $request->input('price'),
                'specification' => $request->input('specification'),
                'updated_by' => auth()->user()->id,
            ];
        }
        
        $data = Product::find($id);
        $data->update($input);
        $log = 'Product '.($data->name).' Updated';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Product '.($data->name).' Updated',
            'alert-type' => 'success'
        );

        return redirect()->route('product.index')->with($notification);
    }

    public function productDestroy($id)
    {
        $data = Product::find($id);
        $destroy = [
            'deleted_at' => Carbon::now()->toDateTimeString(),
            'updated_by' => auth()->user()->id,
        ];
        $data->update($destroy);
        $log = 'Product '.($data->name).' Deleted';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Product '.($data->name).' Deleted',
            'alert-type' => 'success'
        );
        
        return redirect()->route('product.index')->with($notification);
    }
}
