<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ABCTOGO</title>
	<link rel="shortcut icon" href="{{asset('images/favicon.ico')}}">
	<link rel="stylesheet" href="{{ URL::asset('assets/css/website/bootstrap.min.css') }}">
	<link href="{{ URL::asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('assets/css/website/bootstrap-slider.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/website/style.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/website/responsive.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/website/slick.css') }}">
</head>
<body>

@php
$redirect_link = route('facebook-redirect');
$fb_client_id = '903684596946714';
$fb_login_link = 'https://graph.facebook.com/oauth/authorize?client_id='.$fb_client_id.'&redirect_uri='.$redirect_link.'&scope=email';

$g_redirect_link = "https://abctogo.com/auth/redirect";
$google_client_id = '549363359043-055loggbi0ta536g3gdcfb5s9d5oprt9.apps.googleusercontent.com';
$google_login_link = 'https://accounts.google.com/o/oauth2/auth?response_type=code&redirect_uri='.$g_redirect_link.'&client_id='.$google_client_id.'&scope=https://www.googleapis.com/auth/userinfo.profile+https://www.googleapis.com/auth/userinfo.email&access_type=online&approval_prompt=auto';

@endphp

<div class="sign-in-page d-flex justify-content-center align-items-center">
	<div class="container sign-in-wrap">
		<div class="row align-items-center">
			<div class="col-md-6 col-lg-5 pr-xl-5">
				<div class="text-center">
					<a href="{{ route('home') }}" title="" class="login-logo mb-5 pb-xl-5"><img src="{{ URL::asset('assets/images/website/logo2.png') }}" alt=""></a>
					<h2 class="text-white mb-4 font-500 t-black-mobile">Welcome</h2>
					<p class="text-white t-black-mobile">Let the drinks come to you.</p>
				</div>
			</div>
			<div class="col-md-6 col-lg-7 px-xl-5 mt-5 mt-md-0">
				<div class="sign-in-box bg-white shadow border-r10">
					<div class="text-center">
						<h3 class="t-blue mb-5 login-title relative d-inline-block">Sign In</h3>
					</div>
					@include('website.include.flash-message')
					<form action="{{ route('customer.login') }}" method="POST">
						@csrf
						<div class="mb-2">
							<label class="font-14 mb-0 w-100 t-grey">Email address/ Phone number
							<div class="form-group mb-0 border-bottom-login">
								<input type="email" name="email" placeholder="Email address/ Phone number" class="input-email sign-in-input-field" required parsley-trigger="change" data-parsley-errors-container="#email_error">
							</div>
							<div id="email_error"></div>
						</div>
						
						<div class="mb-2">
							<div class="form-group mb-0 border-bottom-login">
								<label class="font-14 mb-0 w-100 t-grey">Password
									<div class="input-password d-flex">
										<input type="password" name="password" placeholder="********" class="sign-in-input-field input-password" required parsley-trigger="change" data-parsley-errors-container="#password_error">
										<button type="button" class="hide-password toggle-password"><i class="far fa-eye-slash fa-2x text-muted"></i></i></button>
										<!-- <button type="button" class="hide-password toggle-password"><img src="{{ URL::asset('assets/images/website/hide-icon.png') }}" alt=""></button> -->
									</div>
								</label>
							</div>
							<div id="password_error"></div>
						</div>
						
						<div class="form-group mb-3 text-right">
							<a href="{{ route('customer.forgot') }}" title="" class="forgot-password">Forgot Password?</a>
						</div>
						<div class="text-center pt-4">
                            <input type="submit" name="" class="bg-darkblue border-0 p-2 border-r50 text-white w-100" value="Sign In">
						</div>
						<div class="text-center py-3">
							<p class="t-grey mb-2">Or Sign in With</p>
							<a href="https://abctogo.com/auth/redirect"><img src="{{ URL::asset('assets/images/website/google.jpg') }}" alt="" class="border-50"></a>
							<a href="{{ $fb_login_link }}" class="mx-4"><img src="{{ URL::asset('assets/images/website/facebook.jpg') }}" alt="" class="border-50"></a>
							<a href="{{ route('appleredirect') }}"><img src="{{ URL::asset('assets/images/website/apple.jpg') }}" alt="" class="border-50"></a>

						</div>
						<p class="text-center mb-0 font-16 font-14-mobile t-grey">New User? <a href="{{ route('signup') }}" class="ml-1 ml-md-3 font-18 blue-link font-700">Sign Up</a></p>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="{{ URL::asset('assets/js/website/jquery.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/website/bootstrap.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/website/popper.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/website/slick.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/website/bootstrap-slider.min.js')}}"></script>

</body>
</html>



<script type="text/javascript">
    $(document).ready(function() {
            $('form').parsley();
            $(".alert").fadeTo(2000, 500).slideUp(500, function(){
                $(".alert").slideUp(500);
            });

            $('.toggle-password').click(function(){
			    $(this).children().toggleClass('fa-eye-slash fa-eye');
			    let input = $(this).prev();
			    input.attr('type', input.attr('type') === 'password' ? 'text' : 'password');
			});


    });
</script>