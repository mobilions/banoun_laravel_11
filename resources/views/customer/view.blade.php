@extends('layouts.master')

@section('title',$title)

@section('StyleContent')

<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />

<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

@endsection

@section('PageContent')

<style type="text/css">

tr.ex2:hover, a.ex2:active {cursor: pointer; background-color:#adf7a9  ! important;font-size: 115%;}

tr.selected {background-color:#adf7a9  ! important;}

</style>

<div class="row">

<div class="col-12">

    <div class="page-title-box d-sm-flex align-items-center justify-content-between">

        <h4 class="mb-sm-0 font-size-16">{{$title}}</h4>

        <div class="page-title-right">

            <ol class="breadcrumb m-0">

                    <li class="breadcrumb-item"><a href="javascript: void(0);">Details</a></li>

                    <li class="breadcrumb-item active">{{$title}}</li>

            </ol>

        </div>



    </div>

</div>

</div>

<div class="row">

    <div class="col-xl-12">

        <div class="card">

            <div class="card-body">

                <h4 class="card-title">{{$log->name}}</h4>

                <div class="row">

                    <div class="col-lg-3 mb-3">

                        <div>

                            <label>Phone</label>

                            <p>{{$log->phone}}</p>

                        </div>

                    </div>  

                    <div class="col-lg-3 mb-3">

                        <div>

                            <label>Email</label>

                           <p> {{$log->email}}</p>

                        </div>

                    </div>  

                

                <div class="col-lg-3 mb-3">

                        <div>

                            <label>Credit Balance</label>

                            <p>{{$log->credit_balance}}</p>

                        </div>

                    </div>  

                </div>

                <!-- Nav tabs -->

                <ul class="nav nav-tabs" role="tablist">

                    <li class="nav-item">

                        <a class="nav-link active" data-bs-toggle="tab" href="#kids" role="tab">

                            <span class="d-block d-sm-none"><i class="fas fa-user"></i></span>

                            <span class="d-none d-sm-block">Kids</span>    

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link" data-bs-toggle="tab" href="#address" role="tab">

                            <span class="d-block d-sm-none"><i class="far fa-home"></i></span>

                            <span class="d-none d-sm-block">Address</span>    

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link" data-bs-toggle="tab" href="#orders" role="tab">

                            <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>

                            <span class="d-none d-sm-block">Orders</span>    

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link" data-bs-toggle="tab" href="#wishlist" role="tab">

                            <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>

                            <span class="d-none d-sm-block">Wishlist</span>    

                        </a>

                    </li>

                </ul>



                <!-- Tab panes -->

                <div class="tab-content p-3 text-muted">

                    <div class="tab-pane active" id="kids" role="tabpanel">

                        <table class="table align-middle table-nowrap mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>Name</th>

                                    <th>Gender</th>

                                    <th>DOB</th>

                                    <th>Image</th>

                                </tr>

                            </thead>           

                            <tbody>

                                @foreach ($kids as $index)

                                <tr>

                                    <td>{{$index->name}}</td>

                                    <td>{{$index->gender}}</td>

                                    <td>{{date('Y-m-d',strtotime($index->dob))}}</td>

                                    <td>  <img src="{{$index->imgfile}}" width="100" alt="" title=""></a> </td>

                                </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                    <div class="tab-pane" id="address" role="tabpanel">

                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Phone</th>
                                    <th>Addess Type</th>
                                    <th>Area</th>
                                    <th>Block</th>
                                    <th>Street</th>
                                    <th>Avenue</th>
                                    <th>Building</th>
                                    <th>Apartment</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userAddress as $address)
                                    <tr>
                                        <td>{{$address->name}}</td>
                                        <td>{{$address->country_mobile}} {{$address->mobile}}</td>
                                        <td>{{$address->country_landline}} {{$address->landline}}</td>
                                        <td>{{$address->type}}</td>
                                        <td>{{$address->userarea->name}}</td>
                                        <td>{{$address->block}}</td>
                                        <td>{{$address->street}}</td>
                                        <td>{{$address->avenue}}</td>
                                        <td>{{$address->building}}</td>
                                        <td>{{$address->apartment}}</td>
                                        <td>{{$address->additional_info}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                    <div class="tab-pane" id="orders" role="tabpanel"> 
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Order Number</th>
                                    <th>Grand Total</th>
                                    <th>Payment Type</th>
                                    <th>Payment Statut</th>
                                    <th>Note</th>
                                    <th>Order Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartMasters as $cartMaster)
                                    <tr>
                                        <td>{{$cartMaster->order_number}}</td>
                                        <td>{{$cartMaster->grandtotal}}</td>
                                        <td>{{$cartMaster->paymenttype}}</td>
                                        <td>{{$cartMaster->paymentstatus}}</td>
                                        <td>{{$cartMaster->comments}}</td>
                                        <td>{{$cartMaster->orderstatus}}</td>
                                    </tr>
                                @endforeach
                        </table>

                    </div>

                    <div class="tab-pane" id="wishlist" role="tabpanel">

                        <p class="mb-0">

                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Variant</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($wishlists as $wishlist)
                                    <tr>
                                        <td>{{App\Models\Product::FindName($wishlist->product_id)}}</td>
                                        <td>{{App\Models\Productvariant::FindName($wishlist->variant_id)}}</td>
                                        <td>{{$wishlist->qty}}</td>
                                    </tr>
                                @endforeach
                        </table>

                        </p>

                    </div>

                </div>



            </div>

        </div>

    </div>

    </div>

</div>

</div>

@endsection

@section('ScriptContent')

<script src="{{asset('/assets')}}/libs/datatables.net/js/jquery.dataTables.min.js"></script>

<script src="{{asset('/assets')}}/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<script src="{{asset('/assets')}}/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>

<script src="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>

<script src="{{asset('/assets')}}/libs/jszip/jszip.min.js"></script>

<script src="{{asset('/assets')}}/libs/pdfmake/build/pdfmake.min.js"></script>

<script src="{{asset('/assets')}}/libs/pdfmake/build/vfs_fonts.js"></script>

<script src="{{asset('/assets')}}/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>

<script src="{{asset('/assets')}}/libs/datatables.net-buttons/js/buttons.print.min.js"></script>

<script src="{{asset('/assets')}}/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

<script src="{{asset('/assets')}}/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>

<script src="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

<script>

$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-buttons").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});

</script>

@stop