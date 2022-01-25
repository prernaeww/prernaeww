<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ABCTOGO | Forgot Password</title>
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

<style type="text/css">
	#verify-modal input[type=number] {
          height: 45px;
          width: 45px;
          font-size: 25px;
          text-align: center;
          border: 1px solid #000000;
     }
    #verify-modal input[type=number]::-webkit-inner-spin-button, #verify-modal input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>

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
						<h3 class="t-blue mb-5 login-title relative d-inline-block">Forgot Password</h3>
						<p class="t-grey font-16">Enter Your Registered Email Below To Receive Password Reset Instruction</p>
					</div>
					@include('website.include.flash-message')
					<form action="" method="POST" id="forgot-pw-form">
						@csrf
						<div class="mb-2">
							<label class="font-14 mb-0 w-100 t-grey">Email address/ Phone number
							<div class="form-group mb-0 border-bottom-login">
								<input type="text" name="email" placeholder="Email address/ Phone number" class="input-email sign-in-input-field" required parsley-trigger="change" data-parsley-errors-container="#email_error">
							</div>
							<div id="email_error"></div>
						</div>

						<div class="text-center pt-4">
                            <input type="submit" name="" class="bg-darkblue border-0 p-2 border-r50 text-white w-100" value="Submit">
						</div>
						
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="verify-modal" tabindex="-1" role="dialog" aria-labelledby="place-order-modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content border-r10">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body py-5 px-md-5 mx-lg-5">

            	<div class="text-center">
						<h3 class="t-blue mb-5 login-title relative d-inline-block">Authentication</h3>
						<p class="t-grey font-16">OTP Will be sent to your phone number</p>
					</div>
					<form action="" method="post" accept-charset="utf-8">
						<div class="otp-box form-group my-5 py-lg-5">
							<div id="wrapper">
								<div id="form" class="d-flex justify-content-center">
									<input name="otp_digits[]" id="codeBox1" type="number" maxlength="1" onkeyup="onKeyUpEvent(1, event)" onfocus="onFocusEvent(1)"/>
							        <input name="otp_digits[]" id="codeBox2" type="number" maxlength="1" onkeyup="onKeyUpEvent(2, event)" onfocus="onFocusEvent(2)"/>
							        <input name="otp_digits[]" id="codeBox3" type="number" maxlength="1" onkeyup="onKeyUpEvent(3, event)" onfocus="onFocusEvent(3)"/>
							        <input name="otp_digits[]" id="codeBox4" type="number" maxlength="1" onkeyup="onKeyUpEvent(4, event)" onfocus="onFocusEvent(4)"/>
							        <input name="otp_digits[]" id="codeBox5" type="number" maxlength="1" onkeyup="onKeyUpEvent(5, event)" onfocus="onFocusEvent(5)"/>
							        <input name="otp_digits[]" id="codeBox6" type="number" maxlength="1" onkeyup="onKeyUpEvent(6, event)" onfocus="onFocusEvent(6)"/>
								</div>
							</div>
						</div>
						<div class="text-center">
							<p class="mb-0 t-grey">Didn't you received any code?</p>
							<a href="javascript:void(0);" title="Resend a new code" class="blue-link" id="resend-link">Resend a new code.</a>
						</div>
					</form>

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

    });

    function getCodeBoxElement(index) {
	    return document.getElementById('codeBox' + index);
	}
    function onKeyUpEvent(index, event) {
        const eventCode = event.which || event.keyCode;
        console.log("getCodeBoxElement", getCodeBoxElement(index).value.length);
        if (getCodeBoxElement(index).value.length === 1) {
          if (index !== 6) {
            getCodeBoxElement(index+ 1).focus();
          } else {
            getCodeBoxElement(index).blur();
            // Submit code
            console.log('submit code ');
            var otp_digits = $('#codeBox1').val() + $('#codeBox2').val() + $('#codeBox3').val() + $('#codeBox4').val() + $('#codeBox5').val() + $('#codeBox6').val();
            console.log(otp_digits);
            if(match_otp == otp_digits){
            	console.log('OTP matched');

            	window.location.replace('/password/reset/'+phone_number);


            }else{
            	Notiflix.Notify.Failure('Invalid OTP');
            	return false;
            }
            
          }
        }else{
        	$('#codeBox'+index).val("");
        }
        if (eventCode === 8 && index !== 1) {
          getCodeBoxElement(index - 1).focus();
        }
    }

    function onFocusEvent(index) {
        for (item = 1; item < index; item++) {
          const currentElement = getCodeBoxElement(item);
          if (!currentElement.value) {
              currentElement.focus();
              break;
          }
        }
    }

    var phone_number = "";
    
    var match_otp = '';
    $(document).on('submit','form#forgot-pw-form',function(event){
	   	console.log("submitting");
	   	event.preventDefault();
	   	$('#loader').show();
	   	var email = $('[name="email"]').val();

	    $.ajax({
	            url: 'send_email_otp',
	            type: 'post',
	            dataType: "json",
	            data: {
	                "email": email,
	                 "_token": "{{ csrf_token() }}"
	            },
	            success: function (returnData) 
	            {
	                
	                console.log(returnData);
	                if(returnData.status == true){

	                	if (typeof(returnData.data) !== 'undefined'){
	                		$("#verify-modal").modal();
	                		phone_number = returnData.data.crypt_phone;
	                		console.log(phone_number);
	                		match_otp = returnData.data.otp;
	                	}else{
	                		Notiflix.Notify.Success(returnData.message); 
	                		$('.input-email').val('');
	                	}
	                	
	                	$('#loader').hide();
	                }else{
	                	$('#loader').hide();
	                	Notiflix.Notify.Failure(returnData.message);
	                	return false;
	                }
	                
	          
	            }
	      });

	});

	$(document).on('click','#resend-link',function(){
	   	console.log("resend");
	   	$("input[name='otp_digits[]']").val(''); 
	   	$('#loader').show();

        var phone = $('[name="phone"]').val();

	    $.ajax({
	            url: 'send_otp_mobile',
	            type: 'post',
	            dataType: "json",
	            data: {
	                "phone": phone,
	                 "_token": "{{ csrf_token() }}"
	            },
	            success: function (returnData) 
	            {
	            	$("input[name='otp_digits[]']").val(''); 
	                $('#loader').hide();
	                console.log(returnData);
	                if(returnData.status == true){
	                	Notiflix.Notify.Success(returnData.message);
	                	match_otp = returnData.data.otp;
	                }else{
	                	Notiflix.Notify.Failure(returnData.message);
	                	return false;
	                }
	                
	          
	            }
	      });

	});
</script>