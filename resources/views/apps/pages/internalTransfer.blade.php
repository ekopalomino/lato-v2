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
                    @can('Can Create Inventory')
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
                                <th>From</th>
                                <th>To</th>
                                <th>Created</th>
                                <th>Received</th>
                                <th>Status</th>
                                <th>Request By</th>
                                <th>Received By</th>
                                <th></th>
                			</tr>
                		</thead>
                		<tbody>
                            @foreach($data as $key=>$val)
                            <tr>      
                                <td>{{ $key+1 }}</td>
                                <td>{{ $val->order_ref }}</td>
                                <td>{{ $val->from_wh }}</td>
                                <td>{{ $val->to_wh }}</td>
                                <td>{{date("d F Y H:i",strtotime($val->created_at)) }}</td>
                                <td>
                                    @if($val->updated_at != $val->created_at)
                                    {{date("d F Y H:i",strtotime($val->updated_at)) }}
                                    @endif
                                </td>
                                <td> 
                                    @if( ($val->status_id) == '4')
                                    <label class="label label-sm label-danger">{{ $val->Statuses->name }}</label>
                                    @elseif(($val->status_id) == '3')
                                    <label class="label label-sm label-success">{{ $val->Statuses->name }}</label>
                                    @endif
                                </td>
                                <td>{{ $val->created_by }}</td>
                                <td>
                                    @if(!empty($val->updated_by))
                                    {{ $val->updated_by }}
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-xs btn-info modalMd" href="#" value="{{ action('Apps\InventoryManagementController@transferView',['id'=>$val->id]) }}" title="Detail Mutasi" data-toggle="modal" data-target="#modalMd"><i class="fa fa-search"></i></a>
                                    @can('Can Edit Inventory')
                                    {!! Form::open(['method' => 'POST','route' => ['transfer.accept', $val->id],'style'=>'display:inline','onsubmit' => 'return ConfirmDelete()']) !!}
                                    {!! Form::button('<i class="fa fa-check"></i>',['type'=>'submit','class' => 'btn btn-xs btn-success','title'=>'Terima Mutasi']) !!}
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
    var x = confirm("Mutasi Barang Diterima?");
    if (x)
        return true;
    else
        return false;
    }
</script>
@endsection