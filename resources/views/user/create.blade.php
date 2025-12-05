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
                <form  action="{{url('/profile/store')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}  
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <div>
                            <label>Name <span class="text-danger">*</span></label>
                            <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" id="name" value="{{ old('name') }}" required="">
                            @error('name') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div>
                            <label>Email (Username) <span class="text-danger">*</span></label>
                            <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" id="email" value="{{ old('email') }}" required="">
                            @error('email') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div>
                            <label>Password <span class="text-danger">*</span></label>
                            <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" id="password" required="">
                            @error('password') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                        </div>
                    </div>      
                </div>
                    <div class="col-lg-6 mt-3">
                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Create</button>
                        <a href="{{url('/users')}}" class="btn btn-secondary waves-effect waves-light resetbtn mt-2">Cancel</a>
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