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
                        <form  action="{{url('admin/coupon/update')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}  
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <div>
                                    <label>Coupon Code <span class="text-danger">*</span></label>
                                    <input value="{{$log->coupon_code}}" class="form-control" type="text" name="coupon_code" id="coupon_code" required="">
                                    <input class="form-control" value="{{$log->id}}"  name="editid" id="editid" type="hidden">
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3" style="display: none;">
                                <div>
                                    <label>Coupon Code in arabic</label>
                                    <input  class="form-control" type="text" name="coupon_code_ar" id="coupon_code_ar">
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <div>
                                    <label>Coupon Type <span class="text-danger">*</span></label>
                                    <select id="price_type" name="price_type" class="form-control">
                                        <option @if($log->price_type=='Percentage') selected='' @endif value="Percentage">Percentage</option>
                                        <option @if($log->price_type=='Price') selected='' @endif  value="Price">Price</option>
                                        <option @if($log->price_type=='FreeDelivery') selected='' @endif value="FreeDelivery">FreeDelivery</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <div>
                                    <label>Coupon Value <span class="text-danger">*</span></label>
                                    <input  value="{{$log->coupon_val}}" class="form-control" type="number" name="coupon_val" id="coupon_val" required="">
                                </div>
                            </div>
                            <div class="col-lg-4 mt-3">
                                <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Update</button>
                                <a href="{{url('coupon')}}" class="btn btn-secondary waves-effect waves-light resetbtn mt-2">Cancel</a>
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