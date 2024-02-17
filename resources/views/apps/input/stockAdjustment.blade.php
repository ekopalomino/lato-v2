@extends('apps.layouts.main')
@section('header.title')
LATO | Stock Adjustment
@endsection
@section('content') 
<div class="page-content">
    <div class="portlet box red ">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-database"></i> Stock Adjustment
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
            {!! Form::model($data, ['method' => 'POST','route' => ['store.adjust', $data->id],'class' => 'form-horizontal']) !!}
            @csrf
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">Product Name</label>
                    <div class="col-md-4">
                        {!! Form::text('product_name', null, array('placeholder' => 'Branch Name','class' => 'form-control','readonly')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Warehouse Name</label>
                    <div class="col-md-4">
                        {!! Form::text('warehouse_name', null, array('placeholder' => 'Branch Name','class' => 'form-control','readonly')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Plus</label>
                    <div class="col-md-4">
                        {!! Form::number('plus_amount', null, array('placeholder' => 'Plus Amount','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Minus</label>
                    <div class="col-md-4">
                        {!! Form::number('min_amount', null, array('placeholder' => 'Minus Amount','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Notes</label>
                    <div class="col-md-4">
                        {!! Form::textarea('notes', null, array('placeholder' => 'Notes','class' => 'form-control')) !!}
                    </div>
                </div>
                {{ Form::hidden('product_id', $data->product_id) }}
                {{ Form::hidden('product_name', $data->product_name) }}
                {{ Form::hidden('warehouse_name', $data->warehouse_name) }}
                <div class="form-actions right">
                    <a button type="button" class="btn default" href="{{ route('inventory.index') }}">Cancel</a>
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