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

                <form  action="{{url('/variantsub/update')}}" method="post" enctype="multipart/form-data">

                {{csrf_field()}}  

                <div class="row">

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label>Variant <span class="text-danger">*</span></label>

                            <select class="form-control @error('variant_id') is-invalid @enderror" name="variant_id" id="variant_id" required onchange="checkcolor()">

                                <option value="">Select</option>

                                 @foreach($variants as $list)

                                  <option @if(old('variant_id', $log->variant_id)==$list->id) selected="" @endif value="{{$list->id}}" data-variant-name="{{strtolower($list->name)}}">{{$list->name}}@if($list->name_ar!='')- {{$list->name_ar}}@endif</option>

                                 @endforeach

                            </select>
                            @error('variant_id') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                        </div>

                    </div>

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label>Name <span class="text-danger">*</span></label>

                            <input class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $log->name) }}" type="text" name="name" id="name" required>

                            <input class="form-control" value="{{$log->id}}"  name="editid" id="editid" type="hidden">
                            @error('name') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                        </div>

                    </div>

                    <div class="col-lg-4 mb-3">

                        <div>

                            <label>Name in arabic</label>

                            <input class="form-control @error('name_ar') is-invalid @enderror" value="{{ old('name_ar', $log->name_ar) }}" type="text" name="name_ar" id="name_ar">
                            @error('name_ar') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                        </div>

                    </div>     

                    <div class="col-lg-4 mb-3" id="color_show" style="display: none;">

                        <div>

                            <label>Color</label>

                           <input type="text" value="{{ old('color_val', $log->color_val) ?: '#50a5f1' }}" name="color_val" class="form-control @error('color_val') is-invalid @enderror" id="colorpicker-default">
                           @error('color_val') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                        </div>

                    </div>    

                    <div class="col-lg-4 mb-3" id="size_show" style="display: none;">

                        <div>

                            <label id="size_label">Value/Age</label>
                            <small class="text-muted d-block mb-1" id="size_hint">Enter value (e.g., 8GB, 16GB for Memory or 0.1, 3-6 months for Size)</small>

                           <input type="text" value="{{ old('age', $log->color_val) }}" name="age" class="form-control @error('age') is-invalid @enderror" id="age" placeholder="Enter value">
                           @error('age') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror

                        </div>

                    </div>              

                    <div class="col-lg-4 mt-3">

                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Update</button>

                        <a href="{{url('/variantsub')}}" class="btn btn-secondary waves-effect waves-light resetbtn mt-2">Cancel</a>

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
     var selectedOption = $('#variant_id option:selected');
     var variantName = selectedOption.data('variant-name') || '';
     var variantDisplayName = selectedOption.text().split(' -')[0].trim(); // Get display name
     
     // Check if variant name is "color" (case-insensitive)
     if(variantName === 'color'){
        $('#color_show').show();
        $('#size_show').hide();
     }
     else if(variant_id){
        // For all other variants (Size, Memory, etc.), show the age/value field
        $('#color_show').hide();
        $('#size_show').show();
        // Update label dynamically based on variant type
        if(variantName === 'size'){
            $('#size_label').text('Age/Size');
            $('#size_hint').text('If Less than One Year Mention 0.1 (e.g., 0.1, 3-6 months)');
        } else {
            $('#size_label').text(variantDisplayName + ' Value');
            $('#size_hint').text('Enter the value (e.g., 8GB, 16GB for Memory)');
        }
    }
     else{
        $('#color_show').hide();
        $('#size_show').hide();
     }
 }
 
 $(document).ready(function() {
     checkcolor();
 });
</script>

<script src="{{asset('/assets')}}/libs/select2/js/select2.min.js"></script>        

<script src="{{asset('/assets')}}/libs/spectrum-colorpicker2/spectrum.min.js"></script>

<!-- form advanced init -->

<script src="{{asset('/assets')}}/js/pages/form-advanced.init.js"></script>

@stop