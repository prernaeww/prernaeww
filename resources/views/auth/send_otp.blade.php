<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ABC To Go</title>
	<link rel="stylesheet" href="{{ URL::asset('assets/css/website/bootstrap.min.css') }}">
	<link href="{{ URL::asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('assets/css/website/bootstrap-slider.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/website/style.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/website/responsive.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/website/slick.css') }}">

    <style type="text/css">
    	input[type=number] {
	          height: 45px;
	          width: 45px;
	          font-size: 25px;
	          text-align: center;
	          border: 1px solid #000000;
	     }
	      input[type=number]::-webkit-inner-spin-button,
	      input[type=number]::-webkit-outer-spin-button {
	        -webkit-appearance: none;
	        margin: 0;
	      }
    </style>
</head>
<body>

<div class="authentication-page sign-in-page d-flex justify-content-center align-items-center">
	<div class="container sign-in-wrap">
		<div class="row align-items-center">
			<div class="col-md-5 col-lg-5 pr-xl-5">
				<div class="text-center">
					<a href="{{ route('home') }}" title="" class="login-logo mb-5 pb-xl-5"><img src="{{ URL::asset('assets/images/website/logo2.png') }}" alt="" class="img-fluid"></a>
					<h2 class="text-white mb-4 font-500 t-black-tablet">Welcome</h2>
					<p class="text-white t-black-tablet">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore.</p>
				</div>
			</div>
			<div class="col-md-7 col-lg-7 px-xl-5 mt-5 mt-md-0">
				<div class="sign-in-box bg-white shadow border-r10">
					<div class="text-center">
						<h3 class="t-blue mb-5 login-title relative d-inline-block">Authentication</h3>
						<p class="t-grey font-16">OTP Will be sent to your phone number</p>
					</div>
					<form action="" method="get" accept-charset="utf-8">
						<div class="otp-box form-group my-5 py-lg-5">
							<div id="wrapper">
								<div id="form" class="d-flex justify-content-center">
									<input id="codeBox1" type="number" maxlength="1" onkeyup="onKeyUpEvent(1, event)" onfocus="onFocusEvent(1)"/>
							        <input id="codeBox2" type="number" maxlength="1" onkeyup="onKeyUpEvent(2, event)" onfocus="onFocusEvent(2)"/>
							        <input id="codeBox3" type="number" maxlength="1" onkeyup="onKeyUpEvent(3, event)" onfocus="onFocusEvent(3)"/>
							        <input id="codeBox4" type="number" maxlength="1" onkeyup="onKeyUpEvent(4, event)" onfocus="onFocusEvent(4)"/>
							        <input id="codeBox5" type="number" maxlength="1" onkeyup="onKeyUpEvent(5, event)" onfocus="onFocusEvent(5)"/>
							        <input id="codeBox6" type="number" maxlength="1" onkeyup="onKeyUpEvent(6, event)" onfocus="onFocusEvent(6)"/>
								</div>
							</div>
						</div>
						<div class="text-center">
							<p class="mb-0 t-grey">Didn't you received any code?</p>
							<a href="#" title="" class="blue-link">Resend a new code.</a>
						</div>
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
<!-- <script src="{{ URL::asset('assets/libs/jquery-mask-plugin/jquery-mask-plugin.min.js')}}"></script> -->

</body>
</html>

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
        if (getCodeBoxElement(index).value.length === 1) {
          if (index !== 6) {
            getCodeBoxElement(index+ 1).focus();
          } else {
            getCodeBoxElement(index).blur();
            // Submit code
            console.log('submit code ');
            $('#loader').show();

            $.ajax({
		            url: 'send_otp_mobile',
		            type: 'post',
		            dataType: "json",
		            data: {
		                "email": '',
		                "phone": '345435345',
		                 "_token": "{{ csrf_token() }}"
		            },
		            success: function (returnData) 
		            {
		                $('#loader').hide();
		                console.log(returnData);
		                if(returnData.status == true){
		                	Notiflix.Notify.Success(returnData.message);
		                }else{
		                	Notiflix.Notify.Failure(returnData.message);
		                }
		                return false;
		          
		            }
		      });

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
</script>