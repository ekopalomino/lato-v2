@extends('apps.layouts.main')
@section('header.title')
LATO | Stock Usage Report
@endsection
@section('content')
<div class="page-content">
	<div class="portlet box red ">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-database"></i> Stock Usage Report
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
			{!! Form::open(array('route' => 'inventory-table.view','method'=>'POST', 'class' => 'horizontal-form')) !!}
			@csrf
			<div class="form-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">From</label>
							{!! Form::date('from_date', '', array('id' => 'datepicker','class' => 'form-control')) !!}
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">To</label>
							{!! Form::date('to_date', '', array('id' => 'datepicker','class' => 'form-control')) !!}
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">Product</label>
							{!! Form::select('product', [null=>'Please Select'] + $getProduct,[], array('class' => 'form-control')) !!}
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">Location</label>
							{!! Form::select('location', [null=>'Please Select'] + $getWarehouse,[], array('class' => 'form-control')) !!}
						</div>
					</div>
				</div>
				<div class="form-actions left">
					<a button type="button" class="btn default" href="{{ route('inventory.table') }}">Cancel</a>
					<button type="submit" class="btn blue">
						<i class="fa fa-check"></i> Run
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection