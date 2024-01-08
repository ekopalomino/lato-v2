@extends('apps.layouts.main')
@section('header.title')
LATO | Create Role
@endsection
@section('header.plugins')
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="page-content">
	<div class="row">
        <div class="portlet light bordered">
            <div class="portlet-title"> 
                <div class="caption">
                    <i class="icon-speech font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Access Role Form</span>
                </div>
            </div>
            <div class="portlet-body">
            	<div class="row">
            		<div class="col-md-12">
            			{!! Form::open(array('route' => 'role.store','method'=>'POST', 'class' => 'horizontal-form')) !!}
						@csrf
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label"><strong>Role Name</strong></label>
										{!! Form::text('name', null, array('placeholder' => 'Role Name','class' => 'form-control')) !!}
									</div>
								</div>
							</div>
                            <div class="row">
								<table class="table table-striped table-bordered table-hover order-column" id="role">
									<thead>
										<tr>
											<th>No</th>
											<th>Function</th>
											<th>Access</th>
											<th>Create</th>
											<th>Edit</th>
											<th>Delete</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>1</td>
											<td>Configuration</td>
											<td>
												<label class="mt-checkbox mt-checkbox-outline">
													<input type="checkbox" value="1" name="permission[]" />
													<span></span>
												</label>
											</td>
											<td>
												<label class="mt-checkbox mt-checkbox-outline">
													<input type="checkbox" value="17" name="permission[]" />
													<span></span>
												</label>
											</td>
											<td>
												<label class="mt-checkbox mt-checkbox-outline">
													<input type="checkbox" value="18" name="permission[]" />
													<span></span>
												</label>
											</td>
											<td>
												<label class="mt-checkbox mt-checkbox-outline">
													<input type="checkbox" value="19" name="permission[]" />
													<span></span>
												</label>
											</td>
										</tr>
										<tr>
											<td>2</td>
											<td>User Management</td>
											<td>
												<label class="mt-checkbox mt-checkbox-outline">
													<input type="checkbox" value="2" name="permission[]" />
													<span></span>
												</label>
											</td>
											<td>
												<label class="mt-checkbox mt-checkbox-outline">
													<input type="checkbox" value="20" name="permission[]" />
													<span></span>
												</label>
											</td>
											<td>
												<label class="mt-checkbox mt-checkbox-outline">
													<input type="checkbox" value="21" name="permission[]" />
													<span></span>
												</label>
											</td>
											<td>
												<label class="mt-checkbox mt-checkbox-outline">
													<input type="checkbox" value="22" name="permission[]" />
													<span></span>
												</label>
											</td>
										</tr>
										<tr>
											<td>3</td>
											<td>Products</td>
											<td>
												<label class="mt-checkbox mt-checkbox-outline">
													<input type="checkbox" value="5" name="permission[]" />
													<span></span>
												</label>
											</td>
											<td>
												<label class="mt-checkbox mt-checkbox-outline">
													<input type="checkbox" value="31" name="permission[]" />
													<span></span>
												</label>
											</td>
											<td>
												<label class="mt-checkbox mt-checkbox-outline">
													<input type="checkbox" value="32" name="permission[]" />
													<span></span>
												</label>
											</td>
											<td>
												<label class="mt-checkbox mt-checkbox-outline">
													<input type="checkbox" value="33" name="permission[]" />
													<span></span>
												</label>
											</td>
										</tr>
										<tr>
											<td>4</td>
											<td>Request</td>
											<td>
												<label class="mt-checkbox mt-checkbox-outline">
													<input type="checkbox" value="51" name="permission[]" />
													<span></span>
												</label>
											</td>
											<td>
												<label class="mt-checkbox mt-checkbox-outline">
													<input type="checkbox" value="52" name="permission[]" />
													<span></span>
												</label>
											</td>
											<td>
											</td>
											<td>
											</td>
										</tr>
										<tr>
											<td>5</td>
											<td>Purchasing</td>
											<td>
												<label class="mt-checkbox mt-checkbox-outline">
													<input type="checkbox" value="4" name="permission[]" />
													<span></span>
												</label>
											</td>
											<td>
												<label class="mt-checkbox mt-checkbox-outline">
													<input type="checkbox" value="27" name="permission[]" />
													<span></span>
												</label>
											</td>
											<td>
												<label class="mt-checkbox mt-checkbox-outline">
													<input type="checkbox" value="28" name="permission[]" />
													<span></span>
												</label>
											</td>
											<td>
												<label class="mt-checkbox mt-checkbox-outline">
													<input type="checkbox" value="29" name="permission[]" />
													<span></span>
												</label>
											</td>
											
										</tr>
										<tr>
											<td>6</td>
											<td>Inventory</td>
											<td>
												<label class="mt-checkbox mt-checkbox-outline">
													<input type="checkbox" value="6" name="permission[]" />
													<span></span>
												</label>
											</td>
											<td>
												<label class="mt-checkbox mt-checkbox-outline">
													<input type="checkbox" value="34" name="permission[]" />
													<span></span>
												</label>
											</td>
											<td>
												<label class="mt-checkbox mt-checkbox-outline">
													<input type="checkbox" value="35" name="permission[]" />
													<span></span>
												</label>
											</td>
											<td>
												<label class="mt-checkbox mt-checkbox-outline">
													<input type="checkbox" value="37" name="permission[]" />
													<span></span>
												</label>
											</td>
											>
										</tr>
										<tr>
											<td>7</td>
											<td>Reports</td>
											<td>
												<label class="mt-checkbox mt-checkbox-outline">
													<input type="checkbox" value="48" name="permission[]" />
													<span></span>
												</label>
											</td>
											<td>
												<label class="mt-checkbox mt-checkbox-outline">
													<input type="checkbox" value="49" name="permission[]" />
													<span></span>
												</label>
											</td>
											<td>
												
											</td>
											<td>
												
											</td>
											
										</tr>
									</tbody>
								</table>
							</div>
                            <div class="form-actions right">
	                            <a button type="button" class="btn default" href="{{ route('role.index') }}">Cancel</a>
	                            <button type="submit" class="btn blue">
	                                <i class="fa fa-check"></i> Save</button>
	                        </div>
	                    {!! Form::close() !!}
	                </div>
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
@endsection
@section('footer.scripts')
<script src="{{ asset('assets/pages/scripts/table-datatables-scroller.min.js') }}" type="text/javascript"></script>
@endsection