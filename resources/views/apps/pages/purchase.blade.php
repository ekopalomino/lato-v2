@extends('apps.layouts.main')
@section('header.title')
LATO | Purchase Request
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
                        <i class="fa fa-database"></i>Purchase Request 
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
                            <a href="{{ route('request.create') }}"><button id="sample_editable_1_new" class="btn red btn-outline sbold">Add New
                            </button></a>
                        </div>
                        @endcan
                    </div>
                	<table class="table table-striped table-bordered table-hover" id="sample_2">
                		<thead>
                			<tr>
                                <th>No</th>
                				<th>Ref No</th>
                                <th>Total Quantity</th>
                                <th>Total Price</th>
                				<th>Request By</th>
                                <th>Received By</th>
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
                                <td>{{ number_format($val->quantity,0,',','.')}}</td>
                                <td>{{ number_format($val->total,0,',','.')}}</td>
                                <td>{{ $val->Author->name }}</td>
                                <td>
                                    @if(!empty($val->received_by))    
                                    {{ $val->Receiver->name }}
                                    @endif
                                </td>
                                <td>
                                    @if($val->status == '8')
                                    <label class="label label-sm label-success">{{ $val->Statuses->name }}</label>
                                    @else
                                    <label class="label label-sm label-danger">{{ $val->Statuses->name }}</label>
                                    @endif
                                </td>
                                <td>{{date("d F Y H:i",strtotime($val->created_at)) }}</td>
                                <td>
                                    <a class="btn btn-xs btn-info" title="Lihat PR" href="{{ route('request.show',$val->id) }}"><i class="fa fa-search"></i></a>
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
@endsection