@extends('apps.layouts.main')
@section('header.title')
LATO | ATK Request
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
                    @can('Can Create Request')
                    <div class="col-md-6">
                        <div class="form-group">
                            <a href="{{ route('add.transfer') }}"><button id="sample_editable_1_new" class="btn red btn-outline sbold">Add New
                            </button></a>
                        </div>
                    </div>
                    @endcan
                	<table class="table table-striped table-bordered table-hover" id="sample_2">
                		<thead>
                			<tr>
                                <th>No</th>
                				<th>Request No</th>
                                <th>Requestor</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Data Date</th>
                                <th></th>
                            </tr>
                		</thead>
                		<tbody>
                            @foreach($data as $key=>$val)
                            <tr>      
                                <td>{{ $key+1 }}</td>
                                <td>{{ $val->order_ref }}</td>
                                <td>{{ $val->Sender->name }}</td>
                                <td>{{ $val->to_wh }}</td>
                                <td>
                                    @if($val->status_id == '13')
                                    <label class="label label-sm label-success">{{ $val->Statuses->name }}</label>
                                    @else
                                    <label class="label label-sm label-danger">{{ $val->Statuses->name }}</label>
                                    @endif
                                </td>
                                <td>{{date("d F Y H:i",strtotime($val->created_at)) }}</td> 
                                <td>
                                    <a class="btn btn-xs btn-info" title="Lihat Request" href="{{ route('transfer.view',$val->id) }}"><i class="fa fa-search"></i></a>
                                    @if(($val->status_id) == '13')
                                    {!! Form::open(['method' => 'POST','route' => ['transfer.close', $val->id],'style'=>'display:inline','onsubmit' => 'return ConfirmDelete()']) !!}
                                    {!! Form::button('<i class="fa fa-lock"></i>',['type'=>'submit','class' => 'btn btn-xs btn-danger','title'=>'Close Request']) !!}
                                    {!! Form::close() !!} 
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
    var x = confirm("Close Request?");
    if (x)
        return true;
    else
        return false;
    }
</script>
@endsection