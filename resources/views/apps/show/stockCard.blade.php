@extends('apps.layouts.main')
@section('content')
<div class="page-content">
	<div class="row">
		<div class="col-md-12">
			<table class="table table-striped table-bordered table-hover" id="sample_2">
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
                        <th>Notes</th>
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
                            Penjualan
                            @elseif($val->type == 3)
                            Pembelian
                            @elseif($val->type == 4)
                            Internal Transfer
                            @elseif($val->type == 5)
                            Pengiriman
                            @elseif($val->type == 6)
                            Penerimaan Brg
                            @elseif($val->type == 7)
                            Manufaktur
                            @elseif($val->type == 8)
                            Cancel Pengiriman
                            @elseif($val->type == 9)
                            Rekap Stok Awal Bulan
                            @elseif($val->type == 10)
                            Retur Barang
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
            <div class="row">
				<div class="col-md-12 text-center">
					<nav aria-label="Page navigation">				
						{{ $data->links() }}
					</nav>
				</div>
			</div>          
		</div>
	</div>
</div>       
@endsection
@section('footer.scripts')
<script type="text/javascript">
    $(document).on('ajaxComplete ajaxReady ready', function () {
        $('ul.pagination li a').off('click').on('click', function (e) {
            $("#modalLg").modal('show');
            $('#modalLgContent').load($(this).attr('href'));
            $('#modalLgTitle').html($(this).attr('title'));
            e.preventDefault();
        });
    });
</script>
@endsection 