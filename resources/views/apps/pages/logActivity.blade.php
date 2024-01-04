@extends('apps.layouts.main')
@section('header.title')
LATO | Activity Log
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
                        <i class="fa fa-database"></i>Log Activities 
                    </div>
                    <div class="tools"> </div>
                </div>
                <div class="portlet-body">
                	<table class="table table-striped table-bordered table-hover" id="sample_2">
                		<thead>
                			<tr>
                                <th>No</th>
                				<th>Subject</th>
                				<th>URL</th>
                				<th>IP User</th>
                				<th>Username</th>
                				<th>Activity Date</th>
                			</tr>
                		</thead>
                		<tbody>
                            @if($logs->count())
                            @foreach($logs as $key => $log)
                			<tr>
                				<td>{{ $key+1 }}</td>
                				<td>{{ $log->subject }}</td>
                				<td class="text-success">{{ $log->url }}</td>
                				<td class="text-danger">{{ $log->ip }}</td>
                				<td><label class="label label-sm label-info">{{ $log->creator->name }}</label></td>
                				<td>{{date("d F Y H:i",strtotime($log->created_at)) }}</td>
                			</tr>
                            @endforeach
                            @endif
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