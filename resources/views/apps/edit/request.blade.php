@extends('apps.layouts.main')
@section('header.title')
ATK Management | Show Request
@endsection
@section('content')
<div class="page-content">
    <div class="portlet box red ">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-database"></i> ATK Request
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
            {!! Form::model($data, ['method' => 'POST','route' => ['request.index', $data->id],'class' => 'form-horizontal']) !!}
                @csrf
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Request No</label>
                                <div class="col-md-6">
                                    {!! Form::text('request_ref', null, array('placeholder' => 'SAP Code','class' => 'form-control','readonly')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Request Name</label>
                                <div class="col-md-6">
                                    {!! Form::text('request_name', null, array('placeholder' => 'SAP Code','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">From Warehouse</label>
                                <div class="col-md-6">
                                    {!! Form::text('from_wh', $data->From->name, array('placeholder' => 'Item Name','class' => 'form-control','readonly')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">To Warehouse</label>
                                <div class="col-md-6">
                                    {!! Form::text('to_wh', $data->To->branch_name, array('placeholder' => 'Item Name','class' => 'form-control','readonly')) !!}  
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Status</label>
                                <div class="col-md-6">
                                    {!! Form::text('to_wh', $data->To->branch_name, array('placeholder' => 'Item Name','class' => 'form-control','readonly')) !!}  
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
                                    @foreach($details as $key => $val)
                                    <tr>
                                        <td>{{ $val->product_name }}</td>
                                        <td>{{ $val->request_qty }}</td>
                                        <td>{{ $val->Uoms->name }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
            	</div>
                    <div class="form-actions right">
                        <a button type="button" class="btn default" href="{{ route('request.index') }}">Cancel</a>
                        <button type="submit" class="btn blue">
                        <i class="fa fa-check"></i> Update</button>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection