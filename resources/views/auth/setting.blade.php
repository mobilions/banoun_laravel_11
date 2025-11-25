@extends('layouts.master')

@section('title',$title)

@section('StyleContent')

@endsection

@section('PageContent')

<div class="row">

<div class="col-12">

    <div class="page-title-box d-sm-flex align-items-center justify-content-between">

        <h4 class="mb-sm-0 font-size-16">{{$title}}

            <a href="javascript:void(0);" class="btn btn-outline-primary waves-effect waves-light btn-sm ms-3 editbtn"><i class="mdi mdi-pencil me-1"></i>Edit {{$title}}</a>

            <a href="javascript:void(0);" class="btn btn-outline-secondary waves-effect waves-light btn-sm ms-3 cancelbtn" style="display: none;">Cancel</a>

        </h4>

        <div class="page-title-right">

            <ol class="breadcrumb m-0">

                    <li class="breadcrumb-item"><a href="javascript: void(0);">Details</a></li>

                    <li class="breadcrumb-item active">{{$title}}</li>

            </ol>

        </div>

    </div>

</div>

</div>

@if($setting)

<div class="row">

<div class="col-md-12">

    <div class="card">

        <div class="card-body">

        <div class="row">

            <div class="col-sm-12 pt-3">

            <form  action="{{url('/settings/store')}}" method="post" enctype="multipart/form-data">

                {{csrf_field()}} 

                <div class="mb-3 row">

                    <label for="id-text" class="col-md-4 col-form-label">Company Name</label>

                    <div class="col-md-8">

                        <input class="form-control" name="company" type="text" value="{{$setting->company}}" id="id-text" readonly>

                    </div>

                </div> 

                <div class="mb-3 row">

                    <label for="id-text" class="col-md-4 col-form-label">Company Name(In Arabic)</label>

                    <div class="col-md-8">

                        <input class="form-control" name="company_ar" type="text" value="{{$setting->company_ar}}" id="id-text" readonly>

                    </div>

                </div>

                <div class="mb-3 row">

                    <label for="id-text" class="col-md-4 col-form-label">Contact Person</label>

                    <div class="col-md-8">

                        <input class="form-control" name="contact_person" type="text" value="{{$setting->contact_person}}" id="id-text" readonly>

                    </div>

                </div>

                <div class="mb-3 row">

                    <label for="id-text" class="col-md-4 col-form-label">Contact Phone</label>

                    <div class="col-md-8">

                        <input class="form-control" name="phone" type="text" value="{{$setting->phone}}" id="id-text" readonly>

                    </div>

                </div>

                <div class="mb-3 row">

                    <label for="id-text" class="col-md-4 col-form-label">Contact Email</label>

                    <div class="col-md-8">

                        <input class="form-control" name="email" type="text" value="{{$setting->email}}" id="id-text" readonly>

                    </div>

                </div>

                <div class="mb-3 row">

                    <label for="id-text" class="col-md-4 col-form-label">Support Phone</label>

                    <div class="col-md-8">

                        <input class="form-control" name="support_phone" type="text" value="{{$setting->support_phone}}" id="id-text" readonly>

                    </div>

                </div>

                <div class="mb-3 row">

                    <label for="id-text" class="col-md-4 col-form-label">Support Email</label>

                    <div class="col-md-8">

                        <input class="form-control" name="support_email" type="text" value="{{$setting->support_email}}" id="id-text" readonly>

                    </div>

                </div>

                <div class="mb-3 row">

                    <label for="id-text" class="col-md-4 col-form-label">Company Location</label>

                    <div class="col-md-8">

                        <input class="form-control" name="location" type="text" value="{{$setting->location}}" id="id-text" readonly>

                    </div>

                </div>

                <div class="mb-3 row">

                    <label for="id-text" class="col-md-4 col-form-label">Description</label>

                    <div class="col-md-8">

                        <textarea class="form-control" name="description" type="text" id="id-text" readonly>{{$setting->description}}</textarea>

                    </div>

                </div>

                <div class="mb-3 row">

                    <label for="id-text" class="col-md-4 col-form-label">Description(In Arabic)</label>

                    <div class="col-md-8">

                        <textarea class="form-control" name="description_ar" type="text" id="id-text" readonly>{{$setting->description_ar}}</textarea>

                    </div>

                </div>

                <div class="mb-3 row">

                    <label for="id-text" class="col-md-4 col-form-label">Website Header</label>

                    <div class="col-md-8">

                        <textarea class="form-control" name="header" type="text" id="id-text" readonly>{{$setting->header}}</textarea>

                    </div>

                </div>

                <div class="mb-3 row">

                    <label for="id-text" class="col-md-4 col-form-label">Website Header(In Arabic)</label>

                    <div class="col-md-8">

                        <textarea class="form-control" name="header_ar" type="text" id="id-text" readonly>{{$setting->header_ar}}</textarea>

                    </div>

                </div>

                <div class="mb-3 row">

                    <label for="id-text" class="col-md-4 col-form-label">Company Logo</label>

                    <div class="col-md-8">

                        <input class="form-control" name="imgfile" id="imgfile" type="file">

                    </div>

                </div>

                <div class="mb-3 row">

                    <label for="id-text" class="col-md-4 col-form-label">Facebook</label>

                    <div class="col-md-8">

                        <input class="form-control" name="facebook" type="text" value="{{$setting->facebook}}" id="id-text" readonly>

                    </div>

                </div> 

                <div class="mb-3 row">

                    <label for="id-text" class="col-md-4 col-form-label">Twitter</label>

                    <div class="col-md-8">

                        <input class="form-control" name="twitter" type="text" value="{{$setting->twitter}}" id="id-text" readonly>

                    </div>

                </div> 

                <div class="mb-3 row">

                    <label for="id-text" class="col-md-4 col-form-label">Instagram</label>

                    <div class="col-md-8">

                        <input class="form-control" name="instagram" type="text" value="{{$setting->instagram}}" id="id-text" readonly>

                    </div>

                </div> 

                <div class="mb-3 row">

                    <label for="id-text" class="col-md-4 col-form-label">Whatsapp</label>

                    <div class="col-md-8">

                        <input class="form-control" name="whatsapp" type="text" value="{{$setting->whatsapp}}" id="id-text" readonly>

                    </div>

                </div> 

                <div class="mb-3 row">

                    <label for="id-text" class="col-md-4 col-form-label">Google</label>

                    <div class="col-md-8">

                        <input class="form-control" name="google" type="text" value="{{$setting->google}}" id="id-text" readonly>

                    </div>

                </div>     

                <div class="mb-3 row">

                    <label for="id-text" class="col-md-4 col-form-label">Gift Wrap Price</label>

                    <div class="col-md-8">

                        <input class="form-control" name="giftwrap_price" type="text" value="{{$setting->giftwrap_price}}" id="id-text" readonly>

                    </div>

                </div>              

                <div class="mb-3 row text-center">

                    <button type="submit" class="btn btn-primary waves-effect waves-light me-2 cancelbtn" style="display:none;">Update Changes</button>

                </div>

            </form>

            </div>

        </div>

        </div>

    </div>

</div> 

</div>

@endif

@endsection

@section('ScriptContent')

<script>

$('.editbtn').click(function() {

    $( ".form-control" ).prop( "readonly", false );

    $(".editbtn").hide();

    $(".cancelbtn").show();

});

$('.cancelbtn').click(function() {

    $( ".form-control" ).prop( "readonly", true );

    $(".editbtn").show();

    $(".cancelbtn").hide();

});

</script>

@stop