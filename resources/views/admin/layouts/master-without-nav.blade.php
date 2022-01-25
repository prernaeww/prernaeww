<!DOCTYPE html>
@php
header('Content-Type: application/json; charset=utf-8');
@endphp
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>ABCTOGO | Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="" name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}">

        @include('admin.layouts.head')
  </head>

@yield('body')

@yield('content')

@include('admin.layouts.footer-script')    
    </body>
</html>