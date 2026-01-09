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

                <form  action="{{url('/searchtag/update')}}" method="post" enctype="multipart/form-data">

                {{csrf_field()}}  

                <div class="row">

                    <div class="col-lg-6 mb-3">

                        <div>

                            <label>Title <span class="text-danger">*</span></label>

                            <input class="form-control" value="{{ old('title', $log->title) }}" type="text" name="title" id="title" required>
                            <input type="hidden" name="editid" value="{{$log->id}}">

                        </div>

                    </div>

                    <div class="col-lg-6 mb-3">

                        <div>

                            <label>Title in arabic</label>

                            <input class="form-control" value="{{ old('title_ar', $log->title_ar) }}" type="text" name="title_ar" id="title_ar">

                        </div>

                    </div>

                    <div class="col-lg-4 mt-3 mb-3">

                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2">Update</button>

                        <a href="{{url('/searchtag')}}" class="btn btn-secondary waves-effect waves-light resetbtn">Cancel</a>

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