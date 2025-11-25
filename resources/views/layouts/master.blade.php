<!DOCTYPE html>
<html lang="en">
@include('layouts.styles')
<?php $isvertical = true; ?>
@if($isvertical == true)
<body data-sidebar="dark">
@else
<body data-topbar="dark" data-layout="horizontal">
@endif
<div id="layout-wrapper">
@include('layouts.header')
@if($isvertical == true)
@include('layouts.leftnav')
@else
@include('layouts.topnav')
@endif
<div class="main-content">
<div class="page-content">
<div class="container-fluid">
@include('partials.alerts')
@yield('PageContent')
</div>
</div>
</div>

</div>
<div class="rightbar-overlay"></div>
@include('layouts.scripts')
</body>
</html>
