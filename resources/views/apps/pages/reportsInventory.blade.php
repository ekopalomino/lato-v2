@extends('apps.layouts.main')
@section('header.title')
LATO | Stock Card
@endsection
@section('header.styles')
<link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="page-content">
	<div class="portlet box red ">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-database"></i> Stock Card Report
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
			{!! Form::open(array('route' => 'inventory.view','method'=>'POST', 'class' => 'horizontal-form')) !!}
			@csrf
			<div class="form-body">
				<div class="row">
					<div class="col-md-12">
						<div class="row">
                            <div class="col-md-4">
                            	<div class="form-group">
									<label class="control-label">Products</label>
									<select id="product" name="product" class="form-control select2">
										<option></option>
										@foreach($getProduct as $product)
										<option value="{{ $product->id }}">{{ $product->name}}</option>
										@endforeach
									</select>

								</div>    		
								<div class="form-group">
									<label class="control-label">Location</label>
									<select id="location" name="location" class="form-control select2">
										<option></option>
										@foreach($getLocation as $location)
										<option value="{{ $location->name }}">{{ $location->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
                            <div class="col-md-2">
                                <div class="form-group">
									<label class="control-label">From</label>
									{!! Form::date('from_date', '', array('id' => 'datepicker','class' => 'form-control')) !!}
								</div>
								<div class="form-group">
									<label class="control-label">To</label>
									{!! Form::date('to_date', '', array('id' => 'datepicker','class' => 'form-control')) !!}
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="form-actions left">
					<a button type="button" class="btn default" href="{{ route('inventory.table') }}">Reset</a> 
					<button type="submit" class="btn blue">
						<i class="fa fa-play"></i> Run
					</button>
				</div>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endsection
@section('footer.plugins')
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
@endsection
@section('footer.scripts')
<script src="{{ asset('assets/pages/scripts/components-select2.min.js') }}" type="text/javascript"></script>
@endsection
