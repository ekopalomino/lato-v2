@extends('apps.layouts.main')
@section('header.title')
LATO | Product Category
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
                        <i class="fa fa-database"></i>Product Category 
                    </div>
                </div>
                <div class="portlet-body">
                    @can('Can Create Setting')
                    <div class="col-md-6">
                        <div class="form-group">
                            <tr>
                                <td>
                                    <a class="btn red btn-outline sbold" data-toggle="modal" href="#basic"> Add New</a>
                                </td>
                            </tr>
                        </div>
                    </div>
                    @endcan
                    <div class="col-md-6">
                        <div class="modal fade" id="basic" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    {!! Form::open(array('route' => 'product-cat.store','method'=>'POST')) !!}
                                    @csrf
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                        <h4 class="modal-title">New Product Category</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">Category Name</label>
                                                    {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">Material Group</label>
                                                    <br>
                                                    {!! Form::select('material_id[]', $materials, null, ['multiple' => true]) !!}
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="modal-footer">
                                        <button type="close" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                                        <button id="register" type="submit" class="btn green">Save</button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
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
                	<table class="table table-striped table-bordered table-hover" id="sample_2">
                		<thead>
                			<tr>
                                <th>No</th>
                                <th>Material Group</th>
                				<th>Category Name</th>
                                <th>Status</th>
                                <th>Create / Update</th>
                				<th>Data Date</th>
                				<th></th>
                			</tr>
                		</thead>
                		<tbody>
                            @foreach($data as $key => $val)
                			<tr>
                				<td>{{ $key+1 }}</td>
                                <td>
                                    @foreach($val->Child as $child)
                                    <ul>
                                        <li>{{ $child->Materials->material_name}}</li>
                                    </ul>
                                    @endforeach
                                </td>
                				<td>{{ $val->name }}</td>
                                <td>
                                    @if(!empty($val->deleted_at))
                                    <label class="label label-sm label-danger">Inactive</label>
                                    @else
                                    <label class="label label-sm label-success">Active</label>
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($val->updated_by))    
                                    {{ $val->Editor->name }}
                                    @else
                                    {{ $val->Author->name }}
                                    @endif
                                </td>
                				<td>{{date("d F Y H:i",strtotime($val->updated_at)) }}</td>
                				<td>
                                    @can('Can Edit Setting')
                                    <a class="btn btn-xs btn-success modalMd" href="#" value="{{ action('Apps\ProductManagementController@categoryEdit',['id'=>$val->id]) }}" title="Edit Data" data-toggle="modal" data-target="#modalMd"><i class="fa fa-edit"></i></a>
                                    @endcan
                                    @can('Can Delete Setting')
                                    {!! Form::open(['method' => 'POST','route' => ['product-cat.destroy', $val->id],'style'=>'display:inline','onsubmit' => 'return ConfirmDelete()']) !!}
                                    {!! Form::button('<i class="fa fa-trash"></i>',['type'=>'submit','class' => 'btn btn-xs btn-danger','title'=>'Delete Data']) !!}
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
    var x = confirm("Are you sure you want to delete?");
    if (x)
        return true;
    else
        return false;
    }
</script>
@endsection