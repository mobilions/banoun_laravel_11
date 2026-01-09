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

                <form  action="{{url('/brand/store')}}" method="post" enctype="multipart/form-data">

                {{csrf_field()}}  

                <div class="row">

                    <div class="col-lg-6 mb-3">

                        <div>

                            <label for="name">Name <span class="text-danger">*</span></label>

                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name') }}" required>

                        </div>

                    </div>

                    <div class="col-lg-6 mb-3">

                        <div>

                            <label for="name_ar">Name in arabic</label>

                            <input class="form-control" type="text" name="name_ar" id="name_ar" value="{{ old('name_ar') }}">

                        </div>

                    </div>

                    <div class="col-lg-6 mb-3">

                        <div>

                            <label for="description">Description</label>

                            <textarea class="form-control" name="description" id="description">{{ old('description') }}</textarea>

                        </div>

                    </div>

                    <div class="col-lg-6 mb-3">

                        <div>

                            <label for="description_ar">Description in arabic</label>

                            <textarea class="form-control" name="description_ar" id="description_ar">{{ old('description_ar') }}</textarea>

                        </div>

                    </div>

                    <div class="col-lg-6 mb-3">

                        <div>

                            <label for="imgfile" class="form-label">Image file <small class="text-muted ms-1">(File size should be 80x80)</small></label>

                            <input class="form-control" name="imgfile" id="imgfile" type="file" required>

                        </div>

                    </div>                    

                    <div class="col-lg-6 mt-3">

                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Create</button>

                        <a href="{{url('/brand')}}" class="btn btn-secondary waves-effect waves-light resetbtn mt-2">Cancel</a>

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