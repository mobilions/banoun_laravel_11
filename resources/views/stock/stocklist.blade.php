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

<div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">

<div class="modal-dialog">

<div class="modal-content">

<div class="modal-header">

<h5 class="modal-title" id="myModalLabel">Edit Stock</h5>

<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

</div>

<form  action="{{route('stock.update')}}" method="post">

{{csrf_field()}}  

<div class="modal-body">

        <div class="row">

            <div class="col-lg-12 mb-3">

               <div class="col-lg-12 mb-3">

                    <div>

                        <label>Enter Quantity <span class="text-danger">*</span></label>

                        <input type="number" name="quantity" id="quantityedit" class="form-control" required="">

                        <input type="hidden" name="editid" id="editid">

                    </div>

                </div>

            </div>

        </div>

</div>

<div class="modal-footer">

<button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>

<button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>

</div>

</form>

</div><!-- /.modal-content -->

</div><!-- /.modal-dialog -->

</div><!-- /.modal -->

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
            <form method="GET" action="{{ route('stock.list') }}" class="mb-3">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label>Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Product, Variant..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label>Status</label>
                        <select name="status" class="form-select">
                            <option value="0" {{ request('status', '0') == '0' ? 'selected' : '' }}>Pending</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Approved</option>
                            <option value="" {{ request('status') === '' ? 'selected' : '' }}>All</option>
                        </select>
                    </div>
                    <div class="col-md-1 mt-4">
                        <button type="submit" class="btn btn-primary waves-effect waves-light w-100" title="Filter"><i class="mdi mdi-filter"></i></button>
                    </div>
                    <div class="col-md-1 mt-4">
                        <a href="{{ route('stock.list') }}" class="btn btn-secondary waves-effect waves-light w-100" title="Reset"><i class="mdi mdi-refresh"></i></a>
                    </div>
                </div>
            </form>

            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Prodcut</th>

                        <th>Variant</th>

                        <th>Current Stock</th>

                        <th>Quantity</th>

                        <th>Date Added</th>

                        <th class="export-ignore">Action</th>

                    </tr>

                </thead>           

                <tbody>

                    @foreach ($indexes as $index)
                    <?php $product = $index->product; ?>

                    <tr>

                            <td>{{$index->id}}</td>

                            <td class="export-ignore">
                                @if($product)
                                    <a target="_blank" href="{{ url('product/'.$product->id.'/edit') }}">
                                        {{$product->name}}
                                    </a>
                                @else
                                    <em class="text-muted">N/A</em>
                                @endif
                            </td>

                            <td>{{optional($index->variant)->name ?? __('N/A')}}</td>

                            <td>{!!App\Models\Stock::stockVariant($index->variant_id)!!}</td>

                            <td>{{$index->quantity}}</td>

                            <td>{{date('d-m-Y',strtotime($index->created_at))}}</td>

                            <td>
                                <a href="{{route('stock.list.approve',$index->id)}}" class="btn btn-info btn-sm waves-effect waves-light me-2">
                                    <i class="bx bx-check font-size-14 align-middle me-2"></i> Approve
                                </a>
                                <button type="button" class="btn btn-success btn-sm waves-effect waves-light me-2 editbtn" data-id="{{$index->id}}" data-quantity="{{$index->quantity}}"  data-bs-toggle="modal" data-bs-target="#myModal"><i class="mdi mdi-pencil ms-1"></i></button>
                                <form action="{{ route('stock.destroy', $index->id) }}" method="post" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm waves-effect waves-light" onclick="return confirm('Delete this stock entry?');">
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
    var exportButtons = [
        { extend: "copy", exportOptions: { columns: ":visible:not(.export-ignore)" } },
        { extend: "excel", exportOptions: { columns: ":visible:not(.export-ignore)" } },
        { extend: "pdf", exportOptions: { columns: ":visible:not(.export-ignore)" } }
    ];

    var tableOptions = {
        pageLength: 20,
        lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "All"]],
        buttons: exportButtons,
        responsive: true,
        columnDefs: [
            { targets: 'export-ignore', orderable: false, searchable: false }
        ]
    };

    $(document).ready(function () {
        var table = $("#datatable-buttons").DataTable(tableOptions);
        table.buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)");
        $(".dataTables_length select").addClass("form-select form-select-sm");

        $('.editbtn').click(function() {
            var id = $(this).data("id"); $("#editid").val(id);
            var quantity = $(this).data("quantity"); $("#quantityedit").val(quantity);
        });
    });
})(jQuery);
</script>

@stop