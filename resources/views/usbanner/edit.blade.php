@extends('layouts.master')
@section('title',$title)
@section('StyleContent')
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
                <form  action="{{url('/usbanner/update')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}  
                <div class="row">
                  <div class="col-lg-4 mb-3">
                        <div>
                            <label>Select Redirection Type <span class="text-danger">*</span></label>
                            <select  class="form-control" name="redirect_type" id="redirect_type" onchange="loadurl()" required="">
                                <option value="">Select</option>
                              @foreach($cattype as $ctype)
                                <option @if($log->redirect_type==$ctype->base_url) selected="" @endif value="{{$ctype->base_url}}">{{$ctype->name}}</option>
                              @endforeach
                            </select>
                        </div>
                    </div>
                     <div class="col-lg-6 mb-3">
                        <div>
                            <label>Shop By <span class="text-danger">*</span></label>
                            <select onchange="loadType()" class="form-control" name="shopby" id="shopby" required="">
                                <option value="">Select</option>
                                <option @if($log->shopby=='category') selected="" @endif value="category">Category</option>
                                <option @if($log->shopby=='brand') selected="" @endif value="brand">Brand</option>
                                <option @if($log->shopby=='subcategory') selected="" @endif value="subcategory">SubCategory</option>
                                <option @if($log->shopby=='product') selected="" @endif value="product">Product</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div>
                            <label>Type<span class="text-danger">*</span></label>
                            <select class="form-control" name="category_id" id="category_id" required="">
                                <option value="">Select</option>
                                 @foreach($categories as $list)
                                  <option  @if($log->shopby=='category' && $log->category_id==$list->id) selected="" @endif class="all category" value="{{$list->id}}">{{$list->name}} @if($list->name_ar!='')- {{$list->name_ar}}@endif</option>
                                 @endforeach

                                 @foreach($subcategories as $list)
                                  <option @if($log->shopby=='subcategory' && $log->category_id==$list->id) selected="" @endif class="all subcategory" value="{{$list->id}}">{{$list->name}} @if($list->name_ar!='')- {{$list->name_ar}}  {{App\Models\Category::FindName($list->category_id)}} @endif</option>
                                 @endforeach

                                 @foreach($brands as $list)
                                  <option @if($log->shopby=='brand' && $log->category_id==$list->id) selected="" @endif class="all brand" value="{{$list->id}}">{{$list->name}} @if($list->name_ar!='')- {{$list->name_ar}}@endif</option>
                                 @endforeach

                                 @foreach($products as $list)
                                  <option @if($log->shopby=='product' && $log->category_id==$list->id) selected="" @endif class="all product" value="{{$list->id}}">{{$list->name}} @if($list->name_ar!='')- {{$list->name_ar}}@endif</option>
                                 @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div>
                            <label>Name <span class="text-danger">*</span></label>
                            <input class="form-control" value="{{$log->name}}" type="text" name="name" id="name" required="">
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div>
                            <label>Name in arabic</label>
                            <input class="form-control" value="{{$log->name_ar}}" type="text" name="name_ar" id="name_ar">
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
                      <select id="grid" name="grid"  class="form-control">
                        <option @if($log->grid=='1') selected="" @endif value="1">Big</option>
                        <option @if($log->grid=='2') selected="" @endif value="2" >Small</option>
                      </select>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div>
                            <label for="formFileSm" class="form-label">Image file </label>
                            <input class="form-control" name="imgfile" id="imgfile" type="file">
                            <input class="form-control" value="{{$log->imageurl}}"  name="imgfile_val" id="imgfile_val" type="hidden">
                            <input class="form-control" value="{{$log->id}}"  name="editid" id="editid" type="hidden">
                            <input class="form-control" value="{{$log->category_id}}"  name="cat" id="cat" type="hidden">
                        </div>
                    @error('imgfile') <span class="font-size-12 ms-1 text-danger">{{ $message }}</strong> </span> @enderror
                    </div>
                    <div class="col-lg-6 mb-3" style="display: none;">
                        <div>
                            <label for="formFileSm" class="form-label">Mobile Image File  </label>
                            <input class="form-control" name="imgfile_sm" id="imgfile_sm" type="file">
                            <input class="form-control" value="{{$log->image_sm}}"  name="imgfile_val_sm" id="imgfile_val_sm" type="hidden">
                        </div>
                    @error('imgfile_sm') <span class="font-size-12 ms-1 text-danger">{{ $message }}</strong> </span> @enderror
                    </div>
                    <div class="col-lg-2 mt-3 mb-3">
                       <img src="{{$log->imageurl}}" width="80" alt="" title=""> 
                    </div>                      
                    <div class="col-lg-6 mt-3 mb-3">
                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2">Update</button>
                        <a href="{{url('/usbanner')}}" class="btn btn-secondary waves-effect waves-light resetbtn">Cancel</a>
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
    loadurl();
    loadType();
    function loadType() {
      
       $('.all').hide();
       var shopby=$('#shopby').val();
       if(shopby=='category'){
         $('.category').show();
       }
       else if(shopby=='subcategory'){
         $('.subcategory').show();
       }
       else if(shopby=='brand'){
         $('.brand').show();
       }
    }

     function loadurl(){

      var url_type=$('#redirect_type').val();
      if(url_type=='categorylist'){
        $('#shopby').val('category');
        loadType();
      }
      else if(url_type=='subcategories'){
        $('#shopby').val('subcategory');
        loadType();
      }
      else if(url_type=='productdetails'){
        $('#shopby').val('product');
        loadType();
      }
      else if(url_type=='brandlist'){
        $('#shopby').val('brand');
        loadType();
      }
      else{
        $('#shopby').val('');
        loadType();
      }

    }
    
</script>
@stop