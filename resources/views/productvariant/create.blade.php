@extends('layouts.master')

@section('title',$title)

@section('StyleContent')

@endsection

@section('PageContent')

<div class="row">

<div class="col-12">

    <div class="page-title-box d-sm-flex align-items-center justify-content-between">

        <h4 class="mb-sm-0 font-size-16">Create {{$title}} - {{App\Models\Product::FindName($id)}} <a href="{{url('/productvariants')}}/{{$id}}" class="btn btn-primary waves-effect waves-light btn-sm ms-3"><i class="fa fa-eye me-1"></i>View {{$title}}</a></h4>

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

                <form  action="{{url('/productvariants/store')}}" method="post" enctype="multipart/form-data">

                {{csrf_field()}}  

                <div class="row">

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label>Size<span class="text-danger">*</span></label>

                            <select class="form-control @error('size_id') is-invalid @enderror" name="size_id" id="size_id" required>

                                <option value="">Select</option>

                                 @foreach($size as $log)

                                  <option value="{{$log->id}}" {{ old('size_id') == $log->id ? 'selected' : '' }}>{{$log->name}} @if($log->name_ar!='')- {{$log->name_ar}}@endif</option>

                                 @endforeach

                            </select>
                            @error('size_id') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                        </div>

                    </div>
 
                    <div class="col-lg-4 mb-3" >
                            <!-- style="display: none;" -->
                        <div>

                            <label>Color</label>

                            <select class="form-control @error('color_id') is-invalid @enderror" name="color_id" id="color_id">

                                <option value="">None</option>

                                 @foreach($color as $log)

                                  <option value="{{$log->id}}" {{ old('color_id') == $log->id ? 'selected' : '' }}>{{$log->name}} @if($log->name_ar!='')- {{$log->name_ar}}@endif</option>

                                 @endforeach

                            </select>
                            @error('color_id') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                        </div>

                    </div>

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label>Price <span class="text-danger">*</span></label>

                            <input class="form-control @error('price') is-invalid @enderror" type="text" name="price" id="price" value="{{ old('price') }}" required>
                            @error('price') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                        </div>

                    </div>

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label for="formFileSm" class="form-label">Image File <span class="text-danger">*</span></label>

                            <input class="form-control @error('imgfile') is-invalid @enderror" name="imgfile" id="imgfile" type="file" required>

                            <input type="hidden" value="{{$id}}" name="product_id">
                            @error('imgfile') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                        </div>

                    </div>  

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label for="formFileSm" class="form-label">Image file 2<small class="text-muted ms-1">(File size should be 140x175)</small></label>

                            <input class="form-control @error('imgfile2') is-invalid @enderror" name="imgfile2" id="imgfile2" type="file">
                            @error('imgfile2') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                        </div>

                    </div>  

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label for="formFileSm" class="form-label">Image file 3<small class="text-muted ms-1">(File size should be 140x175)</small></label>

                            <input class="form-control @error('imgfile3') is-invalid @enderror" name="imgfile3" id="imgfile3" type="file">
                            @error('imgfile3') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                        </div>

                    </div>  

                                

                    <div class="col-lg-4 mt-3 mb-3">

                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2">Create</button>

                        <a href="{{url('/productvariants')}}/{{$id}}" class="btn btn-secondary waves-effect waves-light resetbtn">Cancel</a>

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