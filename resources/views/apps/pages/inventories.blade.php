@extends('apps.layouts.main')
@section('header.title')
LATO | Product Stock 
@endsection
@section('header.styles')
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="page-content">
	<div class="row">
		<div class="col-md-12">
            <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-database"></i>Product Stock 
                    </div>
                    
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="inventory">
                		<thead>
                			<tr>
                                <th>Product</th>
                                <th>Group</th>
                                <th>Warehouse</th>
                                <th>Opening</th>
                                <th>Ending</th>
                                <th>Status</th>
                                <th>Updated Date</th>
                                <th></th>
                			</tr>
                		</thead>
                	</table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer.plugins')
<script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
@endsection
@section('footer.scripts')
<script src="{{ asset('assets/pages/scripts/table-datatables-buttons.min.js') }}" type="text/javascript"></script>
<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content'); 
    $(document).ready(function(){

       // Initialize
       var empTable = $('#inventory').DataTable({
             processing: true,
             serverSide: true,
             orderable: true, 
             searchable: true,
             ajax: "{{ route('inventory.index') }}",
             columns: [
                { data: 'product_name' },
                { data: 'materials', name: 'material_group_id'},
                { data: 'warehouse_name' },
                { data: 'opening_amount' },
                { data: 'closing_amount' },
                { data: 'statuses' },
                { data: 'updated_at', name: 'updated_at'},
                { data: 'action' },
             ]
       });
    });
</script>
@endsection