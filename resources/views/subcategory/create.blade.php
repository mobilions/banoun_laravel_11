@extends('layouts.master')

@section('title',$title)

@section('StyleContent')

<link href="{{asset('/assets')}}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />

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

                <form  action="{{url('/subcategory/store')}}" method="post" enctype="multipart/form-data">

                {{csrf_field()}}  

                <div class="row">

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label>Category <span class="text-danger">*</span></label>

                            <select class="select2 form-control select2-multiple @error('category_id') is-invalid @enderror" name="category_id[]" id="category_id" multiple="multiple" required data-placeholder="Choose ...">

                                <option value="">Select</option>

                                 @foreach($lists as $list)

                                  <option value="{{$list->id}}">{{$list->name}} @if($list->name_ar!='')- {{$list->name_ar}}@endif</option>

                                 @endforeach

                            </select>
                            @error('category_id') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                        </div>

                    </div>

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label>Name <span class="text-danger">*</span></label>

                            <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" id="name" value="{{ old('name') }}" required>
                            @error('name') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                        </div>

                    </div>

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label>Name in arabic</label>

                            <input class="form-control @error('name_ar') is-invalid @enderror" type="text" name="name_ar" id="name_ar" value="{{ old('name_ar') }}">
                            @error('name_ar') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                        </div>

                    </div>

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label>Description</label>

                            <textarea class="form-control @error('description') is-invalid @enderror" type="text" name="description" id="description">{{ old('description') }}</textarea>
                            @error('description') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                        </div>

                    </div>

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label>Description in arabic</label>

                            <textarea class="form-control @error('description_ar') is-invalid @enderror" type="text" name="description_ar" id="description_ar">{{ old('description_ar') }}</textarea>
                            @error('description_ar') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                        </div>

                    </div>

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label for="formFileSm" class="form-label">Image file <span class="text-danger">*</span></label>

                            <input class="form-control @error('imgfile') is-invalid @enderror" name="imgfile" id="imgfile" type="file" required>
                            @error('imgfile') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                        </div>

                    </div>                    

                    <div class="col-lg-4 mt-3 mb-3">

                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2">Create</button>

                        <a href="{{url('/subcategory')}}" class="btn btn-secondary waves-effect waves-light resetbtn">Cancel</a>

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

<script src="{{asset('/assets')}}/libs/select2/js/select2.min.js"></script>

<script src="{{asset('/assets')}}/js/pages/form-advanced.init.js"></script>

<script>



</script>

@stop