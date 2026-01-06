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

            <h4 class="mb-sm-0 font-size-16">{{$title}} - {{App\Models\Product::FindName($product_id)}} <br><br> {{App\Models\Productvariant::FindName($variant_id)}} <br><br>

                Current Quantity: {{App\Models\Stock::stockVariant($variant_id)}}

            </h4>

            <div class="page-title-right">

                <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item"><a href="javascript: void(0);">Details</a></li>

                        <li class="breadcrumb-item active">{{$title}}</li>

                </ol>

            </div>



        </div>

    </div>

</div>

<div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">

<div class="modal-dialog">

<div class="modal-content">

<div class="modal-header">

<h5 class="modal-title" id="myModalLabel">Edit Stock</h5>

<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

</div>

<form  action="{{url('stock/update')}}" method="post">

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

<button type="submit" class="btn btn-primary waves-effect waves-light">Update</button>

</div>

</form>

</div><!-- /.modal-content -->

</div><!-- /.modal-dialog -->

</div>
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this stock entry?</p>
                <p><strong>Quantity: <span id="deleteQuantity"></span></strong></p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-md-4">

        <div class="card">

            <div class="card-body">

                <h5>Add Stock</h5>

                <div class="col-sm-12 pt-3">

                    <form  action="{{url('stock/store')}}" method="post" enctype="multipart/form-data">

                    {{csrf_field()}}  

                        <div class="row">

                            <div class="col-lg-12 mb-3">

                                <div>

                                    <label>Enter Quantity <span class="text-danger">*</span></label>

                                    <input type="number" name="quantity" id="quantity" class="form-control" required="">

                                    <input type="hidden" name="product_id" id="product_id" value="{{$product_id}}">

                                    <input type="hidden" name="variant_id" id="variant_id" value="{{$variant_id}}">

                                </div>

                            </div>

                            <div class="col-lg-12 mt-3 mb-3">

                                <center><button type="submit" class="btn btn-primary waves-effect waves-light me-2">Add</button></center>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <div  class="col-md-8">

        <div class="card">

            <div class="card-body">

                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Quantity</th>
                            <th>Date Added</th>
                            <th>Status</th>
                            <th class="export-ignore">Action</th>
                        </tr>
                    </thead>           
                    <tbody>
                        @foreach ($addstock as $index)
                        <tr>
                            <td>{{$index->id}}</td>
                            <td>{{$index->quantity}}</td>
                            <td>{{date('d-m-Y', strtotime($index->created_at))}}</td>
                            <td><span class="{{$index->status_badge_class}}">{{ucfirst($index->status_text)}}</span></td>
                            <td class="export-ignore">
                                @if($index->status == 0)
                                    <button type="button" 
                                            class="btn btn-success btn-sm waves-effect waves-light me-2 editbtn" 
                                            data-id="{{$index->id}}" 
                                            data-quantity="{{$index->quantity}}" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#myModal">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                    
                                    <button type="button" 
                                            class="btn btn-danger btn-sm waves-effect waves-light deletebtn" 
                                            data-id="{{$index->id}}" 
                                            data-quantity="{{$index->quantity}}">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    

                </table>

            </div>

        </div>

    </div>

    <div class="col-md-12">

        <div class="card">

            <div class="card-body">

                <h5>Stock Transaction</h5>

                <table id="datatable-buttons1" class="table table-bordered dt-responsive nowrap w-100">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Quantity</th>

                            <th>Date Added</th>

                            <th>Transaction</th>

                        </tr>

                    </thead>           

                    <tbody>

                        @foreach ($indexes as $index)

                        <tr>

                            <td>{{$index->id}}</td>

                            <td>{{$index->quantity}}</td>

                            <td>{{date('d-m-Y',strtotime($index->created_at))}}</td>

                                <td>{{$index->process}}</td>

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
        columnDefs: [
            { targets: 'export-ignore', orderable: false, searchable: false }
        ]
    };

    function initTable(selector, wrapperSelector) {
        var table = $(selector).DataTable(defaultOptions);
        if (wrapperSelector) {
            table.buttons().container().appendTo(wrapperSelector);
        }
    }

    $(document).ready(function () {
        initTable("#datatable-buttons", "#datatable-buttons_wrapper .col-md-6:eq(0)");
        initTable("#datatable-buttons1", "#datatable-buttons1_wrapper .col-md-6:eq(0)");
        $(".dataTables_length select").addClass("form-select form-select-sm");
        $('.editbtn').click(function() {
            var id = $(this).data("id"); $("#editid").val(id);
            var quantity = $(this).data("quantity"); $("#quantityedit").val(quantity);
        });

        $('.deletebtn').on('click', function() {
            var stockId = $(this).data('id');
            var quantity = $(this).data('quantity');
            $('#deleteQuantity').text(quantity);
            var deleteUrl = '{{ route("stock.destroy", ":id") }}';
            deleteUrl = deleteUrl.replace(':id', stockId);
            $('#deleteForm').attr('action', deleteUrl);
            $('#deleteModal').modal('show');
        });
    });
})(jQuery);
</script>

@stop