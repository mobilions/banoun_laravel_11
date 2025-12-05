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
                <form  action="{{url('/orders')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}  
                    <div class="row">
                        <div class="col-lg-3">
                            <div>
                                <label>From Date</label>
                                <input class="form-control" type="date" value="{{$fromdate}}" name="fromdate" id="fromdate">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div>
                                <label>To Date</label>
                                <input class="form-control" type="date" value="{{$todate}}" name="todate" id="todate">
                            </div>
                        </div>
                        <div class="col-lg-5 mt-3">
                            <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Amount</th>
                        <th>Quantity</th>
                        <th>Payment Type</th>
                        <th>Payment Status</th>
                        <th>Order Status</th>
                        <th class="export-ignore">Action</th>
                    </tr>
                </thead>           
                <tbody>
                    @foreach ($indexes as $index)
                    <tr>
                        <td>{{$index->order_number}}</td>
                        <td>{{optional($index->user)->name ?? 'N/A'}}</td>
                        <td>{{optional($index->user)->phone ?? 'N/A'}}</td>
                        <td>{{$index->grandtotal}}</td>
                        <td>{{$index->totalqty}}</td>
                        <td>{{$index->paymenttype}}</td>
                        <td>{{$index->paymentstatus}}</td>
                        <td>{{optional($index->orderStatus)->name ?? App\Models\Orderstatus::FindName($index->orderstatus)}}</td>
                        <td class="export-ignore"><a href="{{url('/order')}}/{{$index->id}}/view" class="btn btn-outline-secondary me-2 waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-eye"></i></a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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