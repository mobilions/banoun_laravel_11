@extends('layouts.master')

@section('title',$title)

@section('StyleContent')

<link href="{{asset('/assets')}}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />

@endsection

@section('PageContent')


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

                            <label>Select Size<span class="text-danger">*</span></label>

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

                            <label>Select Colors<span class="text-danger">*</span></label>

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

<script src="{{asset('/assets')}}/js/pages/form-advanced.init.js"></script>

<script type="text/javascript">
    function saveSearchTag() {
        var name=$('#search_name').val();
        var name_ar=$('#search_name_ar').val();
        if(name==''){
            alert("Name is Required");
        }
        else{
            if(name_ar==''){
                name_ar=name;
            }
            url1="{{url('/addsearchTag')}}/"+name+'/'+name_ar;
            $.ajax({ url: url1,
                type: "get", cache: false, dataType: 'json',
                success: function (data) {
                    var newState = new Option(name, data.id, true, true);
                    // Append it to the select
                    $("#searchtag_id").append(newState).trigger('change');
                     $('#myModal').modal('toggle');
                }
            })
        }
        
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
            $('#myModal').on('hidden.bs.modal', function () {
                $('.modal-backdrop').remove();
            });
        });
    })(jQuery);
</script>

@stop