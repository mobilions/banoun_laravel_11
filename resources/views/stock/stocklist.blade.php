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

<form  action="{{url('admin/stock/update')}}" method="post">

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

            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Prodcut</th>

                        <th>Variant</th>

                        <th>Current Stock</th>

                        <th>Quantity</th>

                        <th>Date Added</th>

                        <th>Action</th>

                    </tr>

                </thead>           

                <tbody>

                    @foreach ($indexes as $index)

                    <?php $product=App\Models\Product::where('id',$index->product_id)->first();

                     ?>

                    <tr>

                            <td>{{$index->id}}</td>

                            <td><a target="_balnk" href="{{url('admin/productlist')}}/{{$product->id}}/view">{{App\Models\Product::FindName($product->id)}}</a></td>

                            <td>{{App\Models\Productvariant::FindName($index->variant_id)}}</td>

                            <td>{!!App\Models\Stock::stockVariant($index->variant_id)!!}</td>

                            <td>{{$index->quantity}}</td>

                            <td>{{date('d-m-Y',strtotime($index->created_at))}}</td>

                            <td><a href="{{url('admin/stcokapprovelist')}}/{{$index->id}}" class="btn btn-info btn-sm waves-effect waves-light me-3"> <i class="bx bx-check font-size-14 align-middle me-2"></i> Approve </a> <button type="button" class="btn btn-success btn-sm waves-effect waves-light me-3 editbtn" data-id="{{$index->id}}" data-quantity="{{$index->quantity}}"  data-bs-toggle="modal" data-bs-target="#myModal"><i class="mdi mdi-pencil ms-1"></i></button></td>

                            

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

$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-buttons").DataTable({lengthChange:!1,"iDisplayLength": 500,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});



$('.editbtn').click(function() {

    var id = $(this).data("id"); $("#editid").val(id);

    var quantity = $(this).data("quantity"); $("#quantityedit").val(quantity);

});

</script>

@stop