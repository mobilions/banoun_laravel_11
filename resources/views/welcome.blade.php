@extends('layouts.master')
@section('title','Home')
@section('StyleContent')
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
<div class="row">

<div class="col-md-3">
<div class="card mini-stats-wid">
<div class="card-body">
<div class="d-flex">
    <div class="flex-grow-1">
        <p class="text-muted fw-medium">Brands</p>
        <h4 class="mb-0">{{App\Models\Brand::count()}}</h4>
    </div>
    <div class="flex-shrink-0 align-self-center">
        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
            <span class="avatar-title"><i class="bx bx-globe-alt font-size-24"></i></span>
        </div>
    </div>
</div>
</div>
</div>
</div>
<div class="col-md-3">
<div class="card mini-stats-wid">
<div class="card-body">
<div class="d-flex">
    <div class="flex-grow-1">
        <p class="text-muted fw-medium">Products</p>
        <h4 class="mb-0">{{App\Models\Product::count()}}</h4>
    </div>
    <div class="flex-shrink-0 align-self-center">
        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
            <span class="avatar-title"><i class="bx bx-store font-size-24"></i></span>
        </div>
    </div>
</div>
</div>
</div>
</div>
<div class="col-md-3">
<div class="card mini-stats-wid">
<div class="card-body">
<div class="d-flex">
    <div class="flex-grow-1">
        <p class="text-muted fw-medium">Categories</p>
        <h4 class="mb-0">{{App\Models\Category::count()}}</h4>
    </div>
    <div class="flex-shrink-0 align-self-center">
        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
            <span class="avatar-title"><i class="bx bx-windows font-size-24"></i></span>
        </div>
    </div>
</div>
</div>
</div>
</div>
<div class="col-md-3">
<div class="card mini-stats-wid">
<div class="card-body">
<div class="d-flex">
    <div class="flex-grow-1">
        <p class="text-muted fw-medium">SubCategories</p>
        <h4 class="mb-0">{{App\Models\Subcategory::count()}}</h4>
    </div>
    <div class="flex-shrink-0 align-self-center">
        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
            <span class="avatar-title"><i class="bx bx-file font-size-24"></i></span>
        </div>
    </div>
</div>
</div>
</div>
</div>

<div class="col-md-4">
<div class="card">
<div class="card-body">
<h4 class="card-title mb-4">Monthly Earning</h4>
<div class="row">
    <div class="col-sm-6">
        <p class="text-muted">This month</p>
        <h3>$34,252</h3>
        <p class="text-muted"><span class="text-success me-2"> 12% <i class="mdi mdi-arrow-up"></i> </span> From previous period</p>

        <div class="mt-4">
            <a href="javascript: void(0);" class="btn btn-primary waves-effect waves-light btn-sm">View More <i class="mdi mdi-arrow-right ms-1"></i></a>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="mt-4 mt-sm-0">
            <div id="radialBar-chart" class="apex-charts"></div>
        </div>
    </div>
</div>
<p class="text-muted mb-0">We craft digital, graphic and dimensional thinking.</p>
</div>
</div>
</div>

<div class="col-md-8">
<div class="card">
<div class="card-body">
<div class="d-sm-flex flex-wrap">
<h4 class="card-title mb-4">Orders Per Month (Sample Data)</h4>
<div class="ms-auto">
    <ul class="nav nav-pills">
        <li class="nav-item">
            <a class="nav-link" href="#">Week</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Month</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="#">Year</a>
        </li>
    </ul>
</div>
</div>
</div>

<div id="stacked-column-chart" class="apex-charts" dir="ltr"></div>
</div>
</div>

</div>
@endsection
@section('ScriptContent')
<!-- apexcharts -->
<script src="{{asset('/assets')}}/libs/apexcharts/apexcharts.min.js"></script>

<!-- dashboard init -->
<script src="{{asset('/assets')}}/js/pages/dashboard.init.js"></script>
@endsection