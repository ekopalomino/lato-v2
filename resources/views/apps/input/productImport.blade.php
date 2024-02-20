@extends('apps.layouts.main')
@section('header.title')
LATO | Product Import
@endsection
@section('content')
<div class="page-content">
    <div class="portlet box red ">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-database"></i> Product Import
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
            {!! Form::open(array('route' => 'product.import','method'=>'POST','class' => 'form-horizontal','files' => 'true')) !!}
                @csrf
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="m-heading-1 border-red m-bordered">
                                <h3>How to Import Data</h3>
                                    <p>1. Download templates & data source from the right side column.</p>
                                    <p>2. Use id number from each data source to fill the category_id and uom_id field on product template.</p>
                                    <p>3. Make sure that SAP code are unique for every product.</p>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::file('atk', null, array('placeholder' => 'ATK Data','class' => 'form-control')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="portlet box blue ">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-branch"></i> Import Templates
                                    </div>
                                </div>
                                <div class="portlet-body form">
                                    <div class="form-body">
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">Product</label>
                                            <div class="col-md-6">
                                            <a button type="button" class="btn default red" href="{{ route('product.template') }}">Download</a>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">Category</label>
                                            <div class="col-md-6">
                                            <a button type="button" class="btn default red" href="{{ route('product-cat.export') }}">Download</a>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">UOM</label>
                                            <div class="col-md-6">
                                            <a button type="button" class="btn default red" href="{{ route('uom.export') }}">Download</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions right">
                        <a button type="button" class="btn default" href="{{ route('product.index') }}">Cancel</a>
                        <button type="submit" class="btn blue">
                        <i class="fa fa-check"></i> Save</button>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection