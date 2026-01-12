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

        <h4 class="mb-sm-0 font-size-16">{{$title}}<a href="{{ route('productcreate') }}" class="btn btn-primary waves-effect waves-light btn-sm ms-3"><i class="fa fa-plus me-1"></i>Create {{$title}}</a> <a href="{{ route('productsearch') }}" class="btn btn-success waves-effect waves-light btn-sm ms-3">{{$title}} Settings</a></h4>

        <div class="page-title-right">

            <ol class="breadcrumb m-0">

                    <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>

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
            <form method="GET" action="{{ route('products') }}" class="mb-3">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label>Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search by name..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label>Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories ?? [] as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Brand</label>
                        <select name="brand_id" class="form-select">
                            <option value="">All Brands</option>
                            @foreach($brands ?? [] as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Min Price</label>
                        <input type="number" name="min_price" class="form-control" placeholder="Min Price" value="{{ request('min_price') }}" step="0.01">
                    </div>
                    <div class="col-md-2">
                        <label>Max Price</label>
                        <input type="number" name="max_price" class="form-control" placeholder="Max Price" value="{{ request('max_price') }}" step="0.01">
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <div class="d-flex gap-2">
                            <a href="{{ route('products') }}" class="btn btn-secondary waves-effect waves-light flex-fill" title="Reset"><i class="mdi mdi-refresh me-1"></i>Clear</a>
                            <button type="submit" class="btn btn-primary waves-effect waves-light flex-fill" title="Filter"><i class="mdi mdi-filter me-1"></i>Filter</button>
                        </div>
                    </div>
                </div>
            </form>

            <table id="datatable-buttons" class="table table-bordered nowrap w-100 align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th class="export-ignore">Action</th>
                    </tr>
                </thead>           
                <tbody>
                    @forelse ($indexes as $index)
                    <tr>
                        <td>{{$index->id}}</td>
                        <td data-export="{{$index->name}}">{{$index->name}}@if($index->name_ar)<br>{{$index->name_ar}}@endif</td>
                        <td>{{ $index->category->name ?? '—' }}</td>
                        <td>{{ $index->brand->name ?? '—' }}</td>
                        <td data-export="{{$index->price}}">{{number_format($index->price, 2)}}</td>
                        <td>
                            <span class="badge {{ (int)$index->delete_status === 0 ? 'bg-success' : 'bg-secondary' }}">
                                {{ (int)$index->delete_status === 0 ? 'Active' : 'Deleted' }}
                            </span>
                        </td>
                        <td class="export-ignore">
                            <a href="{{url('/product')}}/{{$index->id}}/edit" class="btn btn-outline-secondary me-2 waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-pencil"></i></a>
                            <form action="{{url('/product')}}/{{$index->id}}/delete" method="POST" class="d-inline" onsubmit="return confirm('Delete this product?');">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger waves-effect waves-light btn-sm font-size-18">
                                    <i class="mdi mdi-trash-can-outline"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No products found.</td>
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