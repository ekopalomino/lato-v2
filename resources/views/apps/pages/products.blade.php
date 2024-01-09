@extends('apps.layouts.main')
@section('header.title')
LATO | Product Catalog
@endsection
@section('header.styles')
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="page-content">
	<div class="row">
		<div class="col-md-12">
            <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-database"></i>Product Catalog
                    </div>
                </div>
                <div class="portlet-body">
                    @if (count($errors) > 0) 
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                        </div>
                    @endif
                    @can('Can Create Product')
                    <div class="col-md-6">
                        <div class="form-group">
                            <a href="{{ route('product.create') }}"><button id="sample_editable_1_new" class="btn red btn-outline sbold"> Add New
                            </button></a>
                        </div>
                    </div>
                    @endcan
                	<table class="table table-striped table-bordered table-hover" id="sample_2">
                		<thead>
                			<tr>
                                <th>No</th>
                                <th>SAP Code</th>
                				<th>Name</th>
                                <th>Category</th>
                                <th>UOM</th>
                                <th>Min Stock</th>
                                <th>Status</th>
                                <th>Create / Update</th>
                                <th>Data Date</th>
                				<th></th>
                			</tr>
                		</thead>
                		<tbody>
                            @foreach($data as $key => $product)
                			<tr>
                				<td>{{ $key+1 }}</td>
                                <td>{{ $product->sap_code }}</td>
                				<td>{{ $product->name }}</td>
                                <td>{{ $product->Categories->name }}</td>
                                <td>{{ $product->Uoms->name }}</td>
                                <td>{{ $product->min_stock }}</td>
                                <td>
                                    @if(!empty($product->deleted_at))
                                    <label class="label label-sm label-danger">Inactive</label>
                                    @else
                                    <label class="label label-sm label-success">Active</label>
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($product->updated_by))    
                                    {{ $product->Editor->name }}
                                    @else
                                    {{ $product->Author->name }}
                                    @endif
                                </td>
                                <td>{{date("d F Y H:i",strtotime($product->updated_at)) }}</td>
                				<td>
                                    @can('Can Edit Product')
                                    <a class="btn btn-xs btn-success" href="{{ route('product.edit',$product->id) }}" title="Edit Product" ><i class="fa fa-edit"></i></a>
                                    @endcan
                                    @can('Can Delete Product')
                                    {!! Form::open(['method' => 'POST','route' => ['product.destroy', $product->id],'style'=>'display:inline','onsubmit' => 'return ConfirmDelete()']) !!}
                                    {!! Form::button('<i class="fa fa-trash"></i>',['type'=>'submit','class' => 'btn btn-xs btn-danger','title'=>'Disable Product']) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>
                			</tr>
                            @endforeach
                		</tbody>
                	</table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer.plugins')
<script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
@endsection
@section('footer.scripts')
<script src="{{ asset('assets/pages/scripts/table-datatables-buttons.min.js') }}" type="text/javascript"></script>
<script>
    function ConfirmDelete()
    {
    var x = confirm("Are you sure you want to deactivate?");
    if (x)
        return true;
    else
        return false;
    }
</script>
@endsection