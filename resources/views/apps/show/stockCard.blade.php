@extends('apps.layouts.main')
@section('header.title')
LATO | Stock Card
@endsection
@section('content') 
<div class="page-content">
	<div class="row">
		<div class="col-md-12">
            <div class="table-scrollable">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Trans Date</th>
                            <th>Type</th>
                            <th>Ref No</th>
                            <th>Warehouse</th>
                            <th>Stock In</th>
                            <th>Stock Out</th>
                            <th>Balance</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $key=>$val)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{date("d F Y H:i",strtotime($val->created_at)) }}</td>
                            <td>
                                @if($val->type == 1)
                                Adjustment
                                @elseif($val->type == 2)
                                Request
                                @elseif($val->type == 3)
                                Purchase
                                @elseif($val->type == 4)
                                Usage
                                @elseif($val->type == 5)
                                Stock Opname
                                @endif
                            </td>
                            <td>{{ $val->reference_id}}</td>
                            <td>{{ $val->warehouse_name}}</td>
                            <td>{{ number_format($val->incoming,2,',','.')}}</td>
                            <td>{{ number_format($val->outgoing,2,',','.')}}</td>
                            <td>{{ number_format($val->remaining,2,',','.')}}</td>
                            <td>{{ $val->notes }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>         
		</div>
	</div>
</div>       
@endsection