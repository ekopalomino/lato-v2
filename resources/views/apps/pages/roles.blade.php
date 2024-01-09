@extends('apps.layouts.main')
@section('header.title')
LATO | Access Role
@endsection
@section('header.styles')
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="page-content">
	<div class="row">
		<div class="col-md-12">
            <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-database"></i>Access Role
                    </div>
                    <div class="tools"> </div>
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
                    <div class="col-md-6">
                        @can('Can Create User')
                        <div class="form-group">
                            <tr>
                                <td>
                                    <a class="btn red btn-outline sbold" href="{{ route('role.create') }}"> Add New</a>
                                </td>
                            </tr>
                        </div>
                        @endcan
                    </div>
                	<table class="table table-striped table-bordered table-hover" id="sample_2">
                		<thead>
                			<tr>
                                <th>No</th>
                				<th>Nama Hak Akses</th>
                				<th>Tgl Dibuat</th>
                				<th>Action</th>
                			</tr>
                		</thead>
                		<tbody>
                            @foreach($roles as $key => $role)
                			<tr>
                				<td>{{ $key+1 }}</td>
                				<td>{{ $role->name }}</td>
                				<td>{{date("d F Y H:i",strtotime($role->created_at)) }}</td>
                				<td>
                                    <a class="btn btn-xs btn-info" href="{{ route('role.edit',$role->id)}}" title="Edit Data" ><i class="fa fa-edit"></i></a>
                                    {!! Form::open(['method' => 'POST','route' => ['role.destroy', $role->id],'style'=>'display:inline','onsubmit' => 'return ConfirmDelete()']) !!}
                                    {!! Form::button('<i class="fa fa-trash"></i>',['type'=>'submit','class' => 'btn btn-xs btn-danger','title'=>'Hapus Data']) !!}
                                    {!! Form::close() !!}
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
    var x = confirm("Yakin Data Akan Dihapus?");
    if (x)
        return true;
    else
        return false;
    }
</script>
@endsection