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

                        @if($setting->imageurl)
                            <div class="mb-2">
                                <img src="{{$setting->imageurl}}" alt="Current Logo" style="max-width: 200px; max-height: 100px;" class="img-thumbnail">
                            </div>
                        @endif
                        <input class="form-control" name="imgfile" id="imgfile" type="file">
                        <input type="hidden" name="imgfile_val" value="{{$setting->imageurl ?? ''}}">
                        <small class="form-text text-muted">Leave empty to keep current logo</small>

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

<!-- Mail Configuration Section -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Mail Configuration</h5>
            </div>
            <div class="card-body">
                <form action="{{url('/settings/store')}}" method="post">
                    {{csrf_field()}}
                    <input type="hidden" name="update_mail_config" value="1">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="MAIL_MAILER" class="form-label">Mail Driver <span class="text-danger">*</span></label>
                                <select class="form-control mail-config-field" name="MAIL_MAILER" id="MAIL_MAILER" readonly>
                                    <option value="smtp" {{ config('mail.default', 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="sendmail" {{ config('mail.default') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                    <option value="mailgun" {{ config('mail.default') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                    <option value="ses" {{ config('mail.default') == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="MAIL_HOST" class="form-label">Mail Host <span class="text-danger">*</span></label>
                                <input type="text" class="form-control mail-config-field" name="MAIL_HOST" id="MAIL_HOST" value="{{ config('mail.mailers.smtp.host', '') }}" placeholder="smtp.mailtrap.io" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="MAIL_PORT" class="form-label">Mail Port <span class="text-danger">*</span></label>
                                <input type="number" class="form-control mail-config-field" name="MAIL_PORT" id="MAIL_PORT" value="{{ config('mail.mailers.smtp.port', '587') }}" placeholder="587" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="MAIL_ENCRYPTION" class="form-label">Encryption <span class="text-danger">*</span></label>
                                <select class="form-control mail-config-field" name="MAIL_ENCRYPTION" id="MAIL_ENCRYPTION" readonly>
                                    @php
                                        $encryption = config('mail.mailers.smtp.encryption') ?? env('MAIL_ENCRYPTION', 'tls');
                                    @endphp
                                    <option value="tls" {{ $encryption == 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="ssl" {{ $encryption == 'ssl' ? 'selected' : '' }}>SSL</option>
                                    <option value="" {{ empty($encryption) ? 'selected' : '' }}>None</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="MAIL_USERNAME" class="form-label">Mail Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control mail-config-field" name="MAIL_USERNAME" id="MAIL_USERNAME" value="{{ config('mail.mailers.smtp.username', '') }}" placeholder="your-username" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="MAIL_PASSWORD" class="form-label">Mail Password</label>
                                <input type="password" class="form-control mail-config-field" name="MAIL_PASSWORD" id="MAIL_PASSWORD" value="" placeholder="Leave blank to keep current password" readonly>
                                <small class="form-text text-muted">Leave blank if you don't want to change the password</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="MAIL_FROM_ADDRESS" class="form-label">From Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control mail-config-field" name="MAIL_FROM_ADDRESS" id="MAIL_FROM_ADDRESS" value="{{ config('mail.from.address', '') }}" placeholder="noreply@example.com" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="MAIL_FROM_NAME" class="form-label">From Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control mail-config-field" name="MAIL_FROM_NAME" id="MAIL_FROM_NAME" value="{{ config('mail.from.name', '') }}" placeholder="{{ $setting->company ?? 'Your App Name' }}" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mail-update-btn" style="display:none;">Update Mail Configuration</button>
                        </div>
                    </div>
                </form>
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
    $( ".mail-config-field" ).prop( "readonly", false );

    $(".editbtn").hide();

    $(".cancelbtn").show();
    $(".mail-update-btn").show();

});

$('.cancelbtn').click(function() {

    $( ".form-control" ).prop( "readonly", true );
    $( ".mail-config-field" ).prop( "readonly", true );

    $(".editbtn").show();

    $(".cancelbtn").hide();
    $(".mail-update-btn").hide();
    
    // Reset mail password field
    $("#MAIL_PASSWORD").val('');

});

</script>

@stop