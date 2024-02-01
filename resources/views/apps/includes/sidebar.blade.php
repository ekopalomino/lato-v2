<div class="page-sidebar-wrapper">
	<div class="page-sidebar navbar-collapse collapse">
		<ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
			<li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler">
                    <span></span>
                </div>
            </li>
            <li class="nav-item {{ set_active('dashboard.index') }}">
                <a href="{{ route('dashboard.index') }}" class="nav-link">
                    <i class="icon-home"></i>
                    <span class="title">Dashboard</span>
                    <span class="selected"></span>
                </a>
            </li>
            @can('Can Access Settings')
            <li class="nav-item {{ set_active(['coas.index','branch.index','warehouse.index','uom-cat.index','uom-val.index']) }}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-settings"></i>
                    <span class="title">Configuration</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item {{ set_active(['coas.index']) }}">
                        <a href="{{ route('coas.index') }}" class="nav-link">
                            <span class="title">Chart of Account</span>
                        </a>
                    </li>
                    <li class="nav-item {{ set_active(['branch.index']) }}">
                        <a href="{{ route('branch.index') }}" class="nav-link">
                            <span class="title">Branch</span>
                        </a>
                    </li>
                    <li class="nav-item {{ set_active(['warehouse.index']) }}">
                        <a href="{{ route('warehouse.index') }}" class="nav-link">
                            <span class="title">Warehouse</span>
                        </a>
                    </li>
                    <li class="nav-item {{ set_active(['uom-cat.index','uom-val.index']) }}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title">Unit of Measurement</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li class="nav-item {{ set_active(['uom-cat.index']) }}">
                                <a href="{{ route('uom-cat.index') }}" class="nav-link ">
                                    <span class="title">Category</span>
                                </a>
                            </li>
                            <li class="nav-item {{ set_active(['uom-val.index']) }}">
                                <a href="{{ route('uom-val.index') }}" class="nav-link ">
                                    <span class="title">Convertion Rate</span>
                                </a>
                            </li>
                        </ul>
                    </li>                                   
                </ul>
            </li>
            @endcan
            @can('Can Access Users')
            <li class="nav-item {{ set_active(['user.index','user.profile','role.index','uker.index','user.log','role.create','role.edit']) }}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-users"></i>
                    <span class="title">User Management</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item {{ set_active(['user.index','user.profile']) }}">
                        <a href="{{ route('user.index') }}" class="nav-link ">
                            <span class="title">Users</span>
                        </a>
                    </li>
                    <li class="nav-item {{ set_active(['role.index','role.create','role.edit']) }}">
                        <a href="{{ route('role.index') }}" class="nav-link ">
                            <span class="title">Roles</span>
                        </a>
                    </li>
                    <li class="nav-item {{ set_active(['user.log']) }}">
                        <a href="{{ route('user.log') }}" class="nav-link ">
                            <span class="title">Activity Log</span>
                        </a>
                    </li>                                    
                </ul>
            </li>
            @endcan
            @can('Can Access Products')
            <li class="nav-item {{ set_active(['product-cat.index','product.index','product.create','product.edit','product.page','material.index']) }}">
            	<a href="javascript:;" class="nav-link nav-toggle">
            		<i class="icon-social-dropbox"></i>
            		<span class="title">Product</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                	<li class="nav-item {{ set_active(['product.index','product.create','product.edit','product.page']) }}">
                		<a href="{{ route('product.index') }}" class="nav-link ">
                            <span class="title">Product Catalog</span>
                        </a>
                    </li>
                    <li class="nav-item {{ set_active(['material.index']) }}">
                		<a href="{{ route('material.index') }}" class="nav-link ">
                            <span class="title">Material Group</span>
                        </a>
                    </li>
                    <li class="nav-item {{ set_active(['product-cat.index']) }}">
                		<a href="{{ route('product-cat.index') }}" class="nav-link ">
                            <span class="title">Product Category</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan
            <li class="nav-item {{ set_active(['request.index','request.create','request.show','transfer.index','add.transfer','transfer.view']) }}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-basket-loaded"></i>
                    <span class="title">Product Request</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @can('Can Access Purchasing')
                    <li class="nav-item {{ set_active(['request.index','request.create','request.show']) }}">
                        <a href="{{ route('request.index') }}" class="nav-link">
                            <span class="title">Purchase Request</span>
                            <span class="badge badge-danger">{{$purchases}}</span>
                        </a>
                    </li>
                    @endcan
                    <li class="nav-item {{ set_active(['transfer.index','add.transfer','transfer.view']) }}">
                        <a href="{{ route('transfer.index') }}" class="nav-link ">
                            <span class="title">ATK Request</span>
                            <span class="badge badge-danger">{{$transfers}}</span>
                        </a>
                    </li>
                </ul>
            </li>
            @can('Can Access Inventories') 
            <li class="nav-item  {{ set_active(['inventory.index','inventory.adjust','receipt.index','receipt.search','receipt.get','receipt.edit','inventory.card','purchase.show']) }}">
            	<a href="javascript:;" class="nav-link nav-toggle">
            		<i class="icon-grid"></i>
            		<span class="title">Inventories</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                	<li class="nav-item  {{ set_active(['inventory.index','inventory.card']) }}">
                		<a href="{{ route('inventory.index') }}" class="nav-link ">
                            <span class="title">Stocks</span>
                        </a>
                    </li>
                    <li class="nav-item {{ set_active(['inventory.adjust']) }}">
                		<a href="{{ route('inventory.adjust') }}" class="nav-link ">
                            <span class="title">Adjustment</span>
                        </a>
                    </li>
                    <li class="nav-item {{ set_active(['receipt.index','receipt.search','receipt.get','receipt.edit']) }}">
                        <a href="{{ route('receipt.index') }}" class="nav-link ">
                            <span class="title">Goods Receipt</span>
                            <span class="badge badge-danger">{{$ex_receipt}}</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan
            <li class="nav-item {{ set_active(['inventory.table','inventory.view']) }}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-bar-chart"></i>
                    <span class="title">Reports</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item {{ set_active(['inventory.table','inventory.view']) }}">
                        <a href="{{ route('inventory.table') }}" class="nav-link ">
                            <span class="title">Stock Card</span>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a href="" class="nav-link ">
                            <span class="title">Usage</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>