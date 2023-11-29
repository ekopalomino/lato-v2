@extends('apps.layouts.main')
@section('header.title')
ATK Management | Add Request
@endsection
@section('header.plugins')
<link href="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="page-content">
    <div class="portlet box red ">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-database"></i> Add Request 
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
            {!! Form::open(array('route' => 'request.store','method'=>'POST', 'class' => 'form-horizontal')) !!}
            @csrf
            <div class="form-body">
            	<div class="row">
                    <div class="col-md-6">
            			<div class="form-group">
                            <label class="col-md-2 control-label">Request Title</label>
                            <div class="col-md-5">
                                {!! Form::text('request_name', null, array('placeholder' => 'Request Title','class' => 'form-control')) !!}
                            </div>
                        </div>
            		</div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Request Number</label>
                            <div class="col-md-5">
                                {!! Form::text('request_ref', $refs, array('placeholder' => 'Customer PO', 'class' => 'form-control','readonly')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Status</label>
                            <div class="col-md-5">
                                {!! Form::text('status', 'Draft', array('class' => 'form-control','readonly')) !!}
                            </div>
                        </div>
                    </div>
            	</div>       		
            	<div class="row">
            		<div class="col-md-12">
	            		<table class="table table-striped table-bordered table-hover" id="sample_2">
	            			<thead>
	            				<tr>
	            					<th>Produk</th>
	            					<th>Jumlah</th>
	            					<th>Satuan</th>
	            				</tr>
	            			</thead>
	            			<tbody>
	            				<tr>
	            					<td>{!! Form::select('product_id[]', [null=>'Please Select'] + $products,[], array('class' => 'form-control')) !!}</td>
                    				<td>{!! Form::number('quantity[]', null, array('placeholder' => 'Jumlah','class' => 'form-control','required')) !!}</td>
                    				<td>{!! Form::select('uom_id[]', [null=>'Please Select'] + $uoms,[], array('class' => 'form-control','required')) !!}</td>
                    			</tr>
                                <tr>
                                    <td>{!! Form::select('product_id[]', [null=>'Please Select'] + $products,[], array('class' => 'form-control')) !!}</td>
                                    <td>{!! Form::number('quantity[]', null, array('placeholder' => 'Jumlah','class' => 'form-control')) !!}</td>
                                    <td>{!! Form::select('uom_id[]', [null=>'Please Select'] + $uoms,[], array('class' => 'form-control')) !!}</td>
                                    </tr>
                                <tr>
                                    <td>{!! Form::select('product_id[]', [null=>'Please Select'] + $products,[], array('class' => 'form-control')) !!}</td>
                                    <td>{!! Form::number('quantity[]', null, array('placeholder' => 'Jumlah','class' => 'form-control')) !!}</td>
                                    <td>{!! Form::select('uom_id[]', [null=>'Please Select'] + $uoms,[], array('class' => 'form-control')) !!}</td>
                                    </tr>
                                <tr>
                                    <td>{!! Form::select('product_id[]', [null=>'Please Select'] + $products,[], array('class' => 'form-control')) !!}</td>
                                    <td>{!! Form::number('quantity[]', null, array('placeholder' => 'Jumlah','class' => 'form-control')) !!}</td>
                                    <td>{!! Form::select('uom_id[]', [null=>'Please Select'] + $uoms,[], array('class' => 'form-control')) !!}</td>
                                </tr>
                                <tr>
                                    <td>{!! Form::select('product_id[]', [null=>'Please Select'] + $products,[], array('class' => 'form-control')) !!}</td>
                                    <td>{!! Form::number('quantity[]', null, array('placeholder' => 'Jumlah','class' => 'form-control')) !!}</td>
                                    <td>{!! Form::select('uom_id[]', [null=>'Please Select'] + $uoms,[], array('class' => 'form-control')) !!}</td>
                                </tr>
                                <tr>
                                    <td>{!! Form::select('product_id[]', [null=>'Please Select'] + $products,[], array('class' => 'form-control')) !!}</td>
                                    <td>{!! Form::number('quantity[]', null, array('placeholder' => 'Jumlah','class' => 'form-control')) !!}</td>
                                    <td>{!! Form::select('uom_id[]', [null=>'Please Select'] + $uoms,[], array('class' => 'form-control')) !!}</td>
                                </tr>
                                <tr>
                                    <td>{!! Form::select('product_id[]', [null=>'Please Select'] + $products,[], array('class' => 'form-control')) !!}</td>
                                    <td>{!! Form::number('quantity[]', null, array('placeholder' => 'Jumlah','class' => 'form-control')) !!}</td>
                                    <td>{!! Form::select('uom_id[]', [null=>'Please Select'] + $uoms,[], array('class' => 'form-control')) !!}</td>
                                </tr>
                                <tr>
                                    <td>{!! Form::select('product_id[]', [null=>'Please Select'] + $products,[], array('class' => 'form-control')) !!}</td>
                                    <td>{!! Form::number('quantity[]', null, array('placeholder' => 'Jumlah','class' => 'form-control')) !!}</td>
                                    <td>{!! Form::select('uom_id[]', [null=>'Please Select'] + $uoms,[], array('class' => 'form-control')) !!}</td>
                                </tr>
                                <tr>
                                    <td>{!! Form::select('product_id[]', [null=>'Please Select'] + $products,[], array('class' => 'form-control')) !!}</td>
                                    <td>{!! Form::number('quantity[]', null, array('placeholder' => 'Jumlah','class' => 'form-control')) !!}</td>
                                    <td>{!! Form::select('uom_id[]', [null=>'Please Select'] + $uoms,[], array('class' => 'form-control')) !!}</td>
                                </tr>
                                <tr>
                                    <td>{!! Form::select('product_id[]', [null=>'Please Select'] + $products,[], array('class' => 'form-control')) !!}</td>
                                    <td>{!! Form::number('quantity[]', null, array('placeholder' => 'Jumlah','class' => 'form-control')) !!}</td>
                                    <td>{!! Form::select('uom_id[]', [null=>'Please Select'] + $uoms,[], array('class' => 'form-control')) !!}</td>
                                </tr>
	            			</tbody>
	            		</table>
	            	</div>
            	</div>
            	<div class="form-actions right">
                    <a button type="button" class="btn default" href="{{ route('request.index') }}">Cancel</a>
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
@section('footer.plugins')
<script src="{{ asset('assets//global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
@endsection
@section('footer.scripts')
<script src="{{ asset('assets/pages/scripts/components-date-time-pickers.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/pages/scripts/form-samples.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/pages/scripts/components-select2.min.js') }}" type="text/javascript"></script>
@endsection