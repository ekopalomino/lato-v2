@extends('apps.layouts.main')
@section('header.title')
LATO | Inventory Checklist
@endsection
@section('content')
<div class="page-content">
    <div class="portlet box red ">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-database"></i> Inventory Checklist
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
            {!! Form::model($data, ['method' => 'POST','route' => ['product.warehouse', $data->id]]) !!}
                @csrf
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="m-heading-1 border-red m-bordered">
                                <h3>Select Warehouse</h3>
                                @foreach($warehouses as $warehouse)
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox" name="warehouse_name[]" value="{{ $warehouse->id }}"> {{ $warehouse->name }}
                                    <span></span>
                                </label>
                                {{ Form::hidden('product_name[]', $data->name) }}
                                {{ Form::hidden('product_stock[]', $data->min_stock) }}
                                @endforeach
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