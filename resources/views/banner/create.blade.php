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
                <form  action="{{url('/banner/store')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}  
                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label for="formFileSm" class="form-label">Desktop Image File <small class="text-muted ms-1">(File size should be 1440*400)</small> </label>
                            <input class="form-control" name="imgfile" id="imgfile" type="file" required="">
                        </div>
                    @error('imgfile') <span class="font-size-12 ms-1 text-danger">{{ $message }}</strong> </span> @enderror
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label for="formFileSm" class="form-label">Mobile Image File <small class="text-muted ms-1">(File size should be 320x320)</small> </label>
                            <input class="form-control" name="imgfile_sm" id="imgfile_sm" type="file" required="">
                        </div>
                    @error('imgfile_sm') <span class="font-size-12 ms-1 text-danger">{{ $message }}</strong> </span> @enderror
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Sequence <span class="text-danger">*</span></label>
                            <input class="form-control" type="number" name="order_id" id="order_id" required="">
                        </div>
                    </div>                   
                    <div class="col-lg-4 mt-3 mb-3">
                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2">Create</button>
                        <a href="{{url('/banner')}}" class="btn btn-secondary waves-effect waves-light resetbtn">Cancel</a>
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
</script>
@stop