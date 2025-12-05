@extends('layouts.master')

@section('title',$title)
@php
    use Illuminate\Support\Str;
@endphp

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

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">

            <div class="col-sm-12 pt-3">

                <form  action="{{url('/category/update')}}" method="post" enctype="multipart/form-data">

                {{csrf_field()}}  

                <div class="row">

                    <div class="col-lg-6 mb-3">

                        <div>

                            <label for="name">Name <span class="text-danger">*</span></label>

                            <input class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $log->name) }}" type="text" name="name" id="name" required>
                            @error('name') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                        </div>

                    </div>

                    <div class="col-lg-6 mb-3">

                        <div>

                            <label for="name_ar">Name in arabic</label>

                            <input class="form-control @error('name_ar') is-invalid @enderror" value="{{ old('name_ar', $log->name_ar) }}" type="text" name="name_ar" id="name_ar">
                            @error('name_ar') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                        </div>

                    </div>

                    <div class="col-lg-6 mb-3">

                        <div>

                            <label for="description">Description</label>

                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description">{{ old('description', $log->description) }}</textarea>
                            @error('description') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                        </div>

                    </div>

                    <div class="col-lg-6 mb-3">

                        <div>

                            <label for="description_ar">Description in arabic</label>

                            <textarea class="form-control @error('description_ar') is-invalid @enderror" name="description_ar" id="description_ar">{{ old('description_ar', $log->description_ar) }}</textarea>
                            @error('description_ar') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                        </div>

                    </div>

                    <div class="col-lg-6 mb-3">

                        <div>

                            <label for="imgfile" class="form-label">Image file <small class="text-muted ms-1">(File size should be 171x120)</small></label>

                            <input class="form-control @error('imgfile') is-invalid @enderror" name="imgfile" id="imgfile" type="file">

                            <input class="form-control" value="{{$log->imageurl}}"  name="imgfile_val" id="imgfile_val" type="hidden">

                            <input class="form-control" value="{{$log->id}}"  name="editid" id="editid" type="hidden">

                        </div>

                    @error('imgfile') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                    </div>

                    <div class="col-lg-2 mt-3 mb-3">
                        @php
                            $categoryImage = $log->imageurl
                                ? (Str::startsWith($log->imageurl, ['http://', 'https://', '//'])
                                    ? $log->imageurl
                                    : asset($log->imageurl))
                                : null;
                        @endphp
                       @if($categoryImage)
                            <img src="{{$categoryImage}}" width="80" alt="{{$log->name}}" title="{{$log->name}}">
                       @else
                            <span class="text-muted">No image</span>
                       @endif
                    </div>                    

                    <div class="col-lg-4 mt-3">

                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Update</button>

                        <a href="{{url('/category')}}" class="btn btn-secondary waves-effect waves-light resetbtn mt-2">Cancel</a>

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