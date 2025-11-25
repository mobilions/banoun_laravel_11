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

<div class="col-md-12">

    <div class="card">

        <div class="card-body">

            <table id="datatable-buttons" class="table table-bordered dt-responsive">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Category</th>

                        <th>Brand</th>

                        <th>SubCategory</th>

                        <th>Prodcut</th>

                        <th>Variant</th>

                        <th>Current Stock</th>

                    </tr>

                </thead>           

                <tbody>

                    <?php $x=1; ?>

                    @foreach ($indexes as $index)

                   

                    <tr>

                            <td>{{$x++}}</td>

                            <td>{{App\Models\Category::FindName($index->category_id)}}</td>

                            <td>{{App\Models\Subcategory::FindName($index->subcategory_id)}}</td>

                            <td>{{App\Models\Brand::FindName($index->brand_id)}}</td>

                            <td>{{$index->product}}</td>

                            <td>{{App\Models\Productvariant::FindName($index->id)}}</td>

                            <td>{{$index->available_quantity}}</td>

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