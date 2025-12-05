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
        <h4 class="text-secondary"><span class="">Edit</span> {{$log->mail_type}} {{$title}}</h4>
        <div class="row">
            <div class="col-sm-12 pt-3">
                <form  action="{{url('emailtemplate/update')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}  
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <div>
                            <label>Subject <span class="text-danger">*</span></label>
                            <input class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $log->name) }}" type="text" name="name" id="name" required="">
                            @error('name') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                            <input type="hidden" name="editid" value="{{$log->id}}">
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div>
                            <label>Message</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" type="text" name="message" id="message">{{ old('message', $log->message) }}</textarea>
                            @error('message') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                        </div>
                    </div>        
                    <div class="col-lg-12 mb-3">
                        <div>
                            <label>Message Arabic</label>
                            <textarea class="form-control @error('message_ar') is-invalid @enderror" type="text" name="message_ar" id="message_ar">{{ old('message_ar', $log->message_ar) }}</textarea>
                            @error('message_ar') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                        </div>
                    </div>                    
                    <div class="col-lg-4 mt-3 mb-3">
                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2">Update</button>
                        <a href="{{url('emailtemplate')}}" class="btn btn-secondary waves-effect waves-light resetbtn">Cancel</a>
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
<script src="{{asset('/assets')}}/libs/tinymce/tinymce.min.js"></script>
<script>
    $(document).ready(function(){0<$("#message").length&&tinymce.init({selector:"textarea#message",height:300,plugins:["advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker","searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking","save table contextmenu directionality emoticons template paste textcolor"],toolbar:"insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",style_formats:[{title:"Bold text",inline:"b"},{title:"Red text",inline:"span",styles:{color:"#ff0000"}},{title:"Red header",block:"h1",styles:{color:"#ff0000"}},{title:"Example 1",inline:"span",classes:"example1"},{title:"Example 2",inline:"span",classes:"example2"},{title:"Table styles"},{title:"Table row 1",selector:"tr",classes:"tablerow1"}]})});

    $(document).ready(function(){0<$("#message_ar").length&&tinymce.init({selector:"textarea#message_ar",height:300,plugins:["advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker","searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking","save table contextmenu directionality emoticons template paste textcolor"],toolbar:"insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",style_formats:[{title:"Bold text",inline:"b"},{title:"Red text",inline:"span",styles:{color:"#ff0000"}},{title:"Red header",block:"h1",styles:{color:"#ff0000"}},{title:"Example 1",inline:"span",classes:"example1"},{title:"Example 2",inline:"span",classes:"example2"},{title:"Table styles"},{title:"Table row 1",selector:"tr",classes:"tablerow1"}]})});
</script>
@stop