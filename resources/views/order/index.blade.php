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
                <form method="GET" action="{{url('/orders')}}" class="mb-3">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label>From Date</label>
                            <input class="form-control" type="date" value="{{$fromdate}}" name="fromdate" id="fromdate">
                        </div>
                        <div class="col-md-2">
                            <label>To Date</label>
                            <input class="form-control" type="date" value="{{$todate}}" name="todate" id="todate">
                        </div>
                        <div class="col-md-2">
                            <label>Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Order #, Name, Phone..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label>Order Status</label>
                            <select name="order_status" class="form-select">
                                <option value="">All Statuses</option>
                                @foreach($orderStatuses ?? [] as $status)
                                    <option value="{{ $status->id }}" {{ request('order_status') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Payment Type</label>
                            <select name="payment_type" class="form-select">
                                <option value="">All Types</option>
                                @foreach($paymentTypes ?? [] as $type)
                                    <option value="{{ $type }}" {{ request('payment_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Min Amount</label>
                            <input type="number" name="min_amount" class="form-control" placeholder="Min" value="{{ request('min_amount') }}" step="0.01">
                        </div>
                        <div class="col-md-2">
                            <label>Max Amount</label>
                            <input type="number" name="max_amount" class="form-control" placeholder="Max" value="{{ request('max_amount') }}" step="0.01">
                        </div>
                        <div class="col-md-1">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary waves-effect waves-light w-100" title="Filter"><i class="mdi mdi-filter me-1"></i>Filter</button>
                        </div>
                        <div class="col-md-1">
                            <label>&nbsp;</label>
                            <a href="{{url('/orders')}}" class="btn btn-secondary waves-effect waves-light w-100" title="Reset"><i class="mdi mdi-refresh me-1"></i>Clear</a>
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
                        <!-- <th>Payment Status</th> -->
                        <th>Order Status</th>
                        <th class="export-ignore">Action</th>
                    </tr>
                </thead>           
                <tbody>
                    @forelse ($indexes as $index)
                    <tr>
                        <td data-export="{{$index->order_number}}">{{$index->order_number}}</td>
                        <td data-export="{{optional($index->user)->name ?? 'N/A'}}">{{optional($index->user)->name ?? 'N/A'}}</td>
                        <td data-export="{{optional($index->user)->phone ?? 'N/A'}}">{{optional($index->user)->phone ?? 'N/A'}}</td>
                        <td data-export="{{number_format($index->grandtotal, 2)}}">{{number_format($index->grandtotal, 2)}}</td>
                        <td data-export="{{$index->totalqty}}">{{$index->totalqty}}</td>
                        <td data-export="{{$index->paymenttype ?? 'N/A'}}">{{$index->paymenttype ?? 'N/A'}}</td>
                        <td data-export="{{optional($index->orderStatus)->name ?? App\Models\Orderstatus::FindName($index->orderstatus)}}">{{optional($index->orderStatus)->name ?? App\Models\Orderstatus::FindName($index->orderstatus)}}</td>
                        <td class="export-ignore"><a href="{{url('/order')}}/{{$index->id}}/view" class="btn btn-outline-secondary me-2 waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-eye"></i></a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No orders found.</td>
                    </tr>
                    @endforelse
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
    $(document).ready(function () {
        var table = $("#datatable-buttons").DataTable({
            pageLength: 20,
            lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "All"]],
            buttons: [
                { 
                    extend: "copy", 
                    exportOptions: { 
                        columns: ":visible:not(.export-ignore)",
                        format: {
                            body: function (data, row, column, node) {
                                var exportData = $(node).attr('data-export');
                                return exportData !== undefined ? exportData : data.replace(/<[^>]*>/g, '').trim();
                            }
                        }
                    } 
                },
                { 
                    extend: "excel", 
                    filename: "Orders_Export",
                    exportOptions: { 
                        columns: ":visible:not(.export-ignore)",
                        format: {
                            body: function (data, row, column, node) {
                                var exportData = $(node).attr('data-export');
                                return exportData !== undefined ? exportData : data.replace(/<[^>]*>/g, '').trim();
                            }
                        }
                    },
                    customize: function(xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        $('row', sheet).each(function() {
                            var cell = $(this).find('c[is="1"]');
                            if (cell.length) {
                                $(this).find('c').attr('s', '1');
                            }
                        });
                    }
                },
                { 
                    extend: "pdf", 
                    filename: "Orders_Export",
                    exportOptions: { 
                        columns: ":visible:not(.export-ignore)",
                        format: {
                            body: function (data, row, column, node) {
                                var exportData = $(node).attr('data-export');
                                var text = exportData !== undefined ? exportData : data.replace(/<[^>]*>/g, '').trim();
                                return text.replace(/\n/g, ' ').substring(0, 200);
                            }
                        }
                    },
                    customize: function(doc) {
                        doc.defaultStyle.fontSize = 8;
                        doc.styles.tableHeader.fontSize = 9;
                        doc.pageMargins = [10, 10, 10, 10];
                        doc.content[1].table.widths = ['15%', '20%', '15%', '12%', '10%', '13%', '15%'];
                    }
                }
            ],
            responsive: true,
            columnDefs: [
                { targets: 'export-ignore', orderable: false, searchable: false, exportable: false }
            ]
        });
        table.buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)");
        $(".dataTables_length select").addClass("form-select form-select-sm");
    });
})(jQuery);
</script>
@stop