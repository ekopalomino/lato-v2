@extends('apps.layouts.main')
@section('header.title')
LATO | Stock Card Report
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
                        <i class="fa fa-database"></i>Stock Card Report
                    </div>
                    <div class="tools"> </div>
                </div>
                <div class="portlet-body">
                	<table class="table table-striped table-bordered table-hover" id="sample_2">
                		<thead>
                			<tr>
                                <th>No</th>
                                <th>Product</th>
                                <th>Document</th>
                                <th>Transaction Date</th>
                                <th>In</th>
                                <th>Out</th>
                                <th>Remaining</th>
                                <th>Remarks</th>
                			</tr>
                		</thead>
                		<tbody>
                            @foreach($data as $key => $val)
                			<tr>
                				<td>{{ $key+1 }}</td>
                                <td>{{ $val->product_name }}</td>
                                <td>{{ $val->reference_id }}</td>
                				<td>{{date("d F Y H:i",strtotime($val->updated_at)) }}</td>
                                <td>{{ number_format($val->incoming,0,',','.')}}</td>
                                <td>{{ number_format($val->outgoing,0,',','.')}}</td>
                                <td>{{ number_format($val->remaining,0,',','.')}}</td>
                                <td>
                                    @if($val->type == 1)
                                        Adjustment
                                        @elseif($val->type == 2)
                                        Penjualan
                                        @elseif($val->type == 3)
                                        Purchase
                                        @elseif($val->type == 4)
                                        Usage
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
@endsection