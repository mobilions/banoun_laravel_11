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
            <!-- Filters -->
            <form method="GET" action="{{url('/customer')}}" class="mb-3">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label>Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Name, Email, Phone..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label>Verification Status</label>
                        <select name="is_verified" class="form-select">
                            <option value="1" {{ request('is_verified', '1') == '1' ? 'selected' : '' }}>Verified</option>
                            <option value="0" {{ request('is_verified') == '0' ? 'selected' : '' }}>Not Verified</option>
                            <option value="" {{ request('is_verified') === '' ? 'selected' : '' }}>All</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Min Credit</label>
                        <input type="number" name="min_credit" class="form-control" placeholder="Min" value="{{ request('min_credit') }}" step="0.01" min="0">
                    </div>
                    <div class="col-md-2">
                        <label>Max Credit</label>
                        <input type="number" name="max_credit" class="form-control" placeholder="Max" value="{{ request('max_credit') }}" step="0.01" min="0">
                    </div>
                    <div class="col-md-1 mt-4">
                        <button type="submit" class="btn btn-primary waves-effect waves-light w-100">Filter</button>
                    </div>
                    <div class="col-md-2 mt-4">
                        <a href="{{url('/customer')}}" class="btn btn-secondary waves-effect waves-light w-100">Reset</a>
                    </div>
                </div>
            </form>

            <table id="datatable-buttons" class="table table-bordered nowrap w-100 align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Credit Balance</th>
                        <th class="export-ignore">Action</th>
                    </tr>
                </thead>           
                <tbody>
                    @forelse ($indexes as $index)
                    <tr>
                        <td data-export="{{$index->id}}">{{$index->id}}</td>
                        <td data-export="{{$index->name}}">{{$index->name}}</td>
                        <td data-export="{{$index->phone ?? 'N/A'}}">{{$index->phone ?? '—'}}</td>
                        <td data-export="{{$index->email}}">{{$index->email}}</td>
                        <td data-export="{{number_format($index->credit_balance ?? 0, 2)}}">{{number_format($index->credit_balance ?? 0, 2)}}</td>
                        <td class="export-ignore">
                            <a href="{{url('/customer')}}/{{$index->id}}/view" class="btn btn-outline-info me-2 waves-effect waves-light btn-sm font-size-18" title="View Details"><i class="mdi mdi-eye"></i></a>
                            <a href="{{url('/customer')}}/{{$index->id}}/edit" class="btn btn-outline-secondary me-2 waves-effect waves-light btn-sm font-size-18" title="Edit"><i class="mdi mdi-pencil"></i></a>
                            <form action="{{url('/customer')}}/{{$index->id}}/delete" method="POST" class="d-inline" onsubmit="return confirm('Delete this customer?');">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger waves-effect waves-light btn-sm font-size-18" title="Delete">
                                    <i class="mdi mdi-trash-can-outline"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No customers found.</td>
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
            pageLength: 10,
            lengthMenu: [[10, 20, 50, 100, -1], [10, 20, 50, 100, "All"]],
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
                    filename: "Customers_Export",
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
                    filename: "Customers_Export",
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
                        doc.content[1].table.widths = ['10%', '25%', '20%', '25%', '20%'];
                    }
                }
            ],
            responsive: false,
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