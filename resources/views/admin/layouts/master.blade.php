<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ABCTOGO | ADMIN</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="" name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        

        <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}">
        @include('admin.layouts.head')
    </head>
    <body>

          <!-- Begin page -->
          <div id="wrapper">
      @include('admin.layouts.topbar')
      @include('admin.layouts.sidebar')
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
      @yield('content')
                </div> <!-- content -->
    @include('admin.layouts.footer')    
            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->
    @include('admin.layouts.footer-script')    
    </body>
</html>