<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ABCTOGO</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="" name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        

        <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}">
        @include('website.layouts.head')
    </head>
    <body class="relative" style="min-height: 100vh !important;">

        <div class="content-wrapper" style="min-height: calc(100vh - 429px);">
            @yield('content')
        </div>
    
        @include('website.layouts.footer')
        @include('website.layouts.footer-script')    
    </body>
</html>