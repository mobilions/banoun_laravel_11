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
                <form  action="{{url('/carousal/update')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}  
                <div class="row">
                     
                    <div class="col-lg-6 mb-3">
                        <div>
                            <label>Title <span class="text-danger">*</span></label>
                            <input class="form-control" value="{{$log->name}}" type="text" name="name" id="name" required="">
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div>
                            <label>Title in arabic</label>
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
                        <div>
                            <label for="formFileSm" class="form-label">Image file <small class="text-muted ms-1">(File size should be 278x370)</small> </label>
                            <input class="form-control" name="imgfile" id="imgfile" type="file">
                            <input class="form-control" value="{{$log->imageurl}}"  name="imgfile_val" id="imgfile_val" type="hidden">
                            <input class="form-control" value="{{$log->id}}"  name="editid" id="editid" type="hidden">
                            <input class="form-control" value="{{$log->category_id}}"  name="cat" id="cat" type="hidden">
                        </div>
                    @error('imgfile') <span class="font-size-12 ms-1 text-danger">{{ $message }}</strong> </span> @enderror
                    </div>
        
                    <div class="col-lg-2 mt-3 mb-3">
                       <img src="{{$log->imageurl}}" width="80" alt="" title=""> 
                    </div>                    
                    <div class="col-lg-6 mt-3">
                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Update</button>
                        <a href="{{url('/carousal')}}" class="btn btn-secondary waves-effect waves-light resetbtn mt-2">Cancel</a>
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
    loadType();
    function loadType() {
       $('#category_id').val('');
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

       var cat=$('#cat').val();
       $('#category_id').val(cat);
       $('#cat').val('');
    }
    
</script>
@stop