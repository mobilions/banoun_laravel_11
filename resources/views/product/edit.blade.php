@extends('layouts.master')

@section('title',$title)
@php
    use Illuminate\Support\Str;
@endphp

@section('StyleContent')

<link href="{{asset('/assets')}}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />

<style>
    /* Ensure page can scroll properly */
    body {
        overflow-y: auto !important;
        overflow-x: hidden !important;
        height: auto !important;
        min-height: 100vh;
    }
    
    html {
        overflow-y: auto !important;
        height: auto !important;
    }
    
    .main-content {
        overflow-y: visible !important;
        overflow-x: hidden !important;
        height: auto !important;
        min-height: 100vh;
    }
    
    .page-content {
        overflow-y: visible !important;
        overflow-x: hidden !important;
        height: auto !important;
        padding-bottom: 20px;
    }
    
    .container-fluid {
        overflow-y: visible !important;
        overflow-x: hidden !important;
        height: auto !important;
    }
    
    #layout-wrapper {
        overflow-y: visible !important;
        overflow-x: hidden !important;
        height: auto !important;
    }
    
    /* Ensure form container can expand */
    .card-body {
        overflow: visible !important;
    }
    
    /* Fix for modal backdrop preventing scroll */
    body.modal-open {
        overflow: hidden !important;
        padding-right: 0 !important;
    }
    
    /* Ensure TinyMCE editors don't cause issues */
    .tox-tinymce {
        max-width: 100% !important;
    }
</style>

@endsection

@section('PageContent')

<div class="row">

<div class="col-12">

    <div class="page-title-box d-sm-flex align-items-center justify-content-between">

        <h4 class="mb-sm-0 font-size-16">Edit {{$title}}</h4>

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

    <ul class="nav nav-tabs" role="tablist">

        <li class="nav-item">

            <a class="nav-link active" data-bs-toggle="tab" href="#home" role="tab">

                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>

                <span class="d-none d-sm-block">Edit Product</span>    

            </a>

        </li>

        <li class="nav-item">

            <a class="nav-link" data-bs-toggle="tab" href="#profile" role="tab">

                <span class="d-block d-sm-none"><i class="far fa-image"></i></span>

                <span class="d-none d-sm-block">Product Images</span>    

            </a>

        </li>

        <li class="nav-item">

            <a class="nav-link" data-bs-toggle="tab" href="#variants" role="tab">

                <span class="d-block d-sm-none"><i class="fas fa-puzzle-piece"></i></span>

                <span class="d-none d-sm-block">Product Variants</span>    

            </a>

        </li>

    </ul>

    <div class="tab-content p-3 text-muted">

        <div class="tab-pane active" id="home" role="tabpanel">

            <div class="card">

                <div class="card-body">

                    <div class="row">

                        <div class="col-sm-12 pt-3">

                                <form  action="{{url('/product/update')}}" method="post" enctype="multipart/form-data" id="productEditForm">

                                {{csrf_field()}}  

                                <div class="row">

                                    <div class="col-lg-4 mb-3">

                                        <div>

                                            <label>Category <span class="text-danger">*</span></label>

                                            <select onchange="loadType()" class="form-control" name="category_id" id="category_id" >

                                                <option value="" disabled>Select</option>

                                                 @foreach($categories as $cat)

                                                  <option @if($cat->id==$log->category_id) selected='' @endif value="{{$cat->id}}">{{$cat->name}} @if($cat->name_ar!='')- {{$cat->name_ar}}@endif</option>

                                                 @endforeach

                                            </select>

                                        </div>

                                    </div>

                                    <div class="col-lg-4 mb-3">

                                        <div>

                                            <label>SubCategory <span class="text-danger">*</span></label>

                                            <select class="form-control" name="subcategory_id" id="subcategory_id" >

                                                <option value="" disabled>Select</option>

                                                 @foreach($subcategories as $sub)

                                                  <option class="all category_{{$sub->category_id}}" @if($sub->id==$log->subcategory_id) selected='' @endif value="{{$sub->id}}">{{$sub->name}} @if($sub->name_ar!='')- {{$sub->name_ar}}@endif</option>

                                                 @endforeach

                                            </select>

                                        </div>

                                    </div>

                                    <div class="col-lg-4 mb-3">

                                        <div>

                                            <label>Brand <span class="text-danger">*</span></label>

                                            <select class="form-control" name="brand_id" id="brand_id" >

                                                <option value="" disabled>Select</option>

                                                 @foreach($brands as $brd)

                                                  <option @if($brd->id==$log->brand_id) selected='' @endif value="{{$brd->id}}">{{$brd->name}} @if($brd->name_ar!='')- {{$brd->name_ar}}@endif</option>

                                                 @endforeach

                                            </select>

                                        </div>

                                    </div>

                                    <div class="col-lg-6 mb-3">

                                        <div>

                                            <label>Name <span class="text-danger">*</span></label>

                                            <input value="{{$log->name}}" class="form-control" type="text" name="name" id="name" >

                                        </div>

                                    </div>

                                    <div class="col-lg-6 mb-3">

                                        <div>

                                            <label>Name in arabic</label>

                                            <input value="{{$log->name_ar}}" class="form-control" type="text" name="name_ar" id="name_ar">

                                        </div>

                                    </div>

                                    

                                    <div class="col-lg-4 mb-3">

                                        <div>

                                            <label>Price <span class="text-danger">*</span></label>

                                            <input value="{{$log->price}}" class="form-control" type="text" name="price" id="price" >

                                        </div>

                                    </div>

                                    <div class="col-lg-4 mb-3">

                                        <div>

                                            <label>Offer Price <span class="text-danger">*</span></label>

                                            <input class="form-control" value="{{$log->price_offer}}" type="text" name="price_offer" id="price_offer" >

                                        </div>

                                    </div>

                                    <div class="col-lg-4 mb-3">

                                        <div>

                                            <label>Discount % <span class="text-danger">*</span></label>

                                            <input class="form-control" type="text" name="percentage_discount" id="percentage_discount" value="{{$log->percentage_discount}}" >

                                            <input class="form-control" value="{{$log->imageurl}}"  name="imgfile_val" id="imgfile_val" type="hidden">

                                            <input class="form-control" value="{{$log->imageurl2}}"  name="imgfile_val2" id="imgfile_val2" type="hidden">

                                            <input class="form-control" value="{{$log->imageurl3}}"  name="imgfile_val3" id="imgfile_val3" type="hidden">

                                            <input class="form-control" value="{{$log->id}}"  name="editid" id="editid" type="hidden">

                                        </div>

                                    </div>

                                    

                                    

                                    <div class="col-lg-6 mb-3">

                                        <div>

                                            <label>Search Tages</label>

                                            <?php $searc=$log->searchtag_id;

                                                  $searc=explode(',', $searc);

                                             ?>

                                            <select class="select2 form-control select2-multiple" name="searchtag_id[]" id="searchtag_id"  multiple="multiple"  data-placeholder="Select">

                                                 @foreach($searchtags as $list)

                                                  <option {{is_array($searc) && in_array($list->id, $searc) ? 'selected' : '' }}  value="{{$list->id }}" > {{$list->title}} {{$list->title_ar}}</option>

                                                 @endforeach

                                            </select>

                                        </div>

                                    </div>    

                                     <div class="col-lg-3 mb-3"><br>

                                        <input type="checkbox" name="is_newarrival" id="is_newarrival" class="form-check-input" @if($log->is_newarrival=='1') checked @endif>

                                        <label class="form-check-label" for="is_newarrival">is New Arrival</label><br>

                                        <input type="checkbox" name="is_trending" id="is_trending" class="form-check-input" @if($log->is_trending=='1') checked @endif>

                                        <label class="form-check-label" for="is_trending">is Trending</label><br>

                                    </div>  

                                    <div class="col-lg-3 mb-3"><br>

                                        <input type="checkbox" name="is_topsearch" id="is_topsearch" class="form-check-input" @if($log->is_topsearch=='1') checked @endif>

                                        <label class="form-check-label" for="is_topsearch">is Top Search</label><br>

                                        <input type="checkbox" name="is_recommended" id="is_recommended" class="form-check-input" @if($log->is_recommended=='1') checked @endif>

                                        <label class="form-check-label" for="is_recommended">is Recommended</label>



                                    </div>



                                     <div class="col-lg-4 mb-3">

                                        <div>

                                            <label>Select Size<span class="text-danger">*</span></label>

                                            <?php $siz=$log->size;

                                                  $siz=explode(',', $siz);

                                             ?>

                                            <select class="select2 form-control select2-multiple" name="size_id[]" id="size_id"  multiple="multiple"  data-placeholder="Select">

                                                 @foreach($sizes as $list)

                                                  <option {{is_array($siz) && in_array($list->id, $siz) ? 'selected' : '' }}  value="{{$list->id }}" > {{$list->name}} {{$list->name_ar}}</option>

                                                 @endforeach

                                            </select>

                                        </div>

                                    </div>

                                    <div class="col-lg-4 mb-3">

                                        <div>

                                            <label>Select Colors<span class="text-danger">*</span></label>

                                            <?php $colo=$log->colors;

                                                  $colo=explode(',', $colo);

                                             ?>

                                            <select class="select2 form-control select2-multiple" name="color_id[]" id="color_id"  multiple="multiple"  data-placeholder="Select">

                                                 @foreach($colors as $list)

                                                  <option {{is_array($colo) && in_array($list->id, $colo) ? 'selected' : '' }}  value="{{$list->id }}" > {{$list->name}} {{$list->name_ar}}</option>

                                                 @endforeach

                                            </select>

                                        </div>

                                    </div>

                                    <div class="col-lg-6 mb-3">

                                        <div>

                                            <label>Description</label>

                                            <textarea class="form-control" type="text" name="description" id="description">{{$log->description}}</textarea>

                                        </div>

                                    </div>

                                    <div class="col-lg-6 mb-3">

                                        <div>

                                            <label>Description in arabic</label>

                                            <textarea class="form-control" type="text" name="description_ar" id="description_ar">{{$log->description_ar}}</textarea>

                                        </div>

                                    </div>



                                    <div class="col-lg-6 mb-3">

                                        <div>

                                            <label>More Info</label>

                                            <textarea class="form-control" type="text" name="more_info" id="more_info">{{$log->more_info}}</textarea>

                                        </div>

                                    </div>

                                    <div class="col-lg-6 mb-3">

                                        <div>

                                            <label>More Info in arabic</label>

                                            <textarea class="form-control" type="text" name="more_info_ar" id="more_info_ar">{{$log->more_info_ar}}</textarea>

                                        </div>

                                    </div>   

                                    @php
                                        $primaryImage = $log->imageurl ? (\Illuminate\Support\Str::startsWith($log->imageurl, ['http://','https://','//']) ? $log->imageurl : asset($log->imageurl)) : null;
                                        $secondImage = $log->imageurl2 ? (\Illuminate\Support\Str::startsWith($log->imageurl2, ['http://','https://','//']) ? $log->imageurl2 : asset($log->imageurl2)) : null;
                                        $thirdImage = $log->imageurl3 ? (\Illuminate\Support\Str::startsWith($log->imageurl3, ['http://','https://','//']) ? $log->imageurl3 : asset($log->imageurl3)) : null;
                                    @endphp

                                    <!-- <div class="col-lg-2 mt-3 mb-3">
                                       @if($primaryImage)
                                           <img src="{{$primaryImage}}" width="80" alt="{{$log->name}}" title="{{$log->name}}">
                                       @else
                                           <span class="text-muted">No image</span>
                                       @endif
                                    </div>   

                                    <div class="col-lg-2 mt-3 mb-3">
                                       @if($secondImage)
                                           <img src="{{$secondImage}}" width="80" alt="{{$log->name}}" title="{{$log->name}}">
                                       @else
                                           <span class="text-muted">No image</span>
                                       @endif
                                    </div>  

                                    <div class="col-lg-2 mt-3 mb-3">
                                       @if($thirdImage)
                                           <img src="{{$thirdImage}}" width="80" alt="{{$log->name}}" title="{{$log->name}}">
                                       @else
                                           <span class="text-muted">No image</span>
                                       @endif
                                    </div>   -->

                                    <div class="col-lg-6 mt-3 mb-3">

                                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2">Update</button>

                                        <a href="{{url('/product')}}" class="btn btn-secondary waves-effect waves-light resetbtn">Cancel</a>

                                    </div>

                                </form>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>

        </div>

        <div class="tab-pane" id="profile" role="tabpanel">

            <div class="row">

                <div class="col-md-4">

                    <div class="card">

                        <div class="card-body">

                            <div class="row">

                                 <form  action="{{url('productvimage/store')}}" method="post" enctype="multipart/form-data">

                                    {{csrf_field()}}  

                                    <div class="row">

                                        <div class="col-lg-12 mb-3">

                                            <div>

                                                <label for="formFileSm" class="form-label">Image file </label>

                                                <input class="form-control" name="imgfile" id="imgfile" type="file" >

                                                <input type="hidden" name="product_id" value="{{$log->id}}">

                                                <input type="hidden" name="variant_id" value="{{$log->productvariants_id}}">

                                            </div>

                                            @error('imgfile') <span class="font-size-12 ms-1 text-danger">{{ $message }}</strong> </span> @enderror

                                        </div>   

                                    </div>

                                    <div class="col-lg-12 mt-3 mb-3">

                                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2">Update</button>

                                    </div>

                                 </form>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-md-8">

                     <div class="card">

                        <div class="card-body">

                            <h5>{{$log->name}}@if($log->name_ar) {{$log->name_ar}}@endif Images</h5>

                            <div class="row">

                                @forelse($productvariantimages as $image)
                                @php
                                    $imageUrl = $image->imageurl 
                                        ? (Str::startsWith($image->imageurl, ['http://', 'https://', '//']) 
                                            ? $image->imageurl 
                                            : asset($image->imageurl))
                                        : null;
                                @endphp
                                <div class="col-lg-2 mt-3 mb-3">
                                   @if($imageUrl)
                                       <img src="{{$imageUrl}}" width="100" height="100" style="object-fit: cover; border: 1px solid #ddd; border-radius: 4px;" alt="{{$log->name}}" title="{{$log->name}}"> 
                                   @else
                                       <div style="width: 100px; height: 100px; border: 1px solid #ddd; border-radius: 4px; display: flex; align-items: center; justify-content: center; background: #f5f5f5;">
                                           <span class="text-muted small">No image</span>
                                       </div>
                                   @endif
                                   <form action="{{url('productvimage')}}/{{$image->id}}/delete" method="POST" class="mt-2" onsubmit="return confirm('Delete this image?');">
                                       @csrf
                                       <button type="submit" class="btn btn-outline-danger waves-effect waves-light btn-sm font-size-18 w-100">
                                           <i class="mdi mdi-trash-can-outline"></i> Delete
                                       </button>
                                   </form>
                                </div> 
                                @empty
                                <div class="col-12">
                                    <p class="text-muted text-center py-4">No additional images uploaded yet.</p>
                                </div>
                                @endforelse

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="tab-pane" id="variants" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="mb-1">Product Variants</h5>
                            <p class="text-muted mb-0">Manage size/color (or other variant) combinations for this product.</p>
                        </div>
                        <a href="{{ route('productvariantscreatenew', $log->id) }}" class="btn btn-primary">
                            <i class="mdi mdi-plus me-1"></i> Add Variant
                        </a>
                    </div>
                    @if($productVariantList->count())
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Variant</th>
                                        <th>Price</th>
                                        <th>Available Qty</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($productVariantList as $variant)
                                        @php
                                            $sizeName = $variant->size_id ? \App\Models\Variantsub::FindName($variant->size_id) : '—';
                                            $colorName = $variant->color_id ? \App\Models\Variantsub::FindName($variant->color_id) : '—';
                                            $variantLabel = trim($sizeName . ($colorName !== '—' ? ' / ' . $colorName : ''));
                                        @endphp
                                        <tr>
                                            <td>{{ $variant->id }}</td>
                                            <td>{{ $variantLabel ?: 'Default' }}</td>
                                            <td>{{ number_format($variant->price ?? 0, 2) }}</td>
                                            <td>{{ $variant->available_quantity ?? '—' }}</td>
                                            <td>
                                                <a href="{{ url('/productvariants') }}/{{ $variant->id }}/edit" class="btn btn-outline-secondary btn-sm me-2" title="Edit Variant">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                <a href="{{ url('stock') }}/{{ $variant->id }}/{{ $variant->product_id }}" class="btn btn-outline-info btn-sm me-2" title="Manage Stock">
                                                    <i class="mdi mdi-store"></i>
                                                </a>
                                                <form action="{{ url('/productvariants') }}/{{ $variant->id }}/{{ $variant->product_id }}/delete" method="GET" class="d-inline" onsubmit="return confirm('Delete this variant?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete Variant">
                                                        <i class="mdi mdi-trash-can-outline"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted mb-2">No variants created yet.</p>
                            <a href="{{ route('productvariantscreatenew', $log->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="mdi mdi-plus me-1"></i> Create the first variant
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    

</div> 

</div>

@endsection

@section('ScriptContent')

<script>
    $(document).ready(function() {
        // Load subcategories for initially selected category
        var initialCategoryId = $('#category_id').val();
        if(initialCategoryId && initialCategoryId !== '') {
            $('.all').hide();
            $('.category_' + initialCategoryId).show();
        } else {
            $('.all').hide();
        }
    });

    function loadType() {
        var category_id = $('#category_id').val();
        if(category_id && category_id !== '') {
            // Hide all subcategories first
            $('#subcategory_id option.all').hide();
            // Show only subcategories for selected category
            $('#subcategory_id option.category_' + category_id).show();
            // Reset subcategory selection
            $('#subcategory_id').val('');
        } else {
            // If no category selected, hide all
            $('#subcategory_id option.all').hide();
            $('#subcategory_id').val('');
        }
    }
</script>

<script src="{{asset('/assets')}}/libs/tinymce/tinymce.min.js"></script>

<script>

    $(document).ready(function(){0<$("#description").length&&tinymce.init({selector:"textarea#description",height:300,plugins:["advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker","searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking","save table contextmenu directionality emoticons template paste textcolor"],toolbar:"insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",style_formats:[{title:"Bold text",inline:"b"},{title:"Red text",inline:"span",styles:{color:"#ff0000"}},{title:"Red header",block:"h1",styles:{color:"#ff0000"}},{title:"Example 1",inline:"span",classes:"example1"},{title:"Example 2",inline:"span",classes:"example2"},{title:"Table styles"},{title:"Table row 1",selector:"tr",classes:"tablerow1"}]})});



     $(document).ready(function(){0<$("#description_ar").length&&tinymce.init({selector:"textarea#description_ar",height:300,plugins:["advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker","searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking","save table contextmenu directionality emoticons template paste textcolor"],toolbar:"insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",style_formats:[{title:"Bold text",inline:"b"},{title:"Red text",inline:"span",styles:{color:"#ff0000"}},{title:"Red header",block:"h1",styles:{color:"#ff0000"}},{title:"Example 1",inline:"span",classes:"example1"},{title:"Example 2",inline:"span",classes:"example2"},{title:"Table styles"},{title:"Table row 1",selector:"tr",classes:"tablerow1"}]})});



     $(document).ready(function(){0<$("#more_info").length&&tinymce.init({selector:"textarea#more_info",height:300,plugins:["advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker","searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking","save table contextmenu directionality emoticons template paste textcolor"],toolbar:"insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",style_formats:[{title:"Bold text",inline:"b"},{title:"Red text",inline:"span",styles:{color:"#ff0000"}},{title:"Red header",block:"h1",styles:{color:"#ff0000"}},{title:"Example 1",inline:"span",classes:"example1"},{title:"Example 2",inline:"span",classes:"example2"},{title:"Table styles"},{title:"Table row 1",selector:"tr",classes:"tablerow1"}]})});



     $(document).ready(function(){0<$("#more_info_ar").length&&tinymce.init({selector:"textarea#more_info_ar",height:300,plugins:["advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker","searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking","save table contextmenu directionality emoticons template paste textcolor"],toolbar:"insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",style_formats:[{title:"Bold text",inline:"b"},{title:"Red text",inline:"span",styles:{color:"#ff0000"}},{title:"Red header",block:"h1",styles:{color:"#ff0000"}},{title:"Example 1",inline:"span",classes:"example1"},{title:"Example 2",inline:"span",classes:"example2"},{title:"Table styles"},{title:"Table row 1",selector:"tr",classes:"tablerow1"}]})});



</script>

<script src="{{asset('/assets')}}/libs/select2/js/select2.min.js"></script>

<script src="{{asset('/assets')}}/js/pages/form-advanced.init.js"></script>

<script>
    (function ($) {
        const initSelect2Within = function (formSelector) {
            const $form = $(formSelector);
            if (!$form.length) {
                return;
            }

            ['#searchtag_id', '#size_id', '#color_id'].forEach(function (selector) {
                const $el = $form.find(selector);
                if (!$el.length) {
                    return;
                }
                if ($el.hasClass('select2-hidden-accessible')) {
                    $el.select2('destroy');
                }
                $el.select2({
                    dropdownParent: $form,
                    width: '100%'
                });
            });

            // $form.on('submit', function (event) {
            //     let hasError = false;
            //     ['#searchtag_id', '#size_id', '#color_id'].forEach(function (selector) {
            //         const $field = $form.find(selector);
            //         if ($field.length) {
            //             if (!$field.val() || !$field.val().length) {
            //                 hasError = true;
            //                 $field.siblings('.invalid-feedback.client-side').remove();
            //                 $field.after('<div class="invalid-feedback client-side d-block">This field is required.</div>');
            //             } else {
            //                 $field.siblings('.invalid-feedback.client-side').remove();
            //             }
            //         }
            //     });
            //     if (hasError) {
            //         event.preventDefault();
            //     }
            // });
        };

        $(function () {
            initSelect2Within('#productEditForm');
        });
    })(jQuery);
</script>

@stop