@extends('apps.layouts.main')
@section('header.title')
FiberTekno | Inventory
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
                        <i class="fa fa-database"></i>Inventory 
                    </div>
                </div>
                <div class="portlet-body">
                	<table class="table table-striped table-bordered table-hover" id="sample_2">
                		<thead>
                			<tr>
                                <th>No</th>
                				<th>Product Name</th>
                                <th>Product Location</th>
                                <th>Product UOM</th>
                                <th>Opening Stock</th>
                                <th>Closing Stock</th>
                                <th>Stock Status</th>
                				<th>Created At</th>
                                <th></th>
                			</tr>
                		</thead>
                		<tbody>
                            @foreach($data as $key => $product)
                			<tr>
                				<td>{{ $key+1 }}</td>
                				<td>{{ $product->Products->name }}</td>
                                <td>{{ $product->Locations->name }}</td>
                                <td>{{ $product->Products->Uoms->name }}</td>
                                <td>{{ $product->opening_amount }}</td>
                                <td>{{ $product->closing_amount }}</td>
                                <td>
                                    @if( ($product->closing_amount) == 0)
                                        <label class="badge badge-danger">No Stock</label>
                                    @elseif(($product->closing_amount)<($product->min_stock))
                                        <label class="badge badge-warning">Low On Stock</label>
                                    @elseif(($product->closing_amount)>($product->min_stock))
                                        <label class="badge badge-success">Stock Normal</label>
                                    @endif
                                </td>
                				<td>{{date("d F Y H:i",strtotime($product->created_at)) }}</td>
                                <td>
                                    <a class="btn btn-xs btn-info modalMd" href="#" value="{{ action('Apps\InventoryManagementController@stockCard',['id'=>$product->id]) }}" title="Stock Card" data-toggle="modal" data-target="#modalMd"><i class="fa fa-search"></i></a>
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