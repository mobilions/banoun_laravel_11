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
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                    @php
                    $statusFlow = [
                        null => [
                            'next' => 1,
                            'label' => 'Mark as Paid',
                            'confirm' => 'Mark order as Payment Paid?',
                            'btn' => 'warning',
                            'icon' => 'bx bx-check',
                        ],
                        1 => [
                            'next' => 2,
                            'label' => 'Mark as Processing',
                            'confirm' => 'Mark order as Processing?',
                            'btn' => 'primary',
                            'icon' => 'bx bx-loader',
                        ],
                        2 => [
                            'next' => 3,
                            'label' => 'Mark as Shipped',
                            'confirm' => 'Mark order as Shipped?',
                            'btn' => 'info',
                            'icon' => 'bx bx-package',
                        ],
                        3 => [
                            'next' => 4,
                            'label' => 'Out for Delivery',
                            'confirm' => 'Mark order as Out for Delivery?',
                            'btn' => 'warning',
                            'icon' => 'bx bx-run',
                        ],
                        4 => [
                            'next' => 5,
                            'label' => 'Mark as Delivered',
                            'confirm' => 'Mark order as Delivered?',
                            'btn' => 'success',
                            'icon' => 'bx bx-check-circle',
                        ],
                    ];
                    @endphp

                    @if(isset($statusFlow[$order->orderstatus]))
                        @php $step = $statusFlow[$order->orderstatus]; @endphp

                        <form action="{{ route('order.status.update', [$order->id, $step['next']]) }}"
                            method="POST"
                            class="d-inline float-end me-3">
                            @csrf

                            <button type="submit"
                                    class="btn btn-{{ $step['btn'] }} btn-sm waves-effect waves-light"
                                    onclick="return confirm('{{ $step['confirm'] }}');">
                                <i class="{{ $step['icon'] }} font-size-14 align-middle me-2"></i>
                                {{ $step['label'] }}
                            </button>
                        </form>
                    @endif

                    @if($order->orderstatus == 5)
                        <span class="badge bg-success float-end me-3">
                            <i class="bx bx-check-circle me-1"></i> Order Completed
                        </span>
                    @endif

                    <div class="row">
                        <div class="col-lg-3 mb-3">
                            <div>
                                <label class="text-muted">Order ID</label>
                                <h6>{{$order->order_number}}</h6>
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div>
                                <label class="text-muted">Order Date</label>
                                <h6>{{date('d M, Y', strtotime($order->created_at))}}</h6>
                            </div>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <div>
                                <label class="text-muted">Name</label>
                                <h6>{{optional($order->user)->name ?? 'N/A'}}</h6>
                            </div>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <div>
                                <label class="text-muted">Mobile</label>
                                <h6>{{optional($order->user)->phone ?? 'N/A'}}</h6>
                            </div>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <div>
                                <label class="text-muted">Total</label>
                                <h6>{{$order->total}} KWD</h6>
                            </div>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <div>
                                <label class="text-muted">Sub Total</label>
                                <h6>{{$order->subtotal}} KWD</h6>
                            </div>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <div>
                                <label class="text-muted">Discount</label>
                                <h6>{{$order->discount}} KWD</h6>
                            </div>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <div>
                                <label class="text-muted">Delivery</label>
                                <h6>{{$order->delivery}} KWD</h6>
                            </div>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <div>
                                <label class="text-muted">Grand Total</label>
                                <h6>{{$order->grandtotal}} KWD</h6>
                            </div>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <div>
                                <label class="text-muted">Total Quantity</label>
                                <h6>{{$order->totalqty}}</h6>
                            </div>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <div>
                                <label class="text-muted">Payment Type</label>
                                <h6>{{$order->paymenttype}}</h6>
                            </div>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <div>
                                <label class="text-muted">Order Status</label>
                                <h6>{{optional($order->orderStatus)->name ?? App\Models\Orderstatus::FindName($order->orderstatus)}}</h6>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatable-buttons" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Variant</th>
                            <th>Quantity</th>
                            <th>Actual Price</th>
                            <th>Offer Price</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>           
                    <tbody>
                        <?php $x=1;?>
                        @foreach ($orderlist as $index)
                        <tr>
                            <td>{{$x++}}</td>
                            <td>{{App\Models\Product::FindName($index->product_id)}}</td>
                            <td>{{App\Models\Productvariant::FindName($index->variant_id)}}</td>
                            <td>{{$index->qty}}</td>
                            <td>{{$index->actual_price}}</td>
                            <td>{{$index->offer_price}}</td>
                            <td>{{$index->total_price}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <h5 class="mt-3">Order Status Log </h5>
                <table class="table table-bordered dt-responsive nowrap w-100">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Status</th>

                            <th>Date</th>

                        </tr>

                    </thead>           

                    <tbody>

                        @foreach ($order_tracks as $olog)

                        <tr>
                            <td>{{$olog->id}}</td>
                            <td>{{optional($olog->status)->name ?? App\Models\Orderstatus::FindName($olog->status_id)}}</td>
                            <td>{{ $olog->created_at->timezone('Asia/Kuwait')->format('d M, Y H:i') }}</td>
                        </tr>

                        @endforeach

                    </tbody>

                </table>
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
(function ($) {
    var exportButtons = [
        { extend: "copy", exportOptions: { columns: ":visible:not(.export-ignore)" } },
        { extend: "excel", exportOptions: { columns: ":visible:not(.export-ignore)" } },
        { extend: "pdf", exportOptions: { columns: ":visible:not(.export-ignore)" } }
    ];
    var defaultOptions = {
        pageLength: 20,
        lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "All"]],
        buttons: exportButtons,
        responsive: true,
        columnDefs: [{ targets: 'export-ignore', orderable: false, searchable: false }]
    };
    $(document).ready(function () {
        $("#datatable-buttons").DataTable(defaultOptions).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)");
        $(".dataTables_length select").addClass("form-select form-select-sm");
    });
})(jQuery);
</script>
@stop