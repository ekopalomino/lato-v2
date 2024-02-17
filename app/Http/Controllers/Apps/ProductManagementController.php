<?php

namespace iteos\Http\Controllers\Apps;

use Illuminate\Http\Request;
use iteos\Http\Controllers\Controller;
use iteos\Models\Product;
use iteos\Models\ProductCategory;
use iteos\Models\ProductHasGroup;
use iteos\Models\UomValue;
use iteos\Models\Warehouse;
use iteos\Models\Inventory;
use iteos\Models\MaterialGroup;
use iteos\Models\ChartOfAccount;
use iteos\Exports\CategoryExport;
use iteos\Exports\MaterialExport;
use Carbon\Carbon;
use iteos\Imports\ProductImport;
use iteos\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;
use Auth;
use PDF;
use File;
use DataTables;
use Storage;

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
        $materials = MaterialGroup::where('deleted_at',NULL)->pluck('material_name','id')->toArray(); 

        return view('apps.pages.productCategory',compact('data','materials'));
    }

    public function categoryStore(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:product_categories,name',
        ]);
        $materials = $request->material_id;
        $input = [
            'name' => $request->input('name'),
            'created_by' => auth()->user()->id,
        ];
        $data = ProductCategory::create($input);
        foreach($materials as $index=>$material)
        {
            $materialData = ProductHasGroup::create([
                'category_id' => $data->id,
                'material_id' => $material,
            ]);
        }
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
        $materials = MaterialGroup::where('deleted_at',NULL)->pluck('material_name','id')->toArray();

        return view('apps.edit.productCategory',compact('data','materials'))->renderSections()['content'];
    }

    public function categoryUpdate(Request $request,$id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        $materials = $request->material_id;
        $input = [
            'name' => $request->input('name'),
            'material_group_id' => $request->input('material_group_id'),
            'updated_by' => auth()->user()->id,
        ];
        $data = ProductCategory::find($id);
        $data->update($input);
        $group = ProductHasGroup::where('category_id',$data->id)->delete();
        foreach($materials as $index=>$material)
        {
            $materialData = ProductHasGroup::create([
                'category_id' => $data->id,
                'material_id' => $material,
            ]);
        }
        $log = 'Category '.($data->name).' Updated';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Category '.($data->name).' Updated',
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

    public function categoryExport()
    {
        return Excel::download(new CategoryExport, 'product-category.xlsx');
    }

    public function materialIndex()
    {
        $data = MaterialGroup::orderBy('material_name','asc')->get();
        $coas = ChartOfAccount::where('deleted_at',NULL)->pluck('coa_name','id')->toArray();

        return view('apps.pages.materialGroup',compact('data','coas'));
    }

    public function materialStore(Request $request)
    {
        $this->validate($request, [
            'material_name' => 'required|unique:material_groups,material_name',
            'account_id' => 'required',
        ]);

        $input = [
            'material_name' => $request->input('material_name'),
            'account_id' => $request->input('account_id'),
            'created_by' => auth()->user()->id,
        ];
        $data = MaterialGroup::create($input);
        $log = 'Material Group '.($data->name).' Created';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Material Group '.($data->name).' Created',
            'alert-type' => 'success'
        );

        return redirect()->route('material.index')->with($notification);
    }

    public function materialEdit($id)
    {
        $data = MaterialGroup::find($id);
        $coas = ChartOfAccount::where('deleted_at',NULL)->pluck('coa_name','id')->toArray();

        return view('apps.edit.materialGroup',compact('data','coas'))->renderSections()['content'];
    }

    public function materialUpdate(Request $request,$id)
    {
        $this->validate($request, [
            'material_name' => 'required|unique:material_groups,material_name',
            'account_id' => 'required',
        ]);

        $input = [
            'material_name' => $request->input('material_name'),
            'account_id' => $request->input('account_id'),
            'updated_by' => auth()->user()->id,
        ];
        $data = MaterialGroup::find($id);
        $data->update($input);
        $log = 'Material Group '.($input->name).' Updated';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Material Group '.($input->name).' Updated',
            'alert-type' => 'success'
        );

        return redirect()->route('material.index')->with($notification);
    }

    public function materialDestroy($id)
    {
        $data = MaterialGroup::find($id);
        $destroy = [
            'deleted_at' => Carbon::now()->toDateTimeString(),
            'updated_by' => auth()->user()->id,
        ];
        $data->update($destroy);
        $log = 'Material Group '.($data->name).' Deleted';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Material Group '.($data->name).' Deleted',
            'alert-type' => 'success'
        );

        return redirect()->route('product-cat.index')->with($notification);
    }

    public function materialExport()
    {
        return Excel::download(new MaterialExport, 'material-group.xlsx');
    }

    public function productIndex(Request $request)
    {
        if($request->ajax()) {
            $data = Product::with('Categories','Materials','Uoms')->orderBy('name','ASC');

            return Datatables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('categories',function(Product $product){
                    return $product->categories->name;
                })
                ->addColumn('uoms',function(Product $product){
                    return $product->uoms->name;
                })
                ->addColumn('statuses',function($row){
                    if($row->status_id == 8){
                        return "<label class='label label-sm label-success'>Active</label>";
                    }else{
                        return "<label class='label label-sm label-danger'>Inactive</label>";
                    }
                })
                ->addColumn('author',function(Product $product){
                    return $product->author->name;
                })
                ->addColumn('created_at',function($row){
                    $date = date("d F Y H:i", strtotime($row->created_at));
                    return $date;
                })
                ->addColumn('action', function($row){
                    // Update Button
                    $updateButton = "<a class='btn btn-xs btn-info updateProduct' href='".route('product.edit',$row->id)."'' ><i class='fa fa-edit'></i></a>";
                    // Delete Button
                    $deleteButton = "<button class='btn btn-xs btn-danger deleteProduct' data-id='".$row->id."'><i class='fa fa-trash'></i></button>";

                    return $updateButton." ".$deleteButton;

                }) 
                ->make();
        }
    	
    	return view('apps.pages.products');
    }

    public function productCreate()
    {
        $categories = ProductCategory::where('deleted_at',NULL)->pluck('name','id')->toArray();
        $materials = MaterialGroup::where('deleted_at',NULL)->pluck('material_name','id')->toArray();
        $uoms = UomValue::pluck('name','id')->toArray();
        $warehouses = Warehouse::where('branch_id',auth()->user()->branch_id)->where('deleted_at',NULL)->pluck('name','id')->toArray();
         
        return view('apps.input.products',compact('categories','uoms','materials','warehouses'));
    }

    public function productStore(Request $request)
    {
        $this->validate($request, [
            'sap_code' => 'required|unique:products,sap_code',
            'name' => 'required',
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
        /* $stocks = Inventory::create([
            'product_id' => $data->id,
            'product_name' => $data->name,
            'warehouse_id' => $data->warehouse_id,
            'warehouse_name' => $data->Warehouses->name,
            'material_group_id' => $data->material_group_id,
            'min_stock' => $data->min_stock,
            'opening_amount' => '0',
            'closing_amount' => '0', 
        ]); */
        $log = 'Product '.($data->name).' Created';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Product '.($data->name).' Created',
            'alert-type' => 'success'
        );

        return redirect()->route('product.index')->with($notification);
    }

    public function importTemplate()
    {
        $file = 'atk.xlsx';
        return response()->download(storage_path('app/public/'. $file));
    }

    public function productImport()
    {
        return view('apps.input.productImport');
    }

    public function productImportStore(Request $request)
    {
        $this->validate($request, [
            'atk' => 'required|file|mimes:xlsx,xls,XLSX,XLS'
        ]);
        $data = new ProductImport();
        Excel::import($data, $request->file('atk'));

        $log = 'File Successfully Uploaded';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'File Successfully Uploaded',
            'alert-type' => 'success'
        );

        return redirect()->route('asset.index')->with($notification);
    }

    public function productEdit($id)
    {
        $data = Product::find($id);
        $categories = ProductCategory::pluck('name','id')->toArray();
        $uoms = UomValue::pluck('name','id')->toArray();
        $materials = MaterialGroup::where('deleted_at',NULL)->pluck('material_name','id')->toArray();

        return view('apps.edit.products',compact('data','categories','uoms','materials'));
    }

    public function productUpdate(Request $request,$id)
    {
        $this->validate($request, [
            'sap_code' => 'required',
            'name' => 'required',
            'material_group_id' => 'required',
            'warehouse_id' => 'required',
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
                'material_group_id' => $request->input('material_group_id'),
                'warehouse_id' => $request->input('warehouse_id'),
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
                'material_group_id' => $request->input('material_group_id'),
                'warehouse_id' => $request->input('warehouse_id'),
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

    public function productDestroy(Request $request)
    {
        $data = Product::find($id);
        $destroy = [
            'deleted_at' => Carbon::now()->toDateTimeString(),
            'status_id' => '9',
            'updated_by' => auth()->user()->id,
        ];
        $data->update($destroy);
        $log = 'Product '.($data->name).' Deleted';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Product '.($data->name).' Deleted',
            'alert-type' => 'success'
        );
        
        if($data->update($destroy)){
            $response['success'] = 1;
            $response['msg'] = 'Delete successfully'; 
        }else{
            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    public function downloadProduct()
    {
        return Excel::download( new ProductExport(), 'atk.xlsx') ;
    }
}
