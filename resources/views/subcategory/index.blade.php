@extends('layouts.master')

@section('title',$title)
@php
    use Illuminate\Support\Str;
@endphp

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

        <h4 class="mb-sm-0 font-size-16">{{$title}}<a href="{{ route('subcategorycreate') }}" class="btn btn-primary waves-effect waves-light btn-sm ms-3"><i class="fa fa-plus me-1"></i>Create {{$title}}</a></h4>

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

            <table id="datatable-buttons" class="table table-bordered nowrap w-100 align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Category</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th class="export-ignore">Image</th>
                        <th>Status</th>
                        <th class="export-ignore">Action</th>
                    </tr>
                </thead>           
                <tbody>
                    @foreach ($indexes as $index)
                    <tr>
                        <td>{{$index->id}}</td>
                        <td data-export="{{$index->category}}">{{$index->category}}@if($index->category_ar)<br>{{$index->category_ar}}@endif</td>
                        <td data-export="{{$index->name}}">{{$index->name}}<br>{{$index->name_ar}}</td>
                        <td class="text-wrap" style="white-space: normal; word-break: break-word; max-width: 420px;" data-export="{{$index->description}}">
                            {{$index->description}}<br>{{$index->description_ar}}
                        </td>
                        @php
                            $subcategoryImage = $index->imageurl
                                ? (Str::startsWith($index->imageurl, ['http://', 'https://', '//'])
                                    ? $index->imageurl
                                    : asset($index->imageurl))
                                : null;
                        @endphp
                        <td class="text-center export-ignore">
                            @if($subcategoryImage)
                                <img src="{{$subcategoryImage}}" width="100" alt="{{$index->name}}" title="{{$index->name}}">
                            @else
                                <span class="text-muted">No image</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ (int)$index->delete_status === 0 ? 'bg-success' : 'bg-secondary' }}">
                                {{ (int)$index->delete_status === 0 ? 'Active' : 'Deleted' }}
                            </span>
                        </td>
                        <td class="export-ignore">
                            <a href="{{url('/subcategory')}}/{{$index->id}}/edit" class="btn btn-outline-secondary me-2 waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-pencil"></i></a>
                            <form action="{{url('/subcategory')}}/{{$index->id}}/delete" method="POST" class="d-inline" onsubmit="return confirm('Delete this subcategory?');">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger waves-effect waves-light btn-sm font-size-18">
                                    <i class="mdi mdi-trash-can-outline"></i>
                                </button>
                            </form>
                        </td>
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