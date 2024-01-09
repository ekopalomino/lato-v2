@extends('apps.layouts.main')
@section('header.title')
LATO | Stock Data 
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
                        <i class="fa fa-database"></i>Stock Data 
                    </div>
                    <div class="tools"> </div>
                </div>
                <div class="portlet-body">
                	<table class="table table-striped table-bordered table-hover" id="sample_2">
                		<thead>
                			<tr>
                                <th>No</th>
                				<th>Product</th>
                                <th>Group</th>
                                <th>Warehouse</th>
                                <th>Opening</th>
                                <th>Ending</th>
                                <th>UOM</th>
                                <th>Status</th>
                				<th>Data Date</th>
                                <th></th>
                			</tr>
                		</thead>
                		<tbody>
                            @foreach($data as $key => $product)
                			<tr>
                				<td>{{ $key+1 }}</td>
                				<td>{{ $product->product_name }}</td>
                                <td>{{ $product->Materials->material_name }}</td>
                                <td>
                                    @if(!empty($product->warehouse_name))
                                    {{ $product->warehouse_name }}
                                    @endif
                                </td>
                                <td>{{ number_format($product->opening_amount,2,',','.')}}</td>
                                <td>{{ number_format($product->closing_amount,2,',','.')}}</td>
                                <td>{{ $product->Products->Uoms->name }}</td>
                                <td>
                                    @if( ($product->closing_amount) == '0')
                                        <label class="label label-sm label-danger">No Stock</label>
                                    @elseif(($product->closing_amount) <= ($product->min_stock))
                                        <label class="label label-sm label-warning">Low On Stock</label>
                                    @elseif(($product->closing_amount) >= ($product->min_stock))
                                        <label class="label label-sm label-success">Stock Normal</label>
                                    @endif
                                </td>
                				<td>{{date("d F Y H:i",strtotime($product->updated_at)) }}</td>
                                <td>
                                    <a class="btn btn-xs btn-success" title="Print Stock Card" href="{{ route('stock.pdf',$product->id) }}"><i class="fa fa-print"></i></a>
                                    <a class="btn btn-xs btn-info modalLg" href="#" value="{{ action('Apps\InventoryManagementController@stockCard',['id'=>$product->id]) }}" 
                                        title="Product Stock Card {{$product->Products->name }}" data-toggle="modal" data-target="#modalLg"><i class="fa fa-search"></i>
                                    </a>
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