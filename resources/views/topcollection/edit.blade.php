@extends('layouts.master')

@section('title',$title)

@section('StyleContent')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .readonly-field {
        background-color: #e9ecef !important;
        cursor: not-allowed !important;
    }
</style>
@endsection

@section('PageContent')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-16">{{$title}}</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Edit</a></li>
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
                <h4 class="text-secondary"><span class="">Edit</span> {{$title}}</h4>
                <div class="row">
                    <div class="col-sm-12 pt-3">
                        <form action="{{url('/topcollection/update')}}" method="post" enctype="multipart/form-data" id="bannerForm">
                            {{csrf_field()}}
                            <input type="hidden" name="editid" value="{{$log->id}}">
                            
                            <div class="row">
                                
                                <!-- Redirection Type -->
                                <div class="col-lg-4 mb-3">
                                    <div>
                                        <label>Select Redirection Type <span class="text-danger">*</span></label>
                                        <select class="form-control" name="redirect_type" id="redirect_type" onchange="loadurl()" required="">
                                            <option value="" disabled>Select</option>
                                            <option value="category" {{ $log->redirect_type == 'category' ? 'selected' : '' }}>Category</option>
                                            <option value="product_listing" {{ $log->redirect_type == 'product_listing' ? 'selected' : '' }}>Product listing</option>
                                            <option value="product_detail" {{ $log->redirect_type == 'product_detail' ? 'selected' : '' }}>Product Detail</option>
                                            <option value="URL" {{ $log->redirect_type == 'URL' ? 'selected' : '' }}>URL</option>
                                        </select>
                                        @error('redirect_type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Shop By -->
                                <div class="col-lg-4 mb-3" id="shopby_div" style="display:none;">
                                    <div>
                                        <label>Shop By <span class="text-danger">*</span></label>
                                        <select onchange="loadType()" class="form-control" name="shopby" id="shopby">
                                            <option value="" disabled>Select</option>
                                            <option value="category" class="shopby-option category-option" {{ strtolower($log->shopby) == 'category' ? 'selected' : '' }}>Category</option>
                                            <option value="subcategory" class="shopby-option subcategory-option" {{ strtolower($log->shopby) == 'subcategory' ? 'selected' : '' }}>Sub Category</option>
                                            <option value="brand" class="shopby-option brand-option" {{ strtolower($log->shopby) == 'brand' ? 'selected' : '' }}>Brand</option>
                                            <option value="product" class="shopby-option product-option" style="display:none;" {{ strtolower($log->shopby) == 'product' ? 'selected' : '' }}>Product</option>
                                        </select>
                                        @error('shopby')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Parent Category (only for subcategory) -->
                                <div class="col-lg-4 mb-3" id="parent_category_div" style="display:none;">
                                    <div>
                                        <label>Select Category <span class="text-danger">*</span></label>
                                        <select class="form-control" name="parent_category_id" id="parent_category_id" onchange="loadSubcategories()">
                                            <option value="" disabled>Select Category</option>
                                            @foreach($categories as $list)
                                                <option value="{{$list->id}}" {{ $log->parent_category_id == $list->id ? 'selected' : '' }}>
                                                    {{$list->name}} @if($list->name_ar!='')- {{$list->name_ar}}@endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('parent_category_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Type -->
                                <div class="col-lg-4 mb-3" id="type_div" style="display:none;">
                                    <div>
                                        <label>Type <span class="text-danger">*</span></label>
                                        <select class="form-control select2" name="type" id="type">
                                            <option value="" disabled>Select</option>
                                        </select>
                                        @error('type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- URL Field -->
                                <div class="col-lg-4 mb-3" id="url_div" style="display:none;">
                                    <div>
                                        <label>URL <span class="text-danger">*</span></label>
                                        <input class="form-control" type="url" name="url" id="url" placeholder="https://example.com" value="{{ $log->url }}">
                                        @error('url')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Name -->
                                <div class="col-lg-4 mb-3">
                                    <div>
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="name" id="name" required="" value="{{ $log->name }}">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Name in Arabic -->
                                <div class="col-lg-4 mb-3">
                                    <div>
                                        <label>Name in Arabic</label>
                                        <input class="form-control" type="text" name="name_ar" id="name_ar" value="{{ $log->name_ar }}">
                                        @error('name_ar')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-lg-4 mb-3">
                                    <div>
                                        <label>Description</label>
                                        <textarea class="form-control" name="description" id="description">{{ $log->description }}</textarea>
                                        @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Description in Arabic -->
                                <div class="col-lg-4 mb-3">
                                    <div>
                                        <label>Description in Arabic</label>
                                        <textarea class="form-control" name="description_ar" id="description_ar">{{ $log->description_ar }}</textarea>
                                        @error('description_ar')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Image File -->
                                <div class="col-lg-4 mb-3">
                                    <div>
                                        <label for="formFileSm" class="form-label">Image file <small class="text-muted ms-1">(File size should be 270x320)</small></label>
                                        <input class="form-control" name="imgfile" id="imgfile" type="file">
                                        <input type="hidden" name="imgfile_val" value="{{$log->imageurl}}">
                                        @error('imgfile')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Current Images -->
                                <div class="col-lg-2 mt-3 mb-3">
                                    @if($log->imageurl)
                                        <img src="{{asset($log->imageurl)}}" width="80" alt="" title="Current Image"> 
                                    @endif
                                </div>

                                <!-- Buttons -->
                                <div class="col-lg-4 mt-3">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Update</button>
                                    <a href="{{url('/topcollection')}}" class="btn btn-secondary waves-effect waves-light resetbtn mt-2">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>

@endsection

@section('ScriptContent')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    var existingType = '{{ $log->type }}';
    var existingShopby = '{{ strtolower($log->shopby) }}';
    var existingParentCategory = '{{ $log->parent_category_id }}';
    var isInitialLoad = true;

    $(document).ready(function() {
        // Initialize Select2
        $('#type').select2({
            placeholder: "Select an option",
            allowClear: true,
            width: '100%'
        });

        // Load initial form state
        setTimeout(function() {
            loadurl(true); // Pass true for initial load
            
            setTimeout(function() {
                if (existingShopby) {
                    loadType(true); // Pass true for initial load
                    
                    if (existingShopby == 'subcategory' && existingParentCategory) {
                        setTimeout(function() {
                            loadSubcategories(true); // Pass true for initial load
                        }, 400);
                    }
                }
            }, 200);
        }, 100);
    });

    function loadType(keepSelection) {
        keepSelection = keepSelection || false;
        
        $('#type').html('<option value="">Loading...</option>').prop('disabled', true);
        $('#parent_category_div').hide();
        $('#parent_category_id').removeAttr('required');
        
        var shopby = $('#shopby').val();
        var redirect_type = $('#redirect_type').val();
        var url = '';
        var placeholderText = 'Select an option';

        if (shopby == 'category') {
            url = '/category/all';
            placeholderText = 'Select category';
        } else if (shopby == 'subcategory') {
            if (redirect_type == 'product_listing') {
                $('#parent_category_div').show();
                $('#parent_category_id').attr('required', true);
                
                // If initial load and parent category exists, load subcategories
                if (keepSelection && existingParentCategory) {
                    $('#parent_category_id').val(existingParentCategory);
                    $('#type').html('<option value="" disabled selected>Loading subcategories...</option>').prop('disabled', false);
                    $('#type').select2('destroy').select2({
                        placeholder: "Select subcategory",
                        allowClear: true,
                        width: '100%'
                    });
                    // Don't load here, let loadSubcategories handle it
                    return;
                } else {
                    $('#type').html('<option value="" disabled selected>Select parent category first</option>').prop('disabled', false);
                    $('#type').select2('destroy').select2({
                        placeholder: "Select parent category first",
                        allowClear: true,
                        width: '100%'
                    });
                    return;
                }
            }
        } else if (shopby == 'brand') {
            url = '/brand/all';
            placeholderText = 'Select brand';
        } else if (shopby == 'product') {
            url = '/product/all';
            placeholderText = 'Select product';
        }

        if (!url) {
            return;
        }

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var options = '<option value="" disabled selected>Select</option>';
                
                $.each(data, function(i, item) {
                    // Pre-select on initial load only
                    var selected = (keepSelection && existingType == item.id) ? 'selected' : '';
                    options += '<option value="' + item.id + '" ' + selected + '>' + item.name;
                    if (item.name_ar) {
                        options += ' - ' + item.name_ar;
                    }
                    options += '</option>';
                });
                
                $('#type').html(options).prop('disabled', false);
                $('#type').select2('destroy').select2({
                    placeholder: placeholderText,
                    allowClear: true,
                    width: '100%'
                });
                
                // Mark initial load as complete after successful load
                if (keepSelection) {
                    isInitialLoad = false;
                }
            },
            error: function() {
                alert('Error loading data');
                $('#type').html('<option value="" disabled>Error loading data</option>').prop('disabled', false);
            }
        });
    }

    function loadSubcategories(keepSelection) {
        keepSelection = keepSelection || false;
        
        var parentCategoryId = $('#parent_category_id').val();
        
        if (!parentCategoryId) {
            return;
        }

        $('#type').html('<option value="">Loading...</option>').prop('disabled', true);
        
        $.ajax({
            url: '/subcategory/by-category/' + parentCategoryId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var options = '<option value="" disabled selected>Select subcategory</option>';
                
                if (data.length > 0) {
                    $.each(data, function(index, subcategory) {
                        // Pre-select on initial load only
                        var selected = (keepSelection && existingType == subcategory.id) ? 'selected' : '';
                        options += '<option value="' + subcategory.id + '" ' + selected + '>' + subcategory.name;
                        if (subcategory.name_ar) {
                            options += ' - ' + subcategory.name_ar;
                        }
                        options += '</option>';
                    });
                } else {
                    options = '<option value="" disabled>No subcategories found</option>';
                }
                
                $('#type').html(options).prop('disabled', false);
                $('#type').select2('destroy').select2({
                    placeholder: "Select subcategory",
                    allowClear: true,
                    width: '100%'
                });
                
                // Mark initial load as complete
                if (keepSelection) {
                    isInitialLoad = false;
                }
            },
            error: function() {
                alert('Error loading subcategories');
                $('#type').html('<option value="" disabled>Error loading data</option>').prop('disabled', false);
            }
        });
    }

    function loadurl(isInit) {
        isInit = isInit || false;
        
        var redirect_type = $('#redirect_type').val();
        
        // Don't clear on initial load, clear on user changes
        if (!isInit) {
            $('#type').html('<option value="" disabled selected>Select</option>');
            $('#type').val('').trigger('change');
            
            // Clear shopby if it will be auto-set
            if (redirect_type == 'category' || redirect_type == 'product_detail') {
                $('#shopby').val('');
            }
            
            isInitialLoad = false;
        }
        
        // Hide all sections first
        $('#shopby_div').hide();
        $('#type_div').hide();
        $('#url_div').hide();
        $('#parent_category_div').hide();
        
        // Remove all required and readonly
        $('#shopby').removeAttr('required').removeClass('readonly-field').css('pointer-events', '');
        $('#type').removeAttr('required');
        $('#url').removeAttr('required');
        $('#parent_category_id').removeAttr('required');
        
        // Hide all shopby options
        $('.shopby-option').hide();

        if (redirect_type == 'category') {
            $('#shopby_div').show();
            $('#type_div').show();
            $('.category-option').show();
            $('#shopby').val('category').attr('required', true).addClass('readonly-field').css('pointer-events', 'none');
            $('#type').attr('required', true);
            if (!isInit) loadType(false);
            
        } else if (redirect_type == 'product_listing') {
            $('#shopby_div').show();
            $('#type_div').show();
            $('.category-option, .subcategory-option, .brand-option').show();
            $('#shopby').attr('required', true);
            $('#type').attr('required', true);
            
        } else if (redirect_type == 'product_detail') {
            $('#shopby_div').show();
            $('#type_div').show();
            $('.product-option').show();
            $('#shopby').val('product').attr('required', true).addClass('readonly-field').css('pointer-events', 'none');
            $('#type').attr('required', true);
            if (!isInit) loadType(false);
            
        } else if (redirect_type == 'URL') {
            $('#url_div').show();
            $('#url').attr('required', true);
        }
    }

    // Form submission
    $('#bannerForm').on('submit', function(e) {
        return true;
    });
</script>

@stop