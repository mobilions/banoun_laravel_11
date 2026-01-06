@extends('layouts.master')

@section('title',$title)

@section('StyleContent')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('PageContent')
<style type="text/css">
tr.ex2:hover, a.ex2:active {cursor: pointer; background-color:#adf7a9 !important; font-size: 115%;}
tr.selected {background-color:#adf7a9 !important;}
.stock-details-row {
    background-color: #f8f9fa;
}
.stock-details-table {
    margin: 10px 0;
}
.expandable-row {
    cursor: pointer;
}
.expandable-row:hover {
    background-color: #e3f2fd;
}
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
                            <th>Product</th>
                            <th>Variant</th>
                            <th>Available Quantity</th>
                            <th class="export-ignore">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $x = 1; ?>
                        @foreach ($indexes as $index)
                        <tr class="expandable-row" data-variant-id="{{$index->id}}" data-product-id="{{$index->product_id}}">
                            <td>{{$x++}}</td>
                            <td>{{$index->product->category->name ?? 'N/A'}}</td>
                            <td>{{$index->product->brand->name ?? 'N/A'}}</td>
                            <td>{{$index->product->subcategory->name ?? 'N/A'}}</td>
                            <td>{{$index->product->name ?? 'N/A'}}</td>
                            <td>
                                @if($index->sizeVariant)
                                    Size: {{$index->sizeVariant->name}}
                                @endif
                                @if($index->colorVariant)
                                    , Color: {{$index->colorVariant->name}}
                                @endif
                            </td>
                            <td><strong>{{$index->stocks_sum_quantity ?? 0}}</strong></td>
                            <td class="export-ignore">
                                <button class="btn btn-outline-primary btn-sm view-details" data-variant-id="{{$index->id}}">
                                    <i class="mdi mdi-eye"></i> View Log
                                </button>
                                <a href="{{url('stock')}}/{{$index->id}}/{{$index->product_id}}" class="btn btn-outline-info me-2 waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-store"></i></a>
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

    $(document).ready(function () {
        // Remove all detail rows before initializing DataTable
        $('.stock-details-row').remove();
        
        var table = $("#datatable-buttons").DataTable({
            pageLength: 20,
            lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "All"]],
            buttons: exportButtons,
            responsive: false,
            columnDefs: [
                { targets: 'export-ignore', orderable: false, searchable: false }
            ]
        });
        
        table.buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)");
        $(".dataTables_length select").addClass("form-select form-select-sm");

        // Handle click on "View Log" button
        $(document).on('click', '.view-details', function(e) {
            e.stopPropagation();
            var variantId = $(this).data('variant-id');
            var clickedRow = $(this).closest('tr');
            var existingDetailsRow = clickedRow.next('tr.stock-details-row');
            
            // Check if details row already exists
            if (existingDetailsRow.length > 0) {
                existingDetailsRow.remove();
                return;
            }
            
            // Create new details row
            var detailsRow = $('<tr class="stock-details-row">' +
                '<td colspan="8">' +
                '<div class="p-3">' +
                '<h6>Stock Addition History</h6>' +
                '<div class="table-responsive">' +
                '<table class="table table-sm stock-details-table">' +
                '<thead>' +
                '<tr>' +
                '<th>#</th>' +
                '<th>Date Added</th>' +
                '<th>Quantity Added</th>' +
                '<th>Process</th>' +
                '<th>Status</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody class="stock-details-body">' +
                '<tr><td colspan="5" class="text-center">' +
                '<div class="spinner-border spinner-border-sm" role="status">' +
                '<span class="visually-hidden">Loading...</span>' +
                '</div>' +
                '</td></tr>' +
                '</tbody>' +
                '</table>' +
                '</div>' +
                '</div>' +
                '</td>' +
                '</tr>');
            
            clickedRow.after(detailsRow);
            
            // Load stock details via AJAX
            $.ajax({
                url: '/details/' + variantId,
                method: 'GET',
                success: function(response) {
                    console.log("Response:", response);
                    
                    var tbody = detailsRow.find('.stock-details-body');
                    tbody.empty(); // Clear the loading spinner
                    
                    if (response && response.length > 0) {
                        $.each(response, function(index, stock) {
                            // Parse the date
                            var date = new Date(stock.created_at);
                            var formattedDate = date.toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: '2-digit',
                                day: '2-digit',
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                            
                            var row = '<tr>' +
                                '<td>' + (index + 1) + '</td>' +
                                '<td>' + formattedDate + '</td>' +
                                '<td><strong>' + stock.quantity + '</strong></td>' +
                                '<td><span class="badge bg-success">' + stock.process + '</span></td>' +
                                '<td>' + (stock.status == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>') + '</td>' +
                                '</tr>';
                            
                            tbody.append(row);
                        });
                        
                        console.log("Rows appended successfully");
                    } else {
                        tbody.html('<tr><td colspan="5" class="text-center">No stock records found</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', xhr, status, error);
                    var tbody = detailsRow.find('.stock-details-body');
                    tbody.html('<tr><td colspan="5" class="text-center text-danger">Error: ' + error + '</td></tr>');
                }
            });
        });
    });
})(jQuery);
</script>
@stop