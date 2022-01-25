<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ABCTOGO | Reset Password</title>
	<link rel="shortcut icon" href="{{asset('images/favicon.ico')}}">
	<link rel="stylesheet" href="{{ URL::asset('assets/css/website/bootstrap.min.css') }}">
	<link href="{{ URL::asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('assets/css/website/bootstrap-slider.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/website/style.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/website/responsive.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/website/slick.css') }}">
    <link href="{{ URL::asset('assets/libs/notiflix/notiflix-2.1.2.css')}}" rel="stylesheet" type="text/css" />
</head>
<body>

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
						<h3 class="t-blue mb-5 login-title relative d-inline-block">Reset Password</h3>
					</div>
					@include('website.include.flash-message')
					<form action="{{ route('password/update') }}" method="POST" id="forgot-pw-form">
						@csrf
						<input type="hidden" name="phone" value="{{ $phone }}">
						<div class="form-group mb-4">
							<div class="border-bottom-login">
								<label class="font-14 mb-0 w-100 t-grey">Password
									<div class="input-password d-flex">
										<input type="password" name="password" value="" placeholder="Password" class="sign-in-input-field input-password" required data-parsley-errors-container="#password_error" id="password" min="8">
										<button type="button" class="hide-password toggle-password"><i class="far fa-eye-slash fa-2x text-muted"></i></button>
									</div>
								</label>
							</div>
							<div id="password_error"></div>
						</div>
						<div class="form-group mb-4">
							<div class="border-bottom-login">
								<label class="font-14 mb-0 w-100 t-grey">Confirm New Password	
									<div class="input-password d-flex">
										<input type="password" name="c_password" value="" placeholder="Confirm New Password" class="sign-in-input-field input-password" data-parsley-errors-container="#c_password_error" required data-parsley-equalto="#password">
										<button type="button" class="hide-password toggle-password"><i class="far fa-eye-slash fa-2x text-muted"></i></button>
									</div>
								</label>
							</div>
							<div id="c_password_error"></div>
						</div>

						<div class="text-center pt-4">
                            <input type="submit" name="" class="bg-darkblue border-0 p-2 border-r50 text-white w-100" value="Save">
						</div>
						
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="{{ URL::asset('assets/js/website/jquery.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/notiflix/notiflix-2.1.2.js')}}"></script>
<script src="{{ URL::asset('assets/js/website/bootstrap.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/website/popper.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/website/slick.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/website/bootstrap-slider.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/lottiefiles/lottie-player.js')}}"></script>

</body>
</html>

<div id="loader" style="width: 100%; height: 100%; position: fixed;display: block;top: 0;left: 0;text-align: center;opacity: 1;background-color: #ffffff73;z-index: 111111; display: none;">
    <lottie-player src="{{ URL::asset('assets/libs/lottiefiles/lf20_i2iugofy.json')}}" background="transparent" speed="1" style="width: 250px; height: 250px; position: absolute;top: 36%;left: 46%;z-index: 1111;" autoplay loop></lottie-player>
</div>

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