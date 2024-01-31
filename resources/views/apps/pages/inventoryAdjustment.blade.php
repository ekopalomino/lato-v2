@extends('apps.layouts.main')
@section('header.title')
LATO | Stock Adjustment
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
                        <i class="fa fa-database"></i>Stock Adjustment
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="updateModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Stock Adjustment</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button> 
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="plus" >Plus</label>
                                        <input type="number" class="form-control" id="plus_amount" placeholder="Enter Stock"> 
                                    </div>
                                    <div class="form-group">
                                        <label for="minus" >Minus</label> 
                                        <input type="number" class="form-control" id="min_amount" placeholder="Enter Stcok"> 
                                    </div> 
                                    <div class="form-group">
                                        <label for="notes" >Notes</label> 
                                        <input type="textarea" class="form-control" id="notes" placeholder="Enter Note"> 
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" id="txt_empid" value="0">
                                    <button type="button" class="btn btn-success btn-sm" id="btn_save">Submit</button>
                                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                	<table class="table table-striped table-bordered table-hover" id="adjustment">
                		<thead>
                			<tr>
                                <th>Product</th>
                                <th>Group</th>
                                <th>Warehouse</th>
                                <th>Opening</th>
                                <th>Ending</th>
                                <th>Data Date</th>
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
       var empTable = $('#adjustment').DataTable({
             processing: true,
             serverSide: true,
             orderable: true, 
             searchable: true,
             ajax: "{{ route('inventory.index') }}",
             columns: [
                { data: 'product_name' },
                {data: 'materials', name: 'material_group_id'},
                { data: 'warehouse_name' },
                { data: 'opening_amount' },
                { data: 'closing_amount' },
                {data: 'created_at', name: 'created_at'},
                { data: 'action' },
             ]
       });
       // Update record
       $('#adjustment').on('click','.updateUser',function(){
            var id = $(this).data('id');

            $('#txt_empid').val(id);

            // AJAX request
            $.ajax({
                url: "{{ route('getEmployeeData') }}",
                type: 'post',
                data: {_token: CSRF_TOKEN,id: id},
                dataType: 'json',
                success: function(response){

                    if(response.success == 1){

                         $('#emp_name').val(response.emp_name);
                         $('#email').val(response.email);
                         $('#gender').val(response.gender);
                         $('#city').val(response.city);

                         empTable.ajax.reload();
                    }else{
                         alert("Invalid ID.");
                    }
                }
            });

       });

       // Save user 
       $('#btn_save').click(function(){
            var id = $('#txt_empid').val();

            var emp_name = $('#emp_name').val().trim();
            var email = $('#email').val().trim();
            var gender = $('#gender').val().trim();
            var city = $('#city').val().trim();

            if(emp_name !='' && email != '' && city != ''){

                 // AJAX request
                 $.ajax({
                     url: "{{ route('updateEmployee') }}",
                     type: 'post',
                     data: {_token: CSRF_TOKEN,id: id,emp_name: emp_name, email: email, gender: gender, city: city},
                     dataType: 'json',
                     success: function(response){
                         if(response.success == 1){
                              alert(response.msg);

                              // Empty and reset the values
                              $('#emp_name','#email','#city').val('');
                              $('#gender').val('Male');
                              $('#txt_empid').val(0);

                              // Reload DataTable
                              empTable.ajax.reload();

                              // Close modal
                              $('#updateModal').modal('toggle');
                         }else{
                              alert(response.msg);
                         }
                     }
                 });

            }else{
                 alert('Please fill all fields.');
            }
       });
       
    });
</script>
@endsection