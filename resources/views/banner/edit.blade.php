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

                <form  action="{{url('/banner/update')}}" method="post" enctype="multipart/form-data">

                {{csrf_field()}}  

                <div class="row">

                   

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label for="formFileSm" class="form-label">Desktop Image File <small class="text-muted ms-1">(File size should be 1440*400)</small></label>

                            <input class="form-control" name="imgfile" id="imgfile" type="file">

                            <input class="form-control" value="{{$log->imageurl}}"  name="imgfile_val" id="imgfile_val" type="hidden">

                            <input class="form-control" value="{{$log->id}}"  name="editid" id="editid" type="hidden">

                        </div>


                    </div>

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label for="formFileSm" class="form-label">Mobile Image File  <small class="text-muted ms-1">(File size should be 320x320)</small></label>

                            <input class="form-control" name="imgfile_sm" id="imgfile_sm" type="file">

                            <input class="form-control" value="{{$log->image_sm}}"  name="imgfile_val_sm" id="imgfile_val_sm" type="hidden">

                        </div>


                    </div>

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label>Sequence <span class="text-danger">*</span></label>

                            <input class="form-control" value="{{$log->order_id}}" type="number" name="order_id" id="order_id" required="">

                        </div>

                    </div>

                    <div class="col-lg-2 mt-3 mb-3">

                       <img src="{{$log->imageurl}}" width="80" alt="" title=""> 

                    </div>     

                    <div class="col-lg-2 mt-3 mb-3">

                       <img src="{{$log->image_sm}}" width="80" alt="" title=""> 

                    </div>                    

                    <div class="col-lg-4 mt-3 mb-3">

                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2">Update</button>

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