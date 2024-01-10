@extends('apps.layouts.main')
@section('header.title')
LATO | ATK Request Detail
@endsection
@section('header.plugin')
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="page-content">
	<div class="row">
		<div class="col-md-12">
			<table class="table table-striped table-bordered table-hover" id="sample_2">
				<thead>
                	<tr>
                        <th>No</th>
                		<th>Product</th>
                		<th>Quantity</th>
                		<th>UOM</th>
                        <th>Request By</th>
                        <th>Location</th>
                	</tr>
                </thead>
                <tbody>
                	@foreach($details as $key => $val)
                	<tr>
                        <td>{{ $key+1 }}</td>
	                	<td>{{ $val->product_name }}</td>
	                	<td>{{ $val->quantity}}</td>
                        <td>{{ $val->Uoms->name}}</td>
                        <td>{{ $val->Parent->Sender->name }}</td>
                        <td>{{ $val->Parent->to_wh }}</td>
	                </tr>
                	@endforeach
                </tbody>
            </table>         
		</div>
		<div class="col-md-12">
            <a button type="button" class="btn default" href="{{ route('transfer.index') }}">Back</a>
        </div>
    </div>
</div>       
@endsection
@section('footer.plugins')
<script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}" type="text/javascript"></script>
@endsection
@section('footer.scripts')
<script src="{{ asset('assets/pages/scripts/ecommerce-orders-view.min.js') }}" type="text/javascript"></script>
@endsection