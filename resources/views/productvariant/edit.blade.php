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

        <h4 class="mb-sm-0 font-size-16">Edit {{$title}} - {{App\Models\Product::FindName($log->product_id)}} <a href="{{url('/productvariants')}}/{{$log->product_id}}" class="btn btn-primary waves-effect waves-light btn-sm ms-3"><i class="fa fa-eye me-1"></i>View {{$title}}</a></h4>

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

        <div class="row">

            <div class="col-sm-12 pt-3">

                <form  action="{{url('/productvariants/update')}}" method="post" enctype="multipart/form-data">

                {{csrf_field()}}  

                <div class="row">

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label>Size<span class="text-danger">*</span></label>

                            <select class="form-control @error('size_id') is-invalid @enderror" name="size_id" id="size_id" required>

                                <option value="">Select</option>

                                 @foreach($size as $logs)

                                  <option value="{{$logs->id}}" {{ old('size_id', $log->size_id) == $logs->id ? 'selected' : '' }}>{{$logs->name}} @if($logs->name_ar!='')- {{$logs->name_ar}}@endif</option>

                                 @endforeach

                            </select>
                            @error('size_id') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                        </div>

                    </div>

                    <div class="col-lg-4 mb-3" style="display: none;">

                        <div>

                            <label>Color</label>

                            <select class="form-control @error('color_id') is-invalid @enderror" name="color_id" id="color_id">

                                <option value="">None</option>

                                 @foreach($color as $logc)

                                  <option value="{{$logc->id}}" {{ old('color_id', $log->color_id) == $logc->id ? 'selected' : '' }}>{{$logc->name}} @if($logc->name_ar!='')- {{$logc->name_ar}}@endif</option>

                                 @endforeach

                            </select>
                            @error('color_id') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                        </div>

                    </div>

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label>Price <span class="text-danger">*</span></label>

                            <input value="{{ old('price', $log->price) }}" class="form-control @error('price') is-invalid @enderror" type="text" name="price" id="price" required>

                            <input class="form-control" value="{{$log->imageurl}}"  name="imgfile_val" id="imgfile_val" type="hidden">

                            <input class="form-control" value="{{$log->imageurl2}}"  name="imgfile_val2" id="imgfile_val2" type="hidden">

                            <input class="form-control" value="{{$log->imageurl3}}"  name="imgfile_val3" id="imgfile_val3" type="hidden">
                            @error('price') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                        </div>

                    </div>

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label for="formFileSm" class="form-label">Image File</label>

                            <input class="form-control @error('imgfile') is-invalid @enderror" name="imgfile" id="imgfile" type="file">

                            <input class="form-control" value="{{$log->id}}"  name="editid" id="editid" type="hidden">

                            <input type="hidden" value="{{$log->product_id}}" name="product_id">
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

                    @php
                        $img1 = $log->imageurl ? (Str::startsWith($log->imageurl, ['http://', 'https://', '//']) ? $log->imageurl : asset($log->imageurl)) : null;
                        $img2 = $log->imageurl2 ? (Str::startsWith($log->imageurl2, ['http://', 'https://', '//']) ? $log->imageurl2 : asset($log->imageurl2)) : null;
                        $img3 = $log->imageurl3 ? (Str::startsWith($log->imageurl3, ['http://', 'https://', '//']) ? $log->imageurl3 : asset($log->imageurl3)) : null;
                    @endphp

                    <div class="col-lg-2 mt-3 mb-3">
                       @if($img1)
                           <img src="{{$img1}}" width="80" height="80" style="object-fit: cover; border: 1px solid #ddd; border-radius: 4px;" alt="{{$log->name}}" title="{{$log->name}}"> 
                       @else
                           <div style="width: 80px; height: 80px; border: 1px solid #ddd; border-radius: 4px; display: flex; align-items: center; justify-content: center; background: #f5f5f5;">
                               <span class="text-muted small">No image</span>
                           </div>
                       @endif
                    </div>   

                    <div class="col-lg-2 mt-3 mb-3">
                       @if($img2)
                           <img src="{{$img2}}" width="80" height="80" style="object-fit: cover; border: 1px solid #ddd; border-radius: 4px;" alt="{{$log->name}}" title="{{$log->name}}"> 
                       @else
                           <div style="width: 80px; height: 80px; border: 1px solid #ddd; border-radius: 4px; display: flex; align-items: center; justify-content: center; background: #f5f5f5;">
                               <span class="text-muted small">No image</span>
                           </div>
                       @endif
                    </div>           

                    <div class="col-lg-2 mt-3 mb-3">
                       @if($img3)
                           <img src="{{$img3}}" width="80" height="80" style="object-fit: cover; border: 1px solid #ddd; border-radius: 4px;" alt="{{$log->name}}" title="{{$log->name}}"> 
                       @else
                           <div style="width: 80px; height: 80px; border: 1px solid #ddd; border-radius: 4px; display: flex; align-items: center; justify-content: center; background: #f5f5f5;">
                               <span class="text-muted small">No image</span>
                           </div>
                       @endif
                    </div>   

                    <div class="col-lg-4 mt-3 mb-3">

                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2">Update</button>

                        <a href="{{url('/productvariants')}}/{{$log->product_id}}" class="btn btn-secondary waves-effect waves-light resetbtn">Cancel</a>

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