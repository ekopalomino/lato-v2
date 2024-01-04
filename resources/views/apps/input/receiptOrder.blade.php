@extends('apps.layouts.main')
@section('header.title')
LATO | Add Receipt Order 
@endsection
@section('content')
<div class="page-content">
    <div class="portlet box red ">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-database"></i> Receipt Order Form  
            </div>
        </div>
        <div class="portlet-body form">
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
            {!! Form::open(array('route' => 'receipt.store','method'=>'POST', 'class' => 'horizontal-form')) !!}
            @csrf
            <div class="form-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="control-label">Nomor PO</label>
                            {!! Form::text('request_ref', $purchases->request_ref, array('class' => 'form-control','readonly'=>'true')) !!}
                        </div>
                    </div>
                </div>
            	<div class="row">
            		<div class="col-md-12">
	            		<table class="table table-striped table-bordered table-hover" id="sample_2">
	            			<thead>
                                <tr>
	            					<th>Product</th>
                                    <th>Warehouse</th>
	            					<th>Order Qty</th>
                                    <th>Order UOM</th>
                                    <th>Delivery Qty</th>
                                    <th>Damaged Qty</th>
	            					<th>Delivery UOM</th>
	            					<th></th>
	            				</tr>
	            			</thead>
	            			<tbody>
                                @foreach($purchases->purchaseItems as $key=>$item)
	            				<tr>
	            					<td>{{ Form::hidden('product_id[]', $item->product_id) }}{!! Form::text('product[]', $item->product_name, array('placeholder' => 'Produk','id' => 'product','class' => 'form-control','readonly'=>'true')) !!}</td>
                                    <td>{{ Form::hidden('warehouse_id[]', $item->warehouse_id) }}{!! Form::text('warehouse[]', $item->Warehouses->name, array('placeholder' => 'Produk','id' => 'product','class' => 'form-control','readonly'=>'true')) !!}</td>
                    				<td>{!! Form::number('pesanan[]', number_format($item->remaining_qty,0,',','.'), array('placeholder' => 'Quantity','class' => 'form-control','readonly'=>'true')) !!}</td>
                                    <td>{!! Form::text('uoms[]', $item->Uoms->name, array('placeholder' => 'Customer PO', 'class' => 'form-control','readonly')) !!}</td>
                                    <td>{!! Form::number('pengiriman[]', null, array('placeholder' => 'Jumlah Dikirim','class' => 'form-control')) !!}</td>
                                    <td>{!! Form::number('rusak[]', null, array('placeholder' => 'Jumlah Rusak','class' => 'form-control')) !!}</td>
                                    <td>{{ Form::hidden('uom_order[]', $item->uom_id) }}{!! Form::select('uom_id[]', $uoms,$item->uom_id, array('class' => 'form-control')) !!}</td>
                    				<td>
                                        {{ Form::hidden('id', $key+1) }}
                                        <input type="button" value="Delete" class="btn red" onclick="deleteRow(this)">
                                    </td>
                                    {{Form::hidden('price[]', $item->purchase_price)}}
	            				</tr>
                                @endforeach
	            			</tbody>
	            		</table>
	            	</div>
            	</div> 
            	<div class="form-actions right">
                    <a button type="button" class="btn default" href="{{ route('receipt.index') }}">Cancel</a>
                    <button type="submit" class="btn blue">
                    <i class="fa fa-check"></i> Save</button>
                </div>
            </div>
            {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer.scripts')
<script src="{{ asset('assets/pages/scripts/form-samples.min.js') }}" type="text/javascript"></script>
<script>
function deleteRow(r) {
  var i = r.parentNode.parentNode.rowIndex;
  document.getElementById("sample_2").deleteRow(i);
}
</script>
@endsection