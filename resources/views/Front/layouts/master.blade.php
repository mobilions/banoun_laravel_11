
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title') | Lokal</title>
<link rel="icon" href="{{asset('/lokal')}}/images/favicon.png" sizes="32x32" type="image/png">
<link rel="stylesheet" href="{{asset('/lokal')}}/css/bootstrap.css">
<link rel="stylesheet" href="{{asset('/lokal')}}/css/style.css">
<link rel="stylesheet" href="{{asset('/lokal')}}/css/owl.carousel.min.css">
<link rel="stylesheet" href="{{asset('/lokal')}}/css/owl.theme.default.min.css">
@yield('StyleContent')
</head>

<body>

<!-- BEGIN: Preloader Section -->
<div class="preloader">
<div id="loader">
    <span></span>
    <span></span>
</div>
</div>
<!-- END Preloader Section -->

@include('Front.layouts.header')

@yield('PageContent')

@include('Front.layouts.footer')


<!-- Scripts -->
<script src="{{asset('/lokal')}}/js/jquery.js"></script>
<script src="{{asset('/lokal')}}/js/popper.min.js"></script>
<script src="{{asset('/lokal')}}/js/bootstrap.min.js"></script>
<script src="{{asset('/lokal')}}/js/owl.carousel.min.js"></script>
<!-- scripts -->
<script src="{{asset('/lokal')}}/js/scripts.js"></script>
@yield('ScriptContent')
</body>

</html>