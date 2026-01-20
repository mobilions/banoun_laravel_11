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
                <!-- Filters -->
                <form method="GET" action="{{url('/stocklog')}}" class="mb-3">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label>Search Product</label>
                            <input type="text" name="search" class="form-control" placeholder="Search by product name..." value="{{ request('search') }}">
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
                            <label>SubCategory</label>
                            <select name="subcategory_id" class="form-select">
                                <option value="">All SubCategories</option>
                                @foreach($subcategories ?? [] as $subcat)
                                    <option value="{{ $subcat->id }}" {{ request('subcategory_id') == $subcat->id ? 'selected' : '' }}>{{ $subcat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Min Quantity</label>
                            <input type="number" name="min_quantity" class="form-control" placeholder="Min" value="{{ request('min_quantity') }}" min="0">
                        </div>
                        <div class="col-md-2">
                            <label>Max Quantity</label>
                            <input type="number" name="max_quantity" class="form-control" placeholder="Max" value="{{ request('max_quantity') }}" min="0">
                        </div>
                        <div class="col-md-2">
                            <label>Product Status</label>
                            <select name="status" class="form-select">
                                <option value="">Active</option>
                                <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary waves-effect waves-light w-100" title="Filter"><i class="mdi mdi-filter me-1"></i>Filter</button>
                        </div>
                        <div class="col-md-1">
                            <label>&nbsp;</label>
                            <a href="{{url('/stocklog')}}" class="btn btn-secondary waves-effect waves-light w-100" title="Reset"><i class="mdi mdi-refresh me-1"></i>Clear</a>
                        </div>
                    </div>
                </form>

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
                        @forelse ($indexes as $index)
                        <tr class="expandable-row" data-variant-id="{{$index->id}}" data-product-id="{{$index->product_id}}">
                            <td data-export="{{$x}}">{{$x++}}</td>
                            <td data-export="{{$index->product->category->name ?? 'N/A'}}">{{$index->product->category->name ?? 'N/A'}}</td>
                            <td data-export="{{$index->product->brand->name ?? 'N/A'}}">{{$index->product->brand->name ?? 'N/A'}}</td>
                            <td data-export="{{$index->product->subcategory->name ?? 'N/A'}}">{{$index->product->subcategory->name ?? 'N/A'}}</td>
                            <td data-export="{{$index->product->name ?? 'N/A'}}">{{$index->product->name ?? 'N/A'}}</td>
                            <td data-export="{{($index->sizeVariant ? 'Size: ' . $index->sizeVariant->name : '') . ($index->colorVariant ? ($index->sizeVariant ? ', ' : '') . 'Color: ' . $index->colorVariant->name : '')}}">
                                @if($index->sizeVariant)
                                    Size: {{$index->sizeVariant->name}}
                                @endif
                                @if($index->colorVariant)
                                    @if($index->sizeVariant), @endif Color: {{$index->colorVariant->name}}
                                @endif
                            </td>
                            <td data-export="{{$index->stocks_sum_quantity ?? 0}}"><strong>{{$index->stocks_sum_quantity ?? 0}}</strong></td>
                            <td class="export-ignore">
                                <button class="btn btn-outline-primary btn-sm view-details" data-variant-id="{{$index->id}}">
                                    <i class="mdi mdi-eye"></i> View Log
                                </button>
                                <a href="{{url('stock')}}/{{$index->id}}/{{$index->product_id}}" class="btn btn-outline-info me-2 waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-store"></i></a>
                            </td>
                        </tr>
                       
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No stock records found.</td>
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
        // Remove all detail rows before initializing DataTable
        $('.stock-details-row').remove();
        
        var table = $("#datatable-buttons").DataTable({
            pageLength: 20,
            lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "All"]],
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
                    filename: "StockLog_Export",
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
                    filename: "StockLog_Export",
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
                        doc.content[1].table.widths = ['8%', '15%', '15%', '15%', '20%', '17%', '10%'];
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
        '<h6>Stock Transaction History</h6>' +
        '<div class="table-responsive">' +
        '<table class="table table-sm stock-details-table">' +
        '<thead>' +
        '<tr>' +
        '<th>#</th>' +
        '<th>Date</th>' +
        '<th>Action</th>' +
        '<th>Processed Qty</th>' +
        '<th>Previous Qty</th>' +
        '<th>Total After Action</th>' +
        '<th>Action By</th>' +
        '</tr>' +
        '</thead>' +
        '<tbody class="stock-details-body">' +
        '<tr><td colspan="7" class="text-center">' +
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
                $.each(response, function(index, log) {
                    // Parse the date
                    var date = new Date(log.created_at);
                    var formattedDate = date.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    
                    // Determine badge class
                    var processBadge = '';
                    var processQtyDisplay = '';
                    
                    if (log.process === 'Remove') {
                        processBadge = '<span class="badge bg-danger">' + log.process + '</span>';
                        processQtyDisplay = '<span class="text-danger">' + log.process_quantity + '</span>';
                    } else if (log.process === 'Add') {
                        processBadge = '<span class="badge bg-success">' + log.process + '</span>';
                        processQtyDisplay = '<span class="text-success">+' + log.process_quantity + '</span>';
                    } else {
                        processBadge = '<span class="badge bg-info">' + log.process + '</span>';
                        processQtyDisplay = '<span>' + log.process_quantity + '</span>';
                    }
                    
                    var actionBadge = log.action === 'CREATED' 
                        ? '<span class="badge bg-primary">' + log.action + '</span>'
                        : '<span class="badge bg-warning">' + log.action + '</span>';
                    
                    var row = '<tr>' +
                        '<td>' + (index + 1) + '</td>' +
                        '<td>' + formattedDate + '</td>' +
                        '<td>' + actionBadge + '</td>' +
                        '<td>' + processQtyDisplay + ' ' + processBadge + '</td>' +
                        '<td>' + log.previous_quantity + '</td>' +
                        '<td>' + log.total_quantity + '</td>' +
                        '<td>' + (log.user ? log.user.name : 'N/A') + '</td>' +
                        '</tr>';
                    
                    tbody.append(row);
                });
                
                console.log("Rows appended successfully");
            } else {
                tbody.html('<tr><td colspan="7" class="text-center">No stock records found</td></tr>');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', xhr, status, error);
            var tbody = detailsRow.find('.stock-details-body');
            tbody.html('<tr><td colspan="7" class="text-center text-danger">Error: ' + error + '</td></tr>');
        }
    });
});
    });
})(jQuery);
</script>
@stop