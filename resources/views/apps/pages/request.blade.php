@extends('apps.layouts.main')
@section('header.title')
ATK Management | ATK Request
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
                        <i class="fa fa-database"></i>ATK Request
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="col-md-6">
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
                        @can('Can Create Purchase')
                        <div class="form-group">
                            <a href="{{ route('request.create') }}"><button id="sample_editable_1_new" class="btn red btn-outline sbold">Add
                            </button></a>
                        </div>
                        @endcan
                    </div>
                	<table class="table table-striped table-bordered table-hover" id="sample_2">
                		<thead>
                			<tr>
                                <th>No</th>
                				<th>Ref No</th>
                                <th>Request Name</th>
                                <th>From Warehouse</th>
                                <th>Request By</th>
                                <th>Approve By</th>
                				<th>Status</th>
                                <th>Data Date</th>
                				<th></th>
                			</tr>
                		</thead> 
                		<tbody>
                            @foreach($data as $key => $val)
                			<tr>
                				<td>{{ $key+1 }}</td>
                                <td>{{ $val->request_ref }}</td>
                                <td>{{ $val->request_name}}</td>
                                <td>{{ $val->From->name }}</td>
                                <td>
                                    @if(!empty($val->updated_by))    
                                    {{ $val->Editor->name }}
                                    @else
                                    {{ $val->Author->name }}
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($val->approve_by))    
                                    {{ $val->Approval->name }}
                                    @endif
                                </td>
                                <td> 
                                    @if(($val->status_id) == '4')
                                    <label class="label label-sm label-danger">{{ $val->Statuses->name }}</label>
                                    @else
                                    <label class="label label-sm label-info">{{ $val->Statuses->name }}</label>
                                    @endif
                                </td>
                                <td>{{date("d F Y H:i",strtotime($val->updated_at)) }}</td>
                                <td>
                                    @if(($val->status_id) == '1')
                                    <a class="btn btn-xs btn-info" title="Lihat PR" href="{{ route('request.show',$val->id) }}"><i class="fa fa-search"></i></a>
                                    @can('Can Approve Purchase')
                                    {!! Form::open(['method' => 'POST','route' => ['request.approve', $val->id],'style'=>'display:inline','onsubmit' => 'return ConfirmAccept()']) !!}
                                    {!! Form::button('<i class="fa fa-check"></i>',['type'=>'submit','class' => 'btn btn-xs btn-success','title'=>'Approve PR']) !!}
                                    {!! Form::close() !!}
                                    {!! Form::open(['method' => 'POST','route' => ['request.rejected', $val->id],'style'=>'display:inline','onsubmit' => 'return ConfirmDelete()']) !!}
                                    {!! Form::button('<i class="fa fa-remove"></i>',['type'=>'submit','class' => 'btn btn-xs btn-danger','title'=>'Tolak PR']) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                    @endif
                                    @if(($val->status) == '314f31d1-4e50-4ad9-ae8c-65f0f7ebfc43')
                                    {!! Form::open(['method' => 'POST','route' => ['purchase.close', $val->id],'style'=>'display:inline','onsubmit' => 'return ConfirmClose()']) !!}
                                    {!! Form::button('<i class="fa fa-lock"></i>',['type'=>'submit','class' => 'btn btn-xs btn-danger','title'=>'Tolak PR']) !!}
                                    {!! Form::close() !!}
                                    @endif
                                    @if(($val->status) == '458410e7-384d-47bc-bdbe-02115adc4449')
                                    <a class="btn btn-xs btn-info" title="Lihat PO" href="{{ route('purchase.show',$val->id) }}"><i class="fa fa-search"></i></a>
                                    @endif
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
    var x = confirm("Pengajuan Akan Dibatalkan?");
    if (x)
        return true;
    else
        return false;
    }
</script>
<script>
    function ConfirmAccept()
    {
    var x = confirm("Pengajuan Akan Diproses?");
    if (x)
        return true;
    else
        return false;
    }
</script>
@endsection