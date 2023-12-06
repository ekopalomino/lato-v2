@extends('apps.layouts.main')
@section('content')
<div class="page-content">
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
	<div class="row">
		<div class="col-md-12">
			{!! Form::model($data, ['method' => 'POST','route' => ['material.update', $data->id]]) !!}
            @csrf
            <div class="row">
            	<div class="col-md-12">
                	<div class="form-group">
                		<label class="control-label">Material Group Name</label>
                		{!! Form::text('material_name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                	</div>
                    <div class="form-group">
                        <label class="control-label">Chart of Account</label>
                        {!! Form::select('account_id', $coas,old('account_id'), array('class' => 'form-control')) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="close" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                <button id="register" type="submit" class="btn green">Update</button>
            </div>
            {!! Form::close() !!}
		</div>
	</div>
</div>       
@endsection
