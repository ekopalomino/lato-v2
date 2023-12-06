<?php

namespace iteos\Http\Controllers\Apps;

use Illuminate\Http\Request;
use iteos\Http\Controllers\Controller;
use iteos\Models\Warehouse;
use iteos\Models\MaterialGroup;
use iteos\Models\Branch;
use iteos\Models\ChartOfAccount;
use iteos\Models\UomCategory;
use iteos\Models\UomValue;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;
use Auth;

class ConfigurationController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Can Access Settings');
        $this->middleware('permission:Can Create Setting', ['only' => ['create','store']]);
        $this->middleware('permission:Can Edit Setting', ['only' => ['edit','update']]);
        $this->middleware('permission:Can Delete Setting', ['only' => ['destroy']]);
    }

    public function coaIndex()
    {
        $data = ChartOfAccount::orderBy('coa_code','asc')->get();

        return view('apps.pages.coas',compact('data'));
    }

    public function coaStore(Request $request)
    {
        $this->validate($request, [
            'coa_code' => 'required|unique:chart_of_accounts,coa_code',
            'coa_name' => 'required|unique:chart_of_accounts,coa_name',
        ]);

        $input = [
            'coa_code' => $request->input('coa_code'),
            'coa_name' => $request->input('coa_name'),
            'created_by' => auth()->user()->id,
        ];
        $data = ChartOfAccount::create($input);
        $log = 'COA '.($data->coa_name).' Created';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'COA '.($data->coa_name).' Created',
            'alert-type' => 'success'
        );

        return redirect()->route('coas.index')->with($notification);
    }

    public function coaEdit($id)
    {
        $data = ChartOfAccount::find($id);

        return view('apps.edit.coas',compact('data'))->renderSections()['content'];
    }

    public function coaUpdate(Request $request,$id)
    {
        $this->validate($request, [
            'coa_code' => 'required|unique:chart_of_accounts,coa_code',
            'coa_name' => 'required|unique:chart_of_accounts,coa_name',
        ]);

        $input = [
            'coa_code' => $request->input('coa_code'),
            'coa_name' => $request->input('coa_name'),
            'updated_by' => auth()->user()->id,
        ];
        $data = ChartOfAccount::find($id);
        $data->update($input);
        $log = 'COA '.($data->coa_name).' Updated';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'COA '.($data->coa_name).' Updated',
            'alert-type' => 'success'
        );

        return redirect()->route('coas.index')->with($notification);
    } 

    public function coaDestroy($id)
    {
        $data = ChartOfAccount::find($id);
        $destroy = [
            'deleted_at' => Carbon::now()->toDateTimeString(),
            'updated_by' => auth()->user()->id,
        ];
        $data->update($destroy);
        $log = 'COA '.($data->coa_name).' Deleted';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'COA '.($data->coa_name).' Deleted',
            'alert-type' => 'success'
        );
        

        return redirect()->route('coas.index')->with($notification);
    }

    public function branchIndex()
    {
        $data = Branch::orderBy('branch_name','asc')->get();

        return view('apps.pages.branch',compact('data'));
    }

    public function branchStore(Request $request)
    {
        $this->validate($request, [
            'branch_name' => 'required|unique:branches,branch_name',
        ]);

        $input = [
            'branch_name' => $request->input('branch_name'),
            'created_by' => auth()->user()->id,
        ];
        $data = Branch::create($input);
        $log = 'Branch '.($data->branch_name).' Created';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Branch '.($data->branch_name).' Created',
            'alert-type' => 'success'
        );

        return redirect()->route('branch.index')->with($notification);
    }

    public function branchEdit($id)
    {
        $data = Branch::find($id);

        return view('apps.edit.branch',compact('data'))->renderSections()['content'];
    }

    public function branchUpdate(Request $request,$id)
    {
        $this->validate($request, [
            'branch_name' => 'required|unique:branches,branch_name',
        ]);

        $input = [
            'branch_name' => $request->input('branch_name'),
            'updated_by' => auth()->user()->id,
        ];
        $data = Branch::find($id);
        $data->update($input);
        $log = 'Branch '.($data->branch_name).' Updated';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Branch '.($data->branch_name).' Updated',
            'alert-type' => 'success'
        );

        return redirect()->route('branch.index')->with($notification);
    } 

    public function branchDestroy($id)
    {
        $data = Branch::find($id);
        $destroy = [
            'deleted_at' => Carbon::now()->toDateTimeString(),
            'updated_by' => auth()->user()->id,
        ];
        $data->update($destroy);
        $log = 'branch '.($data->branch_name).' Deleted';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'branch '.($data->branch_name).' Deleted',
            'alert-type' => 'success'
        );
        

        return redirect()->route('branch.index')->with($notification);
    }

    public function warehouseIndex()
    {
        $data = Warehouse::orderBy('name','asc')->get();
        $materials = MaterialGroup::where('deleted_at',NULL)->pluck('material_name','id')->toArray();
        $branches = Branch::where('deleted_at',NULL)->pluck('branch_name','id')->toArray();

        return view('apps.pages.warehouse',compact('data','materials','branches'));
    }

    public function warehouseStore(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:warehouses,name',
            'prefix' => 'required|unique:warehouses,prefix',
            'material_group_id' => 'required'
        ]);

        $input = [
            'name' => $request->input('name'),
            'prefix' => $request->input('prefix'),
            'branch_id' => $request->input('branch_id'),
            'material_group_id' => $request->input('material_group_id'),
            'created_by' => auth()->user()->id,
        ];
        $data = Warehouse::create($input);
        $log = 'Warehouse '.($data->name).' Created';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Warehouse '.($data->name).' Created',
            'alert-type' => 'success'
        );

        return redirect()->route('warehouse.index')->with($notification);
    }

    public function warehouseEdit($id)
    {
        $data = Warehouse::find($id);
        $materials = MaterialGroup::where('deleted_at',NULL)->pluck('material_name','id')->toArray();
        $branches = Branch::where('deleted_at',NULL)->pluck('branch_name','id')->toArray();

        return view('apps.edit.warehouse',compact('data','materials','branches'))->renderSections()['content'];
    }

    public function warehouseUpdate(Request $request,$id)
    {
        $this->validate($request, [
            'name' => 'required|unique:warehouses,name',
            'prefix' => 'required|unique:warehouses,prefix',
            'material_group_id' => 'required'
        ]);

        $input = [
            'name' => $request->input('name'),
            'branch_id' => $request->input('branch_id'),
            'material_group_id' => $request->input('material_group_id'),
            'updated_by' => auth()->user()->id,
        ];
        $data = Warehouse::find($id);
        $data->update($input);
        $log = 'Warehouse '.($data->name).' Updated';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Warehouse '.($data->name).' Updated',
            'alert-type' => 'success'
        );

        return redirect()->route('warehouse.index')->with($notification);
    } 

    public function warehouseDestroy($id)
    {
        $data = Warehouse::find($id);
        $destroy = [
            'deleted_at' => Carbon::now()->toDateTimeString(),
            'updated_by' => auth()->user()->id,
        ];
        $data->update($destroy);
        $log = 'Warehouse '.($data->name).' Deleted';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Warehouse '.($data->name).' Deleted',
            'alert-type' => 'success'
        );
        

        return redirect()->route('warehouse.index')->with($notification);
    }

    public function uomcatIndex()
    {
        $data = UomCategory::orderBy('name','asc')->get();

        return view('apps.pages.uomCategory',compact('data'));
    }

    public function uomcatStore(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:uom_categories,name',
        ]);

        $input = [
            'name' => $request->input('name'),
            'created_by' => auth()->user()->name,
        ];
        $data = UomCategory::create($input);
        $log = 'Kategori Satuan '.($data->name).' Berhasil Disimpan';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Kategori Satuan '.($data->name).' Berhasil Disimpan',
            'alert-type' => 'success'
        );

        return redirect()->route('uom-cat.index')->with($notification);
    }

    public function uomcatEdit($id)
    {
        $data = UomCategory::find($id);

        return view('apps.edit.uomCategory',compact('data'))->renderSections()['content'];
    }

    public function uomcatUpdate(Request $request,$id)
    {
        $this->validate($request, [
            'name' => 'required|unique:uom_categories,name',
        ]);

        $input = [
            'name' => $request->input('name'),
            'updated_by' => auth()->user()->name,
        ];
        $data = UomCategory::find($id)->update($input);
        $log = 'Kategori Satuan '.($data->name).' Berhasil Diubah';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Kategori Satuan '.($data->name).' Berhasil Diubah',
            'alert-type' => 'success'
        );

        return redirect()->route('uom-cat.index')->with($notification);
    }

    public function uomcatDestroy($id)
    {
        $data = UomCategory::find($id);
        $log = 'Kategori UOM '.($data->name).' Berhasil Dihapus';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Kategori Satuan '.($data->name).' Berhasil Dihapus',
            'alert-type' => 'success'
        );
        $data->delete();

        return redirect()->route('uom-cat.index')->with($notification);
    }

    public function uomvalIndex()
    {
        $data = UomValue::orderBy('created_at','asc')->get();
        $categories = UomCategory::pluck('name','id')->toArray();
        $parents = UomValue::where('is_parent','1')->pluck('name','id')->toArray();

        return view('apps.pages.uomValue',compact('data','categories','parents'));
    }

    public function uomvalStore(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:uom_values,name',
            'type_id' => 'required',
            'value' => 'required|numeric',
        ]);

        $input = [
            'name' => $request->input('name'),
            'type_id' => $request->input('type_id'),
            'is_parent' => $request->input('is_parent'),
            'parent_id' => $request->input('parent_id'),
            'value' => $request->input('value'),
            'created_by' => auth()->user()->name,
        ];
        $data = UomValue::create($input);
        $log = 'Satuan '.($data->name).' Berhasil Disimpan';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Satuan '.($data->name).' Berhasil Disimpan',
            'alert-type' => 'success'
        );

        return redirect()->route('uom-val.index')->with($notification);
    }

    public function uomvalEdit($id)
    {
        $data = UomValue::find($id);
        
        $categories = UomCategory::pluck('name','id')->toArray();

        return view('apps.edit.uomValue',compact('data','categories'))->renderSections()['content'];
    }

    public function uomvalUpdate(Request $request,$id)
    {
        $this->validate($request, [
            'name' => 'required|unique:uom_values,name',
            'type_id' => 'required',
            'value' => 'required|numeric',
        ]);

        $input = [
            'name' => $request->input('name'),
            'type_id' => $request->input('type_id'),
            'value' => $request->input('value'),
            'updated_by' => auth()->user()->name,
        ];
        $data = UomValue::find($id)->update($input);
        $log = 'Satuan '.($data->name).' Berhasil Diubah';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Satuan '.($data->name).' Berhasil Diubah',
            'alert-type' => 'success'
        );

        return redirect()->route('uom-val.index')->with($notification);
    }

    public function uomvalDestroy($id)
    {
        $data = UomValue::find($id);
        $log = 'Satuan '.($data->name).' Berhasil Dihapus';
         \LogActivity::addToLog($log);
        $notification = array (
            'message' => 'Satuan '.($data->name).' Berhasil Dihapus',
            'alert-type' => 'success'
        );
        $data->delete();

        return redirect()->route('uom-val.index')->with($notification);
    }
}
