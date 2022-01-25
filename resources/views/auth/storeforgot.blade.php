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

                        <div class="card-body p-4">

                            

                            <div class="text-center w-75 m-auto">

                                <a href="index">

                                    <span><img src="{{asset('images/logo.png')}}" alt="" height="100"></span>

                                </a>

                               

                                <p class="text-muted mb-4 mt-3">Enter your email address and password to access Board panel.</p>

                            </div>

                            @include('admin.include.flash-message')

                            <!-- <x-auth-session-status class="text-success" :status="session('status')" /> -->

                            <x-auth-validation-errors class="text-danger" :errors="$errors" />

                            <form action="{{ route('forgot_password') }}" method="post">

                                @csrf

                                <div class="form-group mb-3">

                                    <label for="emailaddress">Email address</label>

                                    <input class="form-control" type="email" id="email" required name="email" autofocus placeholder="Enter your email">

                                </div>                                                                

                                <div class="form-group mb-0 text-center">

                                    <button class="btn btn-primary btn-block" type="submit"> Submit </button>

                                </div>

                            </form>

                            

                            </div> <!-- end card-body -->

                        </div>

                        <!-- end card -->

                        <div class="row mt-3">

                            <div class="col-12 text-center">

                                @if (Route::has('password.request'))

                                <a class="text-white-50 ml-1" href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>

                                @endif

                                

                                </div> <!-- end col -->

                            </div>

                            <!-- end row -->

                            </div> <!-- end col -->

                        </div>

                        <!-- end row -->

                    </div>

                    <!-- end container -->

                </div>

                <!-- end page -->

                <footer class="footer footer-alt">

                    {{date('Y')}} &copy; AbcToGo

                </footer>

                @endsection
