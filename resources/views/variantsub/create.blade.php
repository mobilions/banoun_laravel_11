@extends('layouts.master')
@section('title',$title)
@section('StyleContent')
        <link href="{{asset('/assets')}}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="{{asset('/assets')}}/libs/spectrum-colorpicker2/spectrum.min.css" rel="stylesheet" type="text/css">
@endsection
@section('PageContent')
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-16">{{$title}}</h4>
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
        <h4 class="text-secondary"><span class="">Create</span> {{$title}}</h4>
        <div class="row">
            <div class="col-sm-12 pt-3">
                <form  action="{{url('/variantsub/store')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}  
                <div class="row">
                     <div class="col-lg-4 mb-3">
                        <div>
                            <label>Variant <span class="text-danger">*</span></label>
                            <select class="form-control" name="variant_id" id="variant_id" required="" onchange="checkcolor()">
                                <option value="">Select</option>
                                 @foreach($variants as $list)
                                  <option value="{{$list->id}}">{{$list->name}} @if($list->name_ar!='')- {{$list->name_ar}}@endif</option>
                                 @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="name" id="name" required="">
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Name in arabic</label>
                            <input class="form-control" type="text" name="name_ar" id="name_ar">
                        </div>
                    </div>           
                    <div class="col-lg-4 mb-3" id="color_show" style="display: none;">
                        <div>
                            <label>Color</label>
                           <input type="text" name="color_val" class="form-control" id="colorpicker-default" value="#50a5f1">
                        </div>
                    </div> 
                    <div class="col-lg-4 mb-3" id="size_show" style="display: none;">
                        <div>
                            <label>Age (If Less than One Year Mention 0.1)</label>
                           <input type="text"  name="age" class="form-control" id="age">
                        </div>
                    </div>  

                    <div class="col-lg-4 mt-3 mb-3">
                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2">Create</button>
                        <a href="{{url('/variantsub')}}" class="btn btn-secondary waves-effect waves-light resetbtn">Cancel</a>
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
 function checkcolor() {
     var variant_id=$('#variant_id').val();
     if(variant_id=='2'){
        $('#color_show').show();
        $('#size_show').hide();
     }
     else{
         $('#color_show').hide();
         $('#size_show').show();
     }
 }
</script>
<script src="{{asset('/assets')}}/libs/select2/js/select2.min.js"></script>        
<script src="{{asset('/assets')}}/libs/spectrum-colorpicker2/spectrum.min.js"></script>
<!-- form advanced init -->
<script src="{{asset('/assets')}}/js/pages/form-advanced.init.js"></script>
@stop