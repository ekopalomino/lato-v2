@extends('apps.layouts.main')
@section('header.title')
LATO | Purchase Receipt
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
                        <i class="fa fa-database"></i>Purchase Receipt
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="col-md-6"> 
                        @can('Can Create Inventory')
                        <div class="form-group">
                            <div class="form-group">
                                <a href="{{ route('receipt.search') }}"><button id="sample_editable_1_new" class="btn red btn-outline sbold"> Add New
                                </button></a>
                            </div>
                        </div>
                        @endcan
                    </div>
                	<table class="table table-striped table-bordered table-hover" id="sample_2">
                		<thead>
                			<tr>
                                <th>No</th>
                                <th>Ref No</th>
                                <th>Product</th>
                                <th>Warehouse</th>
                                <th>Order</th>
                                <th>Delivered</th>
                                <th>Return</th>
                                <th>Remaining</th>
                                <th>Data Date</th>
                                <th></th>
                			</tr>
                		</thead>
                		<tbody>
                            @foreach($data as $key => $val)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $val->order_ref }}</td>
                                <td>
                                    @foreach($val->Child as $child)
                                    <ul>
                                        <li>{{ $child->product_name}}</li>
                                    </ul>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($val->Child as $child)
                                    <ul>
                                        <li>{{ $child->Warehouses->name}}</li>
                                    </ul>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($val->Child as $child)
                                    <ul>
                                        <li>{{ number_format($child->orders,0,',','.')}} {{ $child->OrderUom->name }}</li>
                                    </ul>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($val->Child as $child)
                                    <ul>
                                        <li>{{ number_format($child->received,0,',','.')}} {{ $child->Uoms->name }}</li>
                                    </ul>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($val->Child as $child)
                                    <ul>
                                        <li>{{ number_format($child->damaged,0,',','.')}} {{ $child->Uoms->name }}</li>
                                    </ul>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($val->Child as $child)
                                    <ul>
                                        <li>{{ number_format($child->remaining,0,',','.')}} {{ $child->Uoms->name }}</li>
                                    </ul>
                                    @endforeach
                                </td>
                                <td>{{date("d F Y H:i",strtotime($val->updated_at)) }}</td>
                                <td>
                                    <a class="btn btn-xs btn-info" title="Edit PO" href="{{ route('receipt.edit',$val->id) }}"><i class="fa fa-edit"></i></a>
                                    {!! Form::open(['method' => 'POST','route' => ['receipt.close', $val->id],'style'=>'display:inline','onsubmit' => 'return ConfirmDelete()']) !!}
                                    {!! Form::button('<i class="fa fa-lock"></i>',['type'=>'submit','class' => 'btn btn-xs btn-danger','title'=>'Close PO']) !!}
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
    var x = confirm("Pembelian Akan Ditutup?");
    if (x)
        return true;
    else
        return false;
    }
</script>
@endsection