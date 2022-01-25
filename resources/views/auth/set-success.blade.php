@extends('admin.layouts.master-without-nav')
@section('body')
<!-- <body class="authentication-bg"> -->
<body>
    @endsection
    @section('content')
    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-pattern">
                    <div class="card-block">
                        <div class="ex-page-content text-center">
                            <img src="{{asset('images/logo.png')}}" height="100" alt="">
                            <h1 class="mt-3 text-success" >Congratulations!</h1>
                            <h4 class="mb-5" style="color: #535a62fc;">Your email address has been successfully verified. If you have any questions about our apps, please <a href="mailto:admin@admin.com" style="color: #535a62fc;"><u>reach out to our team</u></a>. </h4>
                        </div>

                    </div>
                    </div>
                    <!-- end container -->
                </div>
                <!-- end page -->
                <footer class="footer footer-alt">
                    {{date('Y')}} &copy; AbcToGo 
                </footer>
                @endsection
