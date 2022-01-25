<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>ABCTOGO | BOARD</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="" name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        

        <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}">
        @include('board.layouts.head')
    </head>
    <body>

          <!-- Begin page -->
          <div id="wrapper">
      @include('board.layouts.topbar')
      @include('board.layouts.sidebar')
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
      @yield('content')
                </div> <!-- content -->
    @include('board.layouts.footer')    
            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->
    @include('board.layouts.footer-script')    
    </body>
</html>