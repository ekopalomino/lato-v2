@extends('apps.layouts.main')
@section('header.title')
LATO | Stock Opname
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
                        <i class="fa fa-database"></i>Stock Opname
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
                    <div class="col-md-6">
                        <div class="form-group">
                           <a href="{{ route('opname.create') }}"><button id="download" class="btn red btn-outline sbold fa fa-pencil"> Create
                            </button></a>
                        </div>
                    </div>
                    <table class="table table-striped table-bordered table-hover" id="adjustment">
                		<thead>
                			<tr>
                                <th>No</th>
                                <th>Remarks</th>
                                <th>Branch</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Created At</th>
                                <th></th>
                			</tr>
                		</thead>
                        <tbody>
                            @foreach($data as $key => $val)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $val->remarks }}</td>
                                <td>{{ $val->Branches->branch_name }}</td>
                                <td>{{ $val->Statuses->name }}</td>
                                <td>{{ $val->Creator->name }}</td>
                                <td>{{date("d F Y H:i",strtotime($val->updated_at)) }}</td>
                                <td></td>
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