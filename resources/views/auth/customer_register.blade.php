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
    <link href="{{ URL::asset('assets/libs/notiflix/notiflix-2.1.2.css')}}" rel="stylesheet" type="text/css" />

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

</head>
<body>

<div class="sign-in-page d-flex justify-content-center align-items-center">
		<div class="container sign-up-wrap">
			<div class="row align-items-center">
				<div class="col-lg-4 pr-xl-5">
					<div class="text-center">
						<a href="{{ route('home') }}" title="" class="login-logo mb-5 pb-xl-5"><img src="{{ URL::asset('assets/images/website/logo2.png') }}" alt="" class="img-fluid"></a>
						<h2 class="text-white mb-4 font-500 t-black-tablet">Welcome</h2>
						<p class="text-white t-black-tablet">Today, AbcToGo is the largest online marketplace for alcohol in North America. Our purpose is to be there when it matters – committed to life's moments and the people who create them.</p>
					</div>
				</div>
				<div class="col-lg-8 mt-5 mt-lg-0">
					<div class="sign-up-box p-lg-5 py-4 px-3 px-sm-4 bg-white shadow border-r10">
						<form action="{{ route('register_customer') }}" method="post" accept-charset="utf-8" id="register" enctype="multipart/form-data">
							@csrf
							<div class="row align-items-center">
								<div class="col-md-6 pr-xl-5">
									<div class="text-left">
										<h3 class="t-blue mb-5 login-title relative d-inline-block">Sign Up</h3>
									</div>
									@include('website.include.flash-message')
									<div class="create-account d-flex align-items-center justify-content-between">
										<div class="create-account-detail">
											<h4 class="t-black">Create Your</h4>
											<h4 class="font-400">Account</h4>
										</div>
										<div class="create-account-image">
											<label for="FileInput">
												<img src="{{ URL::asset('assets/images/website/user-default.jpg') }}" alt="" id="profile_preview" style="height:120px;width:120px;object-fit: contain;">
											</lable>
											<input type="file" name="profile_picture" id="profile" onchange="readURL(this);" data-parsley-max-file-size="5" data-parsley-filemimetypes="image/jpeg, image/png" accept="image/*" data-parsley-file-mime-types-message="Only allowed jpeg & png files" style="cursor: pointer;  display: none">
										</div>
									</div>
									<ul class="sign-up-as d-flex align-items-center flex-wrap mt-5">
										<li class="mr-4 active user_type" data-id="0"><a href="#" title="Retailer">Retailer</a></li>
										<li class="user_type" data-id="1"><a href="#" title="Business">Business</a></li>
									</ul>
								</div>

								<input type="hidden" name="user_type" value="0" id="user_type">

								<div class="col-md-6 border-left-mobile-0 border-left pl-xl-5 mt-5 mt-md-0">
									<div class="mb-3" id="business_name_div" style="display: none;">
										<div class="form-group mb-0 border-bottom-login">
											<input type="text" name="business_name" placeholder="Business Name" class="business sign-in-input-field" data-parsley-errors-container="#business_name_error">
										</div>
										<div id="business_name_error"></div>
									</div>

									<div class="mb-3">
										<div class="form-group mb-0 border-bottom-login">
											<input type="text" name="first_name" placeholder="First Name" class="last-name sign-in-input-field" required data-parsley-errors-container="#first_name_error">
										</div>
										<div id="first_name_error"></div>
									</div>

									<div class="mb-3">
										<div class="form-group mb-0 border-bottom-login">
											<input type="text" name="last_name" placeholder="Last Name" class="last-name sign-in-input-field" required data-parsley-errors-container="#last_name_error">
										</div>
										<div id="last_name_error"></div>
									</div>

									<div class="mb-3">
										<div class="form-group mb-0 border-bottom-login">
											<input type="email" name="email" placeholder="Email Address" class="input-email sign-in-input-field" required data-parsley-errors-container="#email_error">
										</div>
										<div id="email_error"></div>
									</div>

									<div class="mb-3">
										<div class="form-group mb-0 border-bottom-login">
										<!-- 	<input type="number" name="phone" placeholder="Phone Number" class="input-call sign-in-input-field" required data-parsley-errors-container="#phone_error"> -->

											<input type="text" name="phone" parsley-trigger="change" placeholder="1(XXX)XXX-XXXX" data-mask="1(999)999-9999"   class="input-call sign-in-input-field number" data-parsley-maxlength="14"  data-parsley-minlength="14" data-parsley-minlength-message="This value must be at least 14 characters" data-parsley-maxlength="14" data-parsley-maxlength-message="This value must be at least 14 characters" data-parsley-errors-container="#phone_error" required>
										</div>
										<div id="phone_error"></div>
									</div>

									<div class="form-group mb-4">
										<div class="border-bottom-login">
											<label class="font-14 mb-0 w-100 t-grey">Password
												<div class="input-password d-flex">
													<input type="password" name="password" value="" placeholder="Password" class="sign-in-input-field input-password" required data-parsley-errors-container="#password_error" id="password" min="8">
													<button type="button" class="hide-password toggle-password"><i class="far fa-eye-slash fa-2x text-muted"></i></i></button>
												</div>
											</label>	
										</div>
										<div id="password_error"></div>
									</div>
									<div class="form-group mb-4">
										<div class="border-bottom-login">
											<label class="font-14 mb-0 w-100 t-grey">Confirm Password	
												<div class="input-password d-flex">
													<input type="password" name="c_password" value="" placeholder="Confirm Password" class="sign-in-input-field input-password" data-parsley-errors-container="#c_password_error" required data-parsley-equalto="#password">
													<button type="button" class="hide-password toggle-password"><i class="far fa-eye-slash fa-2x text-muted"></i></i></button>
												</div>
											</label>
										</div>
										<div id="c_password_error"></div>
									</div>
									
									<div class="form-group mb-1 text-center">
										<label class="font-14 t-black mb-0 d-flex align-items-center justify-content-center"><input type="checkbox" name="terms_condition" value="1" class="mr-2" required>
											Agree &nbsp;<a target="_blank" href="{{url('term-of-service')}}" class="t-black"><u>terms of service</u></a>
										</label>
									</div>
									<div class="text-center py-4">
										<input type="submit" name="" class="bg-darkblue border-0 p-2 border-r50 text-white w-100" value="Sign Up">
									</div>
									<p class="text-center mb-0 font-16 font-14-mobile t-grey">Already Have an Account?
										<a href="{{url('login')}}" class="ml-1 ml-md-3 font-18 blue-link font-700">Sign In</a>
									</p>
								</div>
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
					<form action="" method="get" accept-charset="utf-8">
						<div class="otp-box form-group my-5 py-lg-5">
							<div id="wrapper">
								<div id="form" class="d-flex justify-content-center">
									<input name="otp_digits[]" id="codeBox1" type="number" maxlength="1" onkeyup="onKeyUpEvent(1, event)" onfocus="onFocusEvent(1)" data-parsley-type="digits"/>
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
<script src="{{ URL::asset('assets/libs/jquery-mask-plugin/jquery-mask-plugin.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/notiflix/notiflix-2.1.2.js')}}"></script>
<script src="{{ URL::asset('assets/js/website/bootstrap.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/website/popper.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/website/slick.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/website/bootstrap-slider.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/lottiefiles/lottie-player.js')}}"></script>
<!-- <script src="{{ URL::asset('assets/libs/jquery-mask-plugin/jquery-mask-plugin.min.js')}}"></script> -->

</body>
</html>

<div id="loader" style="width: 100%; height: 100%; position: fixed;display: block;top: 0;left: 0;text-align: center;opacity: 1;background-color: #ffffff73;z-index: 111111; display: none;">
    <lottie-player src="{{ URL::asset('assets/libs/lottiefiles/lf20_i2iugofy.json')}}" background="transparent" speed="1" style="width: 250px; height: 250px; position: absolute;top: 36%;left: 46%;z-index: 1111;" autoplay loop></lottie-player>
</div>    
<script type="text/javascript">
	        //========================NUMBER==========================================
$(document).ready(function() {
    //called when key is pressed in textbox
    $(".number").keypress(function(e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57))

            return false;
        return true;

    });
});
//========================NUMBER==========================================
//========================NUMBER ( . )==========================================
$(document).ready(function() {
    //called when key is pressed in textbox
    $(".number").keypress(function(evt) {
        //if the letter is not digit then display error and don't type anything
        var iKeyCode = (evt.which) ? evt.which : evt.keyCode
        if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
            return false;
        return true;
    });
});
//========================NUMBER ( . )========================================== 
$("#profile_preview").click(function () {
    $("#profile").trigger('click');
});

$('.toggle-password').click(function(){
    $(this).children().toggleClass('fa-eye-slash fa-eye');
    let input = $(this).prev();
    input.attr('type', input.attr('type') === 'password' ? 'text' : 'password');
});

function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			$('#profile_preview').attr('src', e.target.result);
		};
		reader.readAsDataURL(input.files[0]);
	}
}

	function getCodeBoxElement(index) {
	    return document.getElementById('codeBox' + index);
	}
    function onKeyUpEvent(index, event) {
        const eventCode = event.which || event.keyCode;
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
				// {
		        //         "first_name": $("input[name=first_name]").val(),
		        //         "last_name": $("input[name=last_name]").val(),
		        //         "email": $("input[name=email]").val(),
		        //         "phone": $("input[name=phone]").val(),
		        //         "password": $("input[name=password]").val(),
		        //         "user_type": $("input[name=user_type]").val(),
		        //         "business_name": $("input[name=business_name]").val(),
		        //         "profile_picture": file_data,
		        //         // "profile_picture": $("input[name=profile_picture]").val(),
		        //          "_token": "{{ csrf_token() }}"
		        //     }
				var fd = new FormData();
                var files = $('#profile')[0].files[0];
				var token = "{{ csrf_token() }}";
                fd.append('profile_picture', files);
                fd.append('first_name', $("input[name=first_name]").val());
                fd.append('last_name', $("input[name=last_name]").val());
                fd.append('email', $("input[name=email]").val());
                fd.append('phone', $("input[name=phone]").val());
                fd.append('user_type', $("input[name=user_type]").val());
                fd.append('password', $("input[name=password]").val());
                fd.append('business_name', $("input[name=business_name]").val());
                fd.append('_token', "{{ csrf_token() }}");
            	$.ajax({
		            url: 'register_customer',
		            type: 'post',
					processData:false,
					contentType: false,
		            data:fd,
		            success: function (returnData) 
		            {
		                $('#verify-modal').modal('hide');
		                $("input[name='otp_digits[]']").val(''); 
		                $('#register').trigger("reset");
						var returnData = JSON.parse(returnData);
		                console.log(returnData);
		                if(returnData.status == true){
		                	Notiflix.Notify.Success(returnData.message);
		                	$('#loader').hide();
		                }else{
		                	$('#loader').hide();
		                	Notiflix.Notify.Failure(returnData.message);
		                	return false;
		                }
		                
		          
		            }
		      });


            }else{
            	Notiflix.Notify.Failure('Invalid OTP');
            	return false;
            }
            
          }
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

    var match_otp = '';
    $(document).on('submit','form#register',function(event){
	   	console.log("submitting");
	   	event.preventDefault();
	   	$('#loader').show();
	   	var email = $('[name="email"]').val();
        var phone = $('[name="phone"]').val();

	    $.ajax({
	            url: 'send_otp_mobile',
	            type: 'post',
	            dataType: "json",
	            data: {
	                "email": email,
	                "phone": phone,
	                 "_token": "{{ csrf_token() }}"
	            },
	            success: function (returnData) 
	            {
	                
	                console.log(returnData);
	                if(returnData.status == true){
	                	Notiflix.Notify.Success(returnData.message);
	                	match_otp = returnData.data.otp;
	                	$("#verify-modal").modal();
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
	                // console.log(returnData);
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

    $(document).ready(function() {

    		// $(".input-call").mask("(999)999-9999");
    		//$("#verify-modal").modal();
            $('form').parsley();
            $(".alert").fadeTo(2000, 500).slideUp(500, function(){
                $(".alert").slideUp(500);
            });

   //          $('.toggle-password').click(function(){
			//     $(this).children().toggleClass('fa fa-eye-slash fa fa-eye');
			//     let input = $(this).prev();
			//     input.attr('type', input.attr('type') === 'password' ? 'text' : 'password');
			// });

			$("li").click(function() {
				var user_type = $(this).attr('data-id');
				$('#user_type').val(user_type);

				if(user_type == 1){
					$('#business_name_div').show();
					$('.business').attr('required', true);
				}else{
					$('.business').removeAttr('required');
					$('#business_name_div').hide();
				}
		      	$("li").removeClass("active");
		      	$(this).addClass("active");
		   });

    });
</script>