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

        <div class="row">

            <div class="col-sm-12 pt-3">

                <form  action="{{url('/subcategory/update')}}" method="post" enctype="multipart/form-data">

                {{csrf_field()}}  

                <div class="row">

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label>Category</label>

                            <select class="form-control" name="category_id" id="category_id" required="">

                                <option value="">Select</option>

                                 @foreach($lists as $list)

                                  <option @if($list->id==$log->category_id) selected="" @endif value="{{$list->id}}">{{$list->name}}@if($list->name_ar!='')- {{$list->name_ar}}@endif</option>

                                 @endforeach

                            </select>

                        </div>

                    </div>

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label>Name <span class="text-danger">*</span></label>

                            <input class="form-control" value="{{$log->name}}" type="text" name="name" id="name" required="">

                        </div>

                    </div>

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label>Name in arabic</label>

                            <input class="form-control" value="{{$log->name_ar}}" type="text" name="name_ar" id="name_ar">

                        </div>

                    </div>

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label>Description</label>

                            <textarea class="form-control" type="text" name="description" id="description">{{$log->description}}</textarea>

                        </div>

                    </div>

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label>Description in arabic</label>

                            <textarea class="form-control" type="text" name="description_ar" id="description_ar">{{$log->description_ar}}</textarea>

                        </div>

                    </div>

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label for="formFileSm" class="form-label">Image file </label>

                            <input class="form-control" name="imgfile" id="imgfile" type="file">

                            <input class="form-control" value="{{$log->imageurl}}"  name="imgfile_val" id="imgfile_val" type="hidden">

                            <input class="form-control" value="{{$log->id}}"  name="editid" id="editid" type="hidden">

                        </div>

                    @error('imgfile') <span class="font-size-12 ms-1 text-danger">{{ $message }}</strong> </span> @enderror

                    </div>

                    <div class="col-lg-2 mt-3 mb-3">
                        @php
                            $subcatImage = $log->imageurl
                                ? (Str::startsWith($log->imageurl, ['http://', 'https://', '//'])
                                    ? $log->imageurl
                                    : asset($log->imageurl))
                                : null;
                        @endphp
                       @if($subcatImage)
                            <img src="{{$subcatImage}}" width="80" alt="{{$log->name}}" title="{{$log->name}}">
                       @else
                            <span class="text-muted">No image</span>
                       @endif
                    </div>                    

                    <div class="col-lg-4 mt-3 mb-3">

                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2">Update</button>

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

<script>

</script>

@stop