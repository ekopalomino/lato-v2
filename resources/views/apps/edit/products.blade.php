@extends('apps.layouts.main')
@section('header.title')
ATK Management | Edit Product
@endsection
@section('content')
<div class="page-content">
    <div class="portlet box red ">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-database"></i> Edit Product 
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
            {!! Form::model($data, ['method' => 'POST','route' => ['product.update', $data->id],'class' => 'form-horizontal','files' => 'true']) !!}
                @csrf
                <div class="form-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">SAP Code</label>
                        <div class="col-md-4">
                            {!! Form::text('sap_code', null, array('placeholder' => 'SAP Code','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Product Name</label>
                        <div class="col-md-4">
                            {!! Form::text('name', null, array('placeholder' => 'Product Name','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Material Group</label>
                        <div class="col-md-4">
                            {!! Form::select('material_group_id', $materials,old('material_group_id'), array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Product Category</label>
                        <div class="col-md-4">
                            {!! Form::select('category_id', $categories,old('category_id'), array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Product UOM</label>
                        <div class="col-md-4">
                            {!! Form::select('uom_id', $uoms,old('uom_id'), array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Minimal Stock</label>
                        <div class="col-md-4">
                            {!! Form::text('min_stock', null, array('placeholder' => 'Product Minimal Stock','class' => 'form-control')) !!} 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Product Cost Price</label>
                        <div class="col-md-4">
                            {!! Form::text('price', null, array('placeholder' => 'Product Cost Price','class' => 'form-control')) !!} 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Product Image</label>
                        <div class="col-md-4">
                            {!! Form::file('image', null, array('placeholder' => 'Product Image','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Product Specification</label>
                        <div class="col-md-4">
                            {!! Form::textarea('specification', null, array('placeholder' => 'Product Specification','class' => 'form-control')) !!} 
                        </div>
                    </div>
                    <div class="form-actions right">
                        <a button type="button" class="btn default" href="{{ route('product.index') }}">Cancel</a>
                        <button type="submit" class="btn blue">
                        <i class="fa fa-check"></i> Update</button>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection