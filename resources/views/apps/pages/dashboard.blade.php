@extends('apps.layouts.main')
@section('header.title')
FiberTekno | Dashboard
@endsection
@section('header.plugins')
<link href="{{ asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/morris/morris.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('header.styles')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawSalesChart);
    google.charts.setOnLoadCallback(drawProductChart);
    google.charts.setOnLoadCallback(drawCustomerChart);
    function drawSalesChart() {
        var sales = <?php echo $data; ?>;
        var data = google.visualization.arrayToDataTable(sales);
        var options = {
          title: 'Monthly Sales Figure',
          curveType: 'function',
          legend: { position: 'bottom' }
        };
        var chart = new google.visualization.LineChart(document.getElementById('linechart_material'));
        chart.draw(data, options);
    }
    function drawProductChart() {
        var items = <?php echo $items; ?>;
        var data = google.visualization.arrayToDataTable(items);
        var options = {
          title: 'Top 10 Products',
        };

        var chart = new google.visualization.BarChart(document.getElementById('Product_chart_div'));
        chart.draw(data, options);
    }
    function drawCustomerChart() {
        var customers = <?php echo $customers; ?>;
        var data = google.visualization.arrayToDataTable(customers);
        var options = {
          title: 'Top 10 Customers',
        };

        var chart = new google.visualization.PieChart(document.getElementById('Customer_chart_div'));
        chart.draw(data, options);
    }
</script>
@endsection
@section('content')
<div class="page-content">
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			<a class="dashboard-stat dashboard-stat-v2 blue" href="#">
                <div class="visual">
                    <i class="fa fa-comments"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="{{ $totalSale }}">{{ $totalSale }}</span>
                    </div>
                    <div class="desc"> Total Akumulasi Penjualan </div>
                </div>
            </a>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			<a class="dashboard-stat dashboard-stat-v2 red" href="#">
                <div class="visual">
                    <i class="fa fa-comments"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="{{ $avgSale }}">{{ $avgSale }}</span>
                    </div>
                    <div class="desc"> Rata Rata Penjualan Per Bulan </div>
                </div>
            </a>
        </div> 
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			<a class="dashboard-stat dashboard-stat-v2 green" href="#">
                <div class="visual">
                    <i class="fa fa-comments"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="{{ $totalPurchase }}">{{ $totalPurchase }}</span>
                    </div>
                    <div class="desc"> Total Akumulasi Pembelian </div>
                </div>
            </a>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
    	<div class="col-lg-12 col-xs-12 col-sm-12">
        	<div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-share font-red-sunglo hide"></i>
                        <span class="caption-subject font-dark bold uppercase">Penjualan</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="linechart_material" style="width: 100%; min-height: 450px"></div>
                    <div style="margin: 20px 0 10px 30px">
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-6 text-stat">
                                <span class="label label-sm label-success"> Pendapatan: </span>
                                <h3>Rp {{ number_format($totalIncome,2,',','.')}}</h3>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-6 text-stat">
                                <span class="label label-sm label-info"> Pajak: </span>
                                <h3>Rp {{ number_format($totalTax,2,',','.')}}</h3>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-6 text-stat">
                                <span class="label label-sm label-danger"> Pengiriman: </span>
                                <h3>Rp {{ number_format($totalDeliveries,2,',','.')}}</h3>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-6 text-stat">
                                <span class="label label-sm label-warning"> Pesanan: </span>
                                <h3>{{ number_format($totalItems,2,',','.')}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-6 col-xs-12 col-sm-12">
        	<div class="portlet light bordered">
                <div class="portlet-body">
                    <div id="Product_chart_div" style="width: 100%; min-height: 450px"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xs-12 col-sm-12">
        	<div class="portlet light bordered">
                <div class="portlet-body">
                    <div id="Customer_chart_div" style="width: 100%; min-height: 450px"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer.plugins')
<script src="{{ asset('assets/global/plugins/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/morris/morris.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/morris/raphael-min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/counterup/jquery.waypoints.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/counterup/jquery.counterup.min.js') }}" type="text/javascript"></script>
@endsection
@section('footer.scripts')
<script src="{{ asset('assets/pages/scripts/dashboard.min.js') }}" type="text/javascript"></script>
@endsection