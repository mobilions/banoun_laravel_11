@extends('layouts.master')

@section('title',$title)

@section('StyleContent')

<link href="{{asset('/assets')}}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/spectrum-colorpicker2/spectrum.min.css" rel="stylesheet" type="text/css">

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


<!-- Search Tag Modal -->
<div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="myModalLabel">Add New Searchtag</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div>
                    <label>Name: <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" placeholder="Enter Name" value="" name="name" id="search_name" required="">
                </div>
            </div>
            <div class="col-lg-12 mb-3">
                <div>
                    <label>Name in Arabic:</label>
                    <input class="form-control" type="text" placeholder="Enter Name in Arabic" value="" name="name_ar" id="search_name_ar">
                </div>
            </div>
        </div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
<button type="button" class="btn btn-primary waves-effect waves-light" onclick="saveSearchTag()">Submit</button>
</div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Size Modal -->
<div id="sizeModal" class="modal fade" tabindex="-1" aria-labelledby="sizeModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="sizeModalLabel">Add New Size</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div>
                    <label>Name: <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" placeholder="Enter Size Name (e.g., Small, Medium, Large)" value="" name="size_name" id="size_name" required="">
                </div>
            </div>
            <div class="col-lg-12 mb-3">
                <div>
                    <label>Name in Arabic:</label>
                    <input class="form-control" type="text" placeholder="Enter Name in Arabic" value="" name="size_name_ar" id="size_name_ar">
                </div>
            </div>
            <div class="col-lg-12 mb-3">
                <div>
                    <label>Age/Value:</label>
                    <input class="form-control" type="text" placeholder="Enter Age or Value (e.g., 0.1, 3-6 months)" value="" name="size_age" id="size_age">
                    <small class="text-muted">If Less than One Year Mention 0.1</small>
                </div>
            </div>
        </div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
<button type="button" class="btn btn-primary waves-effect waves-light" onclick="saveSize()">Submit</button>
</div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Color Modal -->
<div id="colorModal" class="modal fade" tabindex="-1" aria-labelledby="colorModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="colorModalLabel">Add New Color</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div>
                    <label>Name: <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" placeholder="Enter Color Name (e.g., Red, Blue)" value="" name="color_name" id="color_name" required="">
                </div>
            </div>
            <div class="col-lg-12 mb-3">
                <div>
                    <label>Name in Arabic:</label>
                    <input class="form-control" type="text" placeholder="Enter Name in Arabic" value="" name="color_name_ar" id="color_name_ar">
                </div>
            </div>
            <div class="col-lg-12 mb-3">
                <div>
                    <label>Color Value:</label>
                    <input type="text" name="color_value" class="form-control" id="colorpicker-modal" value="#50a5f1" placeholder="#hexcode or color name">
                </div>
            </div>
        </div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
<button type="button" class="btn btn-primary waves-effect waves-light" onclick="saveColor()">Submit</button>
</div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="row">

<div class="col-12">

    <div class="page-title-box d-sm-flex align-items-center justify-content-between">

        <h4 class="mb-sm-0 font-size-16">Create {{$title}}</h4>

        <div class="page-title-right">

            <ol class="breadcrumb m-0">

                    <li class="breadcrumb-item"><a href="javascript: void(0);">Create</a></li>

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

        <div class="row">

            <div class="col-sm-12 pt-3">

                <form  action="{{url('/product/store')}}" method="post" enctype="multipart/form-data" id="productForm">

                {{csrf_field()}}  

                <div class="row">

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label>Category <span class="text-danger">*</span></label>

                            <select onchange="loadType()" class="form-control" name="category_id" id="category_id" required="">

                                <option value="">Select</option>

                                 @foreach($categories as $cat)

                                  <option value="{{$cat->id}}">{{$cat->name}} @if($cat->name_ar!='')- {{$cat->name_ar}}@endif</option>

                                 @endforeach

                            </select>
                            @error('category_id') <span class="text-danger font-size-12">{{ $message }}</span> @enderror
                        </div>

                    </div>

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label>SubCategory <span class="text-danger">*</span></label>

                            <select class="form-control" name="subcategory_id" id="subcategory_id" required="">

                                <option value="">Select</option>

                                 @foreach($subcategories as $sub)

                                  <option class="all category_{{$sub->category_id}}" value="{{$sub->id}}">{{$sub->name}} @if($sub->name_ar!='')- {{$sub->name_ar}}@endif</option>

                                 @endforeach

                            </select>
                            @error('subcategory_id') <span class="text-danger font-size-12">{{ $message }}</span> @enderror
                        </div>

                    </div>

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label>Brand <span class="text-danger">*</span></label>

                            <select class="form-control" name="brand_id" id="brand_id" required="" onchange='SelectBrandTag()'>

                                <option value="">Select</option>

                                 @foreach($brands as $brd)

                                  <option value="{{$brd->id}}">{{$brd->name}} @if($brd->name_ar!='')- {{$brd->name_ar}}@endif</option>

                                 @endforeach

                            </select>
                            @error('brand_id') <span class="text-danger font-size-12">{{ $message }}</span> @enderror
                        </div>

                    </div>

                    <div class="col-lg-6 mb-3">

                        <div>

                            <label>Name <span class="text-danger">*</span></label>

                            <input class="form-control" type="text" name="name" id="name" required="">

                        </div>

                    </div>

                    <div class="col-lg-6 mb-3">

                        <div>

                            <label>Name in arabic</label>

                            <input class="form-control" type="text" name="name_ar" id="name_ar">

                        </div>

                    </div>

                    

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label>Price <span class="text-danger">*</span></label>

                            <input class="form-control" type="text" name="price" id="price" required="">

                        </div>

                    </div>

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label>Offer Price <span class="text-danger">*</span></label>

                            <input class="form-control" type="text" name="price_offer" id="price_offer" required="">

                        </div>

                    </div>

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label>Discount % <span class="text-danger">*</span></label>

                            <input class="form-control" type="text" name="percentage_discount" id="percentage_discount" value="0" required="">

                        </div>

                    </div>

                        

                    <div class="col-lg-6 mb-3">

                        <div>

                            <label>Search Tages <button type="button" class="btn-sm btn-primary btn-sm btn-rounded waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#myModal">Add<i class="mdi mdi-plus ms-1"></i></button></label>

                            <select class="select2 form-control select2-multiple" name="searchtag_id[]" id="searchtag_id"  multiple="multiple" required="" data-placeholder="Choose ...">

                                <option value="">Select</option>

                                 @foreach($searchtags as $list)

                                  <option value="{{$list->id}}">{{$list->title}} @if($list->title_ar!='')- {{$list->title_ar}}@endif</option>

                                 @endforeach

                            </select>
                            @error('searchtag_id') <span class="text-danger font-size-12">{{ $message }}</span> @enderror
                        </div>

                    </div>

                     <div class="col-lg-3 mb-3"><br>

                        <input type="checkbox" name="is_newarrival" id="is_newarrival" class="form-check-input" checked>

                        <label class="form-check-label" for="is_newarrival">is New Arrival</label><br>

                        <input type="checkbox" name="is_trending" id="is_trending" class="form-check-input" checked>

                        <label class="form-check-label" for="is_trending">is Trending</label>

                    </div> 

                    <div class="col-lg-3 mb-3"><br>

                        <input type="checkbox" name="is_topsearch" id="is_topsearch" class="form-check-input" checked>

                        <label class="form-check-label" for="is_topsearch">is Top Search</label><br>

                        <input type="checkbox" name="is_recommended" id="is_recommended" class="form-check-input" checked>

                        <label class="form-check-label" for="is_recommended">is Recommended</label>

                    </div> 

                    

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label>Select Size<span class="text-danger">*</span> <button type="button" class="btn-sm btn-primary btn-sm btn-rounded waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#sizeModal">Add<i class="mdi mdi-plus ms-1"></i></button></label>

                            <select class="select2 form-control select2-multiple" name="size_id[]" id="size_id"  multiple="multiple" required="" data-placeholder="Choose ..." required="">

                                <option value="">Select</option>

                                 @foreach($sizes as $list)

                                  <option value="{{$list->id}}">{{$list->name}} @if($list->name_ar!='')- {{$list->name_ar}}@endif</option>

                                 @endforeach

                            </select>
                            @error('size_id') <span class="text-danger font-size-12">{{ $message }}</span> @enderror
                        </div>

                    </div>

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label>Select Colors<span class="text-danger">*</span> <button type="button" class="btn-sm btn-primary btn-sm btn-rounded waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#colorModal">Add<i class="mdi mdi-plus ms-1"></i></button></label>

                            <select class="select2 form-control select2-multiple" name="color_id[]" id="color_id"  multiple="multiple" required="" data-placeholder="Choose ..." required="">

                                <option value="">Select</option>

                                 @foreach($colors as $list)

                                  <option value="{{$list->id}}">{{$list->name}} @if($list->name_ar!='')- {{$list->name_ar}}@endif</option>

                                 @endforeach

                            </select>
                            @error('color_id') <span class="text-danger font-size-12">{{ $message }}</span> @enderror
                        </div>

                    </div>



                    <div class="col-lg-4 mb-3">

                        <div>

                            <label for="formFileSm" class="form-label">Image file <small class="text-muted ms-1">(File size should be 140x175)</small></label>

                            <input class="form-control" name="imgfile" id="imgfile" type="file">

                        </div>

                    @error('imgfile') <span class="font-size-12 ms-1 text-danger">{{ $message }}</strong> </span> @enderror

                    </div>  



                    <div class="col-lg-4 mb-3">

                        <div>

                            <label for="formFileSm" class="form-label">Image file 2<small class="text-muted ms-1">(File size should be 140x175)</small></label>

                            <input class="form-control" name="imgfile2" id="imgfile2" type="file">

                        </div>

                    @error('imgfile2') <span class="font-size-12 ms-1 text-danger">{{ $message }}</strong> </span> @enderror

                    </div>  



                    <div class="col-lg-4 mb-3">

                        <div>

                            <label for="formFileSm" class="form-label">Image file 3<small class="text-muted ms-1">(File size should be 140x175)</small></label>

                            <input class="form-control" name="imgfile3" id="imgfile3" type="file">

                        </div>

                    @error('imgfile3') <span class="font-size-12 ms-1 text-danger">{{ $message }}</strong> </span> @enderror

                    </div>  

                    

                    <div class="col-lg-6 mb-3">

                        <div>

                            <label>Description</label>

                            <textarea class="form-control" type="text" name="description" id="description"></textarea>

                        </div>

                    </div>

                    <div class="col-lg-6 mb-3">

                        <div>

                            <label>Description in arabic</label>

                            <textarea class="form-control" type="text" name="description_ar" id="description_ar"></textarea>

                        </div>

                    </div>      

                    <div class="col-lg-6 mb-3">

                        <div>

                            <label>More Info</label>

                            <textarea class="form-control" type="text" name="more_info" id="more_info"></textarea>

                        </div>

                    </div>

                    <div class="col-lg-6 mb-3">

                        <div>

                            <label>More Info in arabic</label>

                            <textarea class="form-control" type="text" name="more_info_ar" id="more_info_ar"></textarea>

                        </div>

                    </div>              

                    <div class="col-lg-6 mt-3 mb-3">

                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2">Create</button>

                        <a href="{{url('/product')}}" class="btn btn-secondary waves-effect waves-light resetbtn">Cancel</a>

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

<script>

    $('.all').hide();

    function loadType() {

       var category_id=$('#category_id').val();

       if(category_id!=''){



         $('.all').hide();

         var category_id='category_'+category_id;

         $('.'+category_id).show();

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
<script src="{{asset('/assets')}}/libs/spectrum-colorpicker2/spectrum.min.js"></script>
<script src="{{asset('/assets')}}/js/pages/form-advanced.init.js"></script>

<script type="text/javascript">
    function saveSearchTag() {
        var name=$('#search_name').val().trim();
        var name_ar=$('#search_name_ar').val().trim();
        if(name==''){
            alert("Name is Required");
            $('#search_name').focus();
            return false;
        }
        else{
            if(name_ar==''){
                name_ar=name;
            }
            url1="{{url('/addsearchTag')}}/"+encodeURIComponent(name)+'/'+encodeURIComponent(name_ar);
            $.ajax({ 
                url: url1,
                type: "get", 
                cache: false, 
                dataType: 'json',
                beforeSend: function() {
                    // Disable submit button to prevent double submission
                    $('button[onclick="saveSearchTag()"]').prop('disabled', true);
                },
                success: function (data) {
                    if(data && data.id) {
                        // Check if option already exists
                        var exists = false;
                        $("#searchtag_id option").each(function() {
                            if ($(this).val() == data.id) {
                                exists = true;
                                return false;
                            }
                        });
                        
                        if(!exists) {
                            var newState = new Option(name, data.id, true, true);
                            $("#searchtag_id").append(newState).trigger('change');
                        } else {
                            $("#searchtag_id").val(data.id).trigger('change');
                        }
                        
                        // Clear modal form fields
                        $('#search_name').val('');
                        $('#search_name_ar').val('');
                        
                        // Hide modal and remove backdrop
                        $('#myModal').modal('hide');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        
                        // Restore scrolling
                        setTimeout(function() {
                            $('body').css({
                                'padding-right': '',
                                'overflow': '',
                                'overflow-y': 'auto'
                            });
                            $('html').css({
                                'overflow': '',
                                'overflow-y': 'auto'
                            });
                        }, 300);
                    } else {
                        alert('Error: Could not add search tag. Please try again.');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error adding search tag: ' + (xhr.responseJSON?.message || 'Please try again.'));
                },
                complete: function() {
                    // Re-enable submit button
                    $('button[onclick="saveSearchTag()"]').prop('disabled', false);
                }
            })
        }
        return false;
    }

    function SelectBrandTag() {
       var brand_id=$('#brand_id').val();
       if(brand_id!=''){
            url1="{{url('/selectBrandTag')}}/"+brand_id;
            $.ajax({ url: url1,
                type: "get", cache: false, dataType: 'json',
                success: function (data) {
     
                    if(data.id!=0){
                        $("#searchtag_id").val(data.id).trigger('change');
                    }
                }
            })
       }
    }

    function saveSize() {
        var name=$('#size_name').val().trim();
        var name_ar=$('#size_name_ar').val().trim();
        var age=$('#size_age').val().trim();
        var variant_id = 1; // Size variant ID
        
        if(name==''){
            alert("Name is Required");
            $('#size_name').focus();
            return false;
        }
        else{
            if(name_ar==''){
                name_ar=name;
            }
            url1="{{url('/addVariantValue')}}/"+variant_id+'/'+encodeURIComponent(name)+'/'+encodeURIComponent(name_ar)+'/'+encodeURIComponent(age);
            $.ajax({ 
                url: url1,
                type: "get", 
                cache: false, 
                dataType: 'json',
                beforeSend: function() {
                    $('button[onclick="saveSize()"]').prop('disabled', true);
                },
                success: function (data) {
                    if(data && data.id) {
                        var exists = false;
                        $("#size_id option").each(function() {
                            if ($(this).val() == data.id) {
                                exists = true;
                                return false;
                            }
                        });
                        
                        if(!exists) {
                            var newState = new Option(name, data.id, true, true);
                            $("#size_id").append(newState).trigger('change');
                        } else {
                            $("#size_id").val(data.id).trigger('change');
                        }
                        
                        $('#size_name').val('');
                        $('#size_name_ar').val('');
                        $('#size_age').val('');
                        
                        $('#sizeModal').modal('hide');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        
                        setTimeout(function() {
                            $('body').css({
                                'padding-right': '',
                                'overflow': '',
                                'overflow-y': 'auto'
                            });
                            $('html').css({
                                'overflow': '',
                                'overflow-y': 'auto'
                            });
                        }, 300);
                    } else {
                        alert('Error: Could not add size. Please try again.');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error adding size: ' + (xhr.responseJSON?.message || 'Please try again.'));
                },
                complete: function() {
                    $('button[onclick="saveSize()"]').prop('disabled', false);
                }
            })
        }
        return false;
    }

    function saveColor() {
        var name=$('#color_name').val().trim();
        var name_ar=$('#color_name_ar').val().trim();
        var color_val=$('#colorpicker-modal').val().trim();
        var variant_id = 2; // Color variant ID
        
        if(name==''){
            alert("Name is Required");
            $('#color_name').focus();
            return false;
        }
        else{
            if(name_ar==''){
                name_ar=name;
            }
            if(color_val==''){
                color_val='#50a5f1';
            }
            url1="{{url('/addVariantValue')}}/"+variant_id+'/'+encodeURIComponent(name)+'/'+encodeURIComponent(name_ar)+'/'+encodeURIComponent(color_val);
            $.ajax({ 
                url: url1,
                type: "get", 
                cache: false, 
                dataType: 'json',
                beforeSend: function() {
                    $('button[onclick="saveColor()"]').prop('disabled', true);
                },
                success: function (data) {
                    if(data && data.id) {
                        var exists = false;
                        $("#color_id option").each(function() {
                            if ($(this).val() == data.id) {
                                exists = true;
                                return false;
                            }
                        });
                        
                        if(!exists) {
                            var newState = new Option(name, data.id, true, true);
                            $("#color_id").append(newState).trigger('change');
                        } else {
                            $("#color_id").val(data.id).trigger('change');
                        }
                        
                        $('#color_name').val('');
                        $('#color_name_ar').val('');
                        $('#colorpicker-modal').val('#50a5f1');
                        
                        $('#colorModal').modal('hide');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        
                        setTimeout(function() {
                            $('body').css({
                                'padding-right': '',
                                'overflow': '',
                                'overflow-y': 'auto'
                            });
                            $('html').css({
                                'overflow': '',
                                'overflow-y': 'auto'
                            });
                        }, 300);
                    } else {
                        alert('Error: Could not add color. Please try again.');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error adding color: ' + (xhr.responseJSON?.message || 'Please try again.'));
                },
                complete: function() {
                    $('button[onclick="saveColor()"]').prop('disabled', false);
                }
            })
        }
        return false;
    }

</script>

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

            $form.on('submit', function (event) {
                let hasError = false;
                ['#searchtag_id', '#size_id', '#color_id'].forEach(function (selector) {
                    const $field = $form.find(selector);
                    if ($field.length) {
                        if (!$field.val() || !$field.val().length) {
                            hasError = true;
                            $field.siblings('.invalid-feedback.client-side').remove();
                            $field.after('<div class="invalid-feedback client-side d-block">This field is required.</div>');
                        } else {
                            $field.siblings('.invalid-feedback.client-side').remove();
                        }
                    }
                });
                if (hasError) {
                    event.preventDefault();
                }
            });
        };

        $(function () {
            initSelect2Within('#productForm');
            
            // Clear modal forms when modals are opened
            $('#myModal').on('show.bs.modal', function () {
                $('#search_name').val('');
                $('#search_name_ar').val('');
                $('button[onclick="saveSearchTag()"]').prop('disabled', false);
                $('#search_name').removeClass('is-invalid');
                $('#search_name_ar').removeClass('is-invalid');
            });
            
            $('#sizeModal').on('show.bs.modal', function () {
                $('#size_name').val('');
                $('#size_name_ar').val('');
                $('#size_age').val('');
                $('button[onclick="saveSize()"]').prop('disabled', false);
            });
            
            $('#colorModal').on('show.bs.modal', function () {
                $('#color_name').val('');
                $('#color_name_ar').val('');
                $('#colorpicker-modal').val('#50a5f1');
                $('button[onclick="saveColor()"]').prop('disabled', false);
                // Initialize color picker if not already initialized
                if (!$('#colorpicker-modal').hasClass('spectrum-initialized')) {
                    $('#colorpicker-modal').spectrum({
                        color: '#50a5f1',
                        preferredFormat: "hex"
                    }).addClass('spectrum-initialized');
                }
            });
            
            // Clean up when modals are closed
            $('#myModal, #sizeModal, #colorModal').on('hidden.bs.modal', function () {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').css({
                    'padding-right': '',
                    'overflow': '',
                    'overflow-y': 'auto'
                });
                // Ensure page can scroll after modal closes
                setTimeout(function() {
                    $('html, body').css({
                        'overflow': '',
                        'overflow-y': 'auto',
                        'height': 'auto'
                    });
                }, 100);
            });
            
            // Handle Enter key in modals - prevent form submission, trigger save instead
            $('#myModal').on('keydown', function(e) {
                if (e.key === 'Enter' && (e.target.id === 'search_name' || e.target.id === 'search_name_ar')) {
                    e.preventDefault();
                    saveSearchTag();
                }
            });
            
            $('#sizeModal').on('keydown', function(e) {
                if (e.key === 'Enter' && (e.target.id === 'size_name' || e.target.id === 'size_name_ar' || e.target.id === 'size_age')) {
                    e.preventDefault();
                    saveSize();
                }
            });
            
            $('#colorModal').on('keydown', function(e) {
                if (e.key === 'Enter' && (e.target.id === 'color_name' || e.target.id === 'color_name_ar')) {
                    e.preventDefault();
                    saveColor();
                }
            });
        });
    })(jQuery);
</script>

@stop