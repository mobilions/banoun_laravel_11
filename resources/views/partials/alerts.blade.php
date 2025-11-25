@php
    $hasErrors = $errors->any();
    $statusMessage = session('status') ?? session('success');
    $errorMessage = session('error');
    $errorList = $hasErrors ? $errors->all() : [];
@endphp

@if($statusMessage)
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ $statusMessage }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
    </div>
@endif

@if($errorMessage)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $errorMessage }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
    </div>
@endif

@if($hasErrors)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>{{ __('Please fix the following issues:') }}</strong>
        <ul class="mb-0 mt-2">
            @foreach($errorList as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
    </div>
@endif

