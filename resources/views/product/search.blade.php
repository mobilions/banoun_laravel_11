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

.row-updating {
    opacity: 0.5;
    pointer-events: none;
}

.row-flash-success {
    animation: rowFlash 0.9s ease;
}

@keyframes rowFlash {
    from { background-color: rgba(25,135,84,0.35); }
    to { background-color: transparent; }
}

#product-search-status {
    display: none;
}

</style>

<div class="row">

<div class="col-12">

    <div class="page-title-box d-sm-flex align-items-center justify-content-between">

        <h4 class="mb-sm-0 font-size-16">{{$title}} <a href="{{ route('products') }}" class="btn btn-primary waves-effect waves-light btn-sm ms-3"><i class="fa fa-file me-1"></i>Products</a></h4>

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

        <div class="card-body" id="productsearch">
            <div id="product-search-status" class="alert mb-3" role="alert"></div>
            {!!$content!!}
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



$('.editbtn').click(function() {

    var id = $(this).data("id"); $("#editid").val(id);

    var name = $(this).data("name"); $("#name").val(name);

    var day_limit = $(this).data("day_limit"); $("#day_limit").val(day_limit);

});

$('.resetbtn').click(function() {

    $("#editid").val(0);

    $("#name").val('');

    $("#day_limit").val('');

});



const $statusBanner = $('#product-search-status');

function showStatus(type, message) {
    if (!$statusBanner.length) {
        return;
    }
    $statusBanner
        .removeClass('d-none alert-success alert-danger')
        .addClass(type === 'success' ? 'alert-success' : 'alert-danger')
        .text(message)
        .fadeIn();

    if (type === 'success') {
        clearTimeout($statusBanner.data('timeout'));
        var timeout = setTimeout(function () {
            $statusBanner.fadeOut();
        }, 2500);
        $statusBanner.data('timeout', timeout);
    }
}

$('.changechecksearch').on('change', function() {
        var $checkbox = $(this);
        if ($checkbox.data('processing')) {
            return;
        }

        var isChecked = $checkbox.is(':checked');
        var previousState = !isChecked;
        var $row = $checkbox.closest('tr');

        var payload = {
            product_id: $checkbox.data("productid"),
            field: $checkbox.data("searchname"),
            value: isChecked ? 1 : 0,
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        $checkbox.data('processing', true).prop('disabled', true);
        $row.addClass('row-updating');

        $.ajax({
            url: "{{url('/updateproductsearch')}}",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: payload,
            success: function () {
                $row.addClass('row-flash-success');
                showStatus('success', 'Product setting updated successfully.');
                setTimeout(function () {
                    $row.removeClass('row-flash-success');
                }, 900);
            },
            error: function (xhr) {
                $checkbox.prop('checked', previousState);
                var message = 'Unable to update the product setting. Please try again.';
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    } else if (xhr.responseJSON.errors) {
                        var firstKey = Object.keys(xhr.responseJSON.errors)[0];
                        if (firstKey && xhr.responseJSON.errors[firstKey][0]) {
                            message = xhr.responseJSON.errors[firstKey][0];
                        }
                    }
                }
                showStatus('danger', message);
            },
            complete: function () {
                $checkbox.data('processing', false).prop('disabled', false);
                $row.removeClass('row-updating');
            }
        });

});

</script>

@stop