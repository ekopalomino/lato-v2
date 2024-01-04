@extends('apps.layouts.main')
@section('header.title')
LATO | Show Request
@endsection
@section('content')
<div class="page-content">
    <div class="portlet box red ">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-database"></i> ATK Purchase Request
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
            {!! Form::model($data, ['method' => 'POST','route' => ['request.process', $data->id],'class' => 'form-horizontal']) !!}
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
                                    {!! Form::text('request_title', null, array('placeholder' => 'SAP Code','class' => 'form-control','readonly')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">From Warehouse</label>
                                <div class="col-md-6">
                                    {!! Form::text('request_wh_id', $data->Locations->name, array('placeholder' => 'Item Name','class' => 'form-control','readonly')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Request By</label>
                                <div class="col-md-6">
                                    {!! Form::text('to_wh', $data->Author->name, array('placeholder' => 'Item Name','class' => 'form-control','readonly')) !!}  
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Request Date</label>
                                <div class="col-md-6">
                                    {!! Form::date('created_at', $data->created_at, array('placeholder' => 'Item Name','class' => 'form-control','readonly')) !!}  
                                </div>
                            </div>
                            @if($data->status == '8')
                            <div class="form-group">
                                <label class="col-md-3 control-label">Request Status</label>
                                <div class="col-md-6">
                                    <select id="single" name="status" class="form-control select">
                                        <option value="">Please Select</option>
                                        <option value="13">Process</option>
                                        <option value="17">Close</option>
                                    </select>
                                </div> 
                            </div>
                            @elseif($data->status == '13')
                            <div class="form-group">
                                <label class="col-md-3 control-label">Request Status</label>
                                <div class="col-md-6">
                                    <select id="single" name="status" class="form-control select">
                                        <option value="">Please Select</option>
                                        <option value="17">Close</option>
                                    </select>
                                </div> 
                            </div>
                            @else
                            <div class="form-group">
                                <label class="col-md-3 control-label">Request Status</label>
                                <div class="col-md-6">
                                    {!! Form::text('status', $data->Statuses->name, array('placeholder' => 'Item Name','class' => 'form-control','readonly')) !!}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-hover" id="sample_2">
                                <thead>
                                    <tr>
                                        <th>Account Code</th>
                                        <th>Account Name</td>
                                        <th>Warehouse</th>
                                        <th>Product Name</th>
                                        <th>Request</th>
                                        <th>UOM</th>
                                        <th>Price</th>
                                        <th>Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($details as $key => $val)
                                    <tr>
                                        <td>{!! Form::text('coa_code[]', $val->Coas->coa_code, array('placeholder' => 'Customer PO', 'class' => 'form-control','readonly')) !!}</td>
                                        <td>{!! Form::text('coa_name[]', $val->Coas->coa_name, array('placeholder' => 'Customer PO', 'class' => 'form-control','readonly')) !!}</td>
                                        <td>{!! Form::text('warehouse[]', $val->Warehouses->name, array('placeholder' => 'Customer PO', 'class' => 'form-control','readonly')) !!}</td>
                                        <td>{!! Form::text('product_name[]', $val->product_name, array('placeholder' => 'Customer PO', 'class' => 'form-control','readonly')) !!}</td>
                                        <td>{!! Form::number('remaining_qty[]', number_format($val->remaining_qty,0,',','.'), array('placeholder' => 'Jumlah','class' => 'form-control','required','readonly')) !!}</td>
                                        <td>{!! Form::text('uom_name[]', $val->Uoms->name, array('placeholder' => 'Customer PO', 'class' => 'form-control','readonly')) !!}</td>
                                        <td>{!! Form::text('purchase_price[]', number_format($val->purchase_price,0,',','.'), array('placeholder' => 'Jumlah','class' => 'form-control','readonly')) !!}</td>
                                        <td>{!! Form::text('sub_total[]', number_format($val->sub_total,0,',','.'), array('placeholder' => 'Jumlah','class' => 'form-control','readonly')) !!}</td>
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