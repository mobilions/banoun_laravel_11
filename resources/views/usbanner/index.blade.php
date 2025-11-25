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

        <h4 class="mb-sm-0 font-size-16">{{$title}}<a href="{{ route('usbannercreate') }}" class="btn btn-primary waves-effect waves-light btn-sm ms-3"><i class="fa fa-plus me-1"></i>Create {{$title}}</a></h4>

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

                        <th>Shop By</th>

                        <th>Type</th>

                        <th>Name</th>

                        <th>Description</th>

                        <th>Image</th>

                        <th>Grid</th>

                        <th>Action</th>

                    </tr>

                </thead>           

                <tbody>

                    @foreach ($indexes as $index)

                    <tr>

                        <td>{{$index->id}}</td>

                        <td>{{$index->shopby}}</td>

                        @if($index->shopby=='category')

                        <td>{{App\Models\usbanner::getColumnWhere('categories','id',$index->category_id,'name')}}</td>

                        @elseif($index->shopby=='subcategory')

                        <td>{{App\Models\usbanner::getColumnWhere('subcategories','id',$index->category_id,'name')}}</td>

                        @elseif($index->shopby=='brand')

                        <td>{{App\Models\usbanner::getColumnWhere('brands','id',$index->category_id,'name')}}</td>

                        @else

                        <td></td>

                        @endif

                        <td>{{$index->name}}<br>

                            {{$index->name_ar}}</td>

                        <td>{{$index->description}}<br>

                            {{$index->description_ar}}</td>

                        @if($index->grid=='1')

                        <td>Big</td>

                        @elseif($index->grid=='2')

                        <td>Small</td>

                        @else

                        <td></td>

                        @endif

                        <td>  <img src="{{$index->imageurl}}" width="100" alt="" title=""></a> </td>

                        <td>
                            <a href="{{url('/usbanner')}}/{{$index->id}}/edit" class="btn btn-outline-secondary me-2 waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-pencil"></i></a>
                            <form action="{{url('/usbanner')}}/{{$index->id}}/delete" method="POST" class="d-inline" onsubmit="return confirm('Delete this promotion banner?');">
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

$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-buttons").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});

</script>

@stop