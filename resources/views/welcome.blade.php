@extends('layouts.master')
@section('title','Dashboard')
@section('StyleContent')
<style>
    .stat-card {
        transition: transform 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .chart-container {
        position: relative;
        height: 300px;
    }
    .recent-order-row {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .recent-order-row:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection
@section('PageContent')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Home Page</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Sales Overview Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stats-wid stat-card">
<div class="card-body">
<div class="d-flex">
    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Today's Sales</p>
                        <h4 class="mb-0">${{number_format($salesToday, 2)}}</h4>
                        <p class="text-muted mb-0 mt-2"><span class="text-muted"><i class="mdi mdi-calendar"></i> {{$ordersToday}} Orders</span></p>
    </div>
    <div class="flex-shrink-0 align-self-center">
                        <div class="mini-stat-icon avatar-sm rounded-circle bg-success">
                            <span class="avatar-title"><i class="bx bx-dollar font-size-24"></i></span>
        </div>
    </div>
</div>
</div>
</div>
</div>
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stats-wid stat-card">
<div class="card-body">
<div class="d-flex">
    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">This Week</p>
                        <h4 class="mb-0">${{number_format($salesThisWeek, 2)}}</h4>
                        <p class="text-muted mb-0 mt-2"><span class="text-muted"><i class="mdi mdi-calendar-week"></i> {{$ordersThisWeek}} Orders</span></p>
    </div>
    <div class="flex-shrink-0 align-self-center">
                        <div class="mini-stat-icon avatar-sm rounded-circle bg-info">
                            <span class="avatar-title"><i class="bx bx-calendar font-size-24"></i></span>
        </div>
    </div>
</div>
</div>
</div>
</div>
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stats-wid stat-card">
<div class="card-body">
<div class="d-flex">
    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">This Month</p>
                        <h4 class="mb-0">${{number_format($salesThisMonth, 2)}}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="{{$monthlyChange >= 0 ? 'text-success' : 'text-danger'}} me-2">
                                {{number_format(abs($monthlyChange), 1)}}% 
                                <i class="mdi mdi-arrow-{{$monthlyChange >= 0 ? 'up' : 'down'}}"></i>
                            </span>
                            <span class="text-muted">{{$ordersThisMonth}} Orders</span>
                        </p>
    </div>
    <div class="flex-shrink-0 align-self-center">
        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                            <span class="avatar-title"><i class="bx bx-trending-up font-size-24"></i></span>
        </div>
    </div>
</div>
</div>
</div>
</div>
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stats-wid stat-card">
<div class="card-body">
<div class="d-flex">
    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">This Year</p>
                        <h4 class="mb-0">${{number_format($salesThisYear, 2)}}</h4>
                        <p class="text-muted mb-0 mt-2"><span class="text-muted"><i class="mdi mdi-calendar-year"></i> {{$ordersThisYear}} Orders</span></p>
    </div>
    <div class="flex-shrink-0 align-self-center">
                        <div class="mini-stat-icon avatar-sm rounded-circle bg-warning">
                            <span class="avatar-title"><i class="bx bx-bar-chart-alt-2 font-size-24"></i></span>
                        </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stats-wid stat-card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Total Customers</p>
                        <h4 class="mb-0">{{number_format($totalCustomers)}}</h4>
                        <p class="text-muted mb-0 mt-2"><span class="text-success">+{{$newCustomersThisMonth}} This Month</span></p>
                    </div>
                    <div class="flex-shrink-0 align-self-center">
                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                            <span class="avatar-title"><i class="bx bx-user font-size-24"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stats-wid stat-card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Products</p>
                        <h4 class="mb-0">{{number_format($totalProducts)}}</h4>
                        <p class="text-muted mb-0 mt-2"><a href="{{url('/product')}}" class="text-primary">View All <i class="mdi mdi-arrow-right"></i></a></p>
                    </div>
                    <div class="flex-shrink-0 align-self-center">
                        <div class="mini-stat-icon avatar-sm rounded-circle bg-success">
                            <span class="avatar-title"><i class="bx bx-store font-size-24"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stats-wid stat-card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Categories</p>
                        <h4 class="mb-0">{{number_format($totalCategories)}}</h4>
                        <p class="text-muted mb-0 mt-2"><span class="text-muted">{{$totalSubcategories}} Subcategories</span></p>
                    </div>
                    <div class="flex-shrink-0 align-self-center">
                        <div class="mini-stat-icon avatar-sm rounded-circle bg-info">
                            <span class="avatar-title"><i class="bx bx-windows font-size-24"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stats-wid stat-card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Brands</p>
                        <h4 class="mb-0">{{number_format($totalBrands)}}</h4>
                        <p class="text-muted mb-0 mt-2"><a href="{{url('/brand')}}" class="text-primary">Manage <i class="mdi mdi-arrow-right"></i></a></p>
                    </div>
                    <div class="flex-shrink-0 align-self-center">
                        <div class="mini-stat-icon avatar-sm rounded-circle bg-warning">
                            <span class="avatar-title"><i class="bx bx-globe-alt font-size-24"></i></span>
                        </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

<!-- Charts Row -->
<div class="row">
    <!-- Revenue Chart -->
    <div class="col-xl-12">
<div class="card">
<div class="card-body">
<div class="d-sm-flex flex-wrap">
                    <h4 class="card-title mb-4">Revenue Overview</h4>
<div class="ms-auto">
                        <ul class="nav nav-pills" id="revenue-tab" role="tablist">
        <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#monthly-revenue" role="tab">Monthly</a>
        </li>
        <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#daily-revenue" role="tab">Daily</a>
        </li>
    </ul>
                    </div>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="monthly-revenue" role="tabpanel">
                        <div id="monthly-revenue-chart" class="chart-container"></div>
                    </div>
                    <div class="tab-pane fade" id="daily-revenue" role="tabpanel">
                        <div id="daily-revenue-chart" class="chart-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Second Row Charts -->
<div class="row">
    <!-- Payment Type Breakdown -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Payment Type Breakdown</h4>
                <div id="payment-type-chart" class="chart-container"></div>
                <div class="mt-3">
                    @foreach($paymentTypes as $payment)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>{{$payment->paymenttype ?? 'N/A'}}</span>
                        <span class="fw-medium">{{$payment->count}} orders</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
     <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Order Status</h4>
                <div id="order-status-pie-chart" class="chart-container"></div>
                <div class="mt-3">
                    @foreach($orderStatusData as $status)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <span class="badge" style="background-color: {{$status['color']}}">{{$status['name']}}</span>
                        </div>
                        <span class="fw-medium">{{$status['count']}}</span>
                    </div>
                    @endforeach
                </div>
            </div>
</div>
</div>
</div>

<!-- Top Products and Recent Orders -->
<div class="row">
    <!-- Top Selling Products -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Top Selling Products</h4>
                    <a href="{{url('/product')}}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Quantity Sold</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $product)
                            <tr>
                                <td>
                                    <div>
                                        <h6 class="mb-0">{{$product->name}}</h6>
                                        @if($product->name_ar)
                                        <small class="text-muted">{{$product->name_ar}}</small>
                                        @endif
                                    </div>
                                </td>
                                <td><span class="badge bg-primary">{{$product->total_qty}}</span></td>
                                <td><strong>${{number_format($product->total_revenue, 2)}}</strong></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">No sales data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Recent Orders</h4>
                    <a href="{{url('/orders')}}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr class="recent-order-row" onclick="window.location='{{url('/order/'.$order->id.'/view')}}'">
                                <td><strong>{{$order->order_number}}</strong></td>
                                <td>{{optional($order->user)->name ?? 'N/A'}}</td>
                                <td><strong>${{number_format($order->grandtotal, 2)}}</strong></td>
                                <td>
                                    <span class="badge" style="background-color: {{optional($order->orderStatus)->color ?? '#556ee6'}}">
                                        {{optional($order->orderStatus)->name ?? 'Unknown'}}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No orders found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</div>
</div>

<!-- Low Stock Alert -->
@if($lowStockProducts->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0 text-danger">
                        <i class="mdi mdi-alert-circle"></i> Low Stock Alert (Less than 20 units)
                    </h4>
                    <a href="{{url('/stocklog')}}" class="btn btn-sm btn-danger">Manage Stock</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Variant</th>
                                <th>Available Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockProducts as $variant)
                            <tr>
                                <td>
                                    <div>
                                        <h6 class="mb-0">{{optional($variant->product)->name ?? 'N/A'}}</h6>
                                        <small class="text-muted">{{optional($variant->product)->category->name ?? ''}}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($variant->sizeVariant)
                                        Size: {{$variant->sizeVariant->name}}
                                    @endif
                                    @if($variant->colorVariant)
                                        @if($variant->sizeVariant), @endif Color: {{$variant->colorVariant->name}}
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-danger">{{$variant->available_quantity}}</span>
                                </td>
                                <td>
                                    <a href="{{url('stock/'.$variant->id.'/'.$variant->product_id)}}" class="btn btn-sm btn-outline-primary">
                                        <i class="mdi mdi-plus"></i> Add Stock
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
@section('ScriptContent')
<!-- apexcharts -->
<script src="{{asset('/assets')}}/libs/apexcharts/apexcharts.min.js"></script>

<script>
(function ($) {
    $(document).ready(function () {
        // Monthly Revenue Chart
        var monthlyRevenueOptions = {
            chart: {
                height: 300,
                type: 'area',
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            series: [{
                name: 'Revenue',
                data: @json(array_column($monthlyRevenue, 'revenue'))
            }],
            xaxis: {
                categories: @json(array_column($monthlyRevenue, 'month'))
            },
            colors: ['#556ee6'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.3,
                    stops: [0, 90, 100]
                }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "$" + val.toFixed(2);
                    }
                }
            }
        };
        var monthlyRevenueChart = new ApexCharts(document.querySelector("#monthly-revenue-chart"), monthlyRevenueOptions);
        monthlyRevenueChart.render();

        // Daily Revenue Chart
        var dailyRevenueOptions = {
            chart: {
                height: 300,
                type: 'line',
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            series: [{
                name: 'Revenue',
                data: @json(array_column($dailyRevenue, 'revenue'))
            }],
            xaxis: {
                categories: @json(array_column($dailyRevenue, 'date'))
            },
            colors: ['#34c38f'],
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "$" + val.toFixed(2);
                    }
                }
            }
        };
        var dailyRevenueChart = new ApexCharts(document.querySelector("#daily-revenue-chart"), dailyRevenueOptions);
        dailyRevenueChart.render();

        // Order Status Pie Chart
        var orderStatusData = @json($orderStatusCounts);
        var orderStatusOptions = {
            chart: {
                height: 300,
                type: 'pie'
            },
            labels: orderStatusData.map(item => item.name),
            series: orderStatusData.map(item => item.count),
            colors: orderStatusData.map(item => item.color),
            legend: {
                position: 'bottom'
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + " orders";
                    }
                }
            }
        };
        var orderStatusChart = new ApexCharts(document.querySelector("#order-status-pie-chart"), orderStatusOptions);
        orderStatusChart.render();

        // Payment Type Chart
        var paymentTypes = @json($paymentTypes);
        var paymentTypeOptions = {
            chart: {
                height: 300,
                type: 'donut'
            },
            labels: paymentTypes.map(item => item.paymenttype || 'N/A'),
            series: paymentTypes.map(item => item.count),
            colors: ['#556ee6', '#f1b44c', '#34c38f', '#f46a6a', '#50a5f1'],
            legend: {
                position: 'bottom'
            }
        };
        var paymentTypeChart = new ApexCharts(document.querySelector("#payment-type-chart"), paymentTypeOptions);
        paymentTypeChart.render();

        // Payment Status Chart
        var paymentStatuses = @json($paymentStatuses);
        var paymentStatusOptions = {
            chart: {
                height: 300,
                type: 'bar',
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 4
                }
            },
            dataLabels: { enabled: true },
            series: [{
                name: 'Orders',
                data: paymentStatuses.map(item => item.count)
            }],
            xaxis: {
                categories: paymentStatuses.map(item => item.paymentstatus || 'N/A')
            },
            colors: ['#556ee6']
        };
        var paymentStatusChart = new ApexCharts(document.querySelector("#payment-status-chart"), paymentStatusOptions);
        paymentStatusChart.render();
    });
})(jQuery);
</script>
@endsection
