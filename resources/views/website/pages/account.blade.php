@extends('website.layouts.master')
@section('content')
@include('website.layouts.nav')

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
    .account-page-settings form a{
    	padding-left: 0 !important;
    	color: #19181A;
    }
    .account-page-settings form, .acc-logout{
    	cursor: pointer;
    }
</style>

<section class="account-page my-5 pb-lg-5">
	<div class="container">
		@include('website.include.flash-message')
		<h3 class="t-blue mb-4 pb-3 border-bottom">Account</h3>
		<div class="row">
			<div class="col-md-4">
				<div class="bg-white shadow border-r10 p-lg-5 p-4 h-100 d-flex justify-content-center align-items-center">
					<div class="account-page-image text-center">
						<img src="{{ url(Auth::user()->profile_picture) }}" alt="" class="border-r10 mb-4">
						<h5 class="t-blue">{{ Auth::user()->first_name.' '.Auth::user()->last_name }}</h5>
						<p class="t-grey2 mb-0">{{ Auth::user()->email }}</p>
					</div>
				</div>
			</div>
			
			<div class="col-md-8 mt-5 mt-md-0">
				<div class="bg-white shadow border-r10 p-lg-5 p-4 h-100">
					<div class="account-page-settings mr-lg-5 pr-xl-5">
						<ul class="mb-0">
							<li><a href="#" data-toggle="modal" data-target="#account-detail-modal" class="acc-account-detail">Account Details</a></li>

							<li><a href="{{ route('favorite') }}" class="acc-saved">Saved</a></li>
							<li><a href="{{ route('address.index') }}" class="acc-address">Address</a></li>
							@if(Auth::user()->notification == 1)
								@php
								$active = 'active';
								@endphp
							@else
								@php
								$active = '';
								@endphp
							@endif

							<li><button type="submit" class="acc-notification notification-toggle relative w-100 text-left {{  $active}}" onclick="notification({{Auth::user()->notification}})" data-notification='{{Auth::user()->notification}}' id="notification_id">Notifications</button></li>

							<li><a href="{{route('save_card_list')}}"  class="save-card">Saved Cards</a></li>
							
							<li><a href="#" data-toggle="modal" data-target="#interest-ads-modal" class="acc-ads">Interest Based Ads</a></li>



							<li><a href="#" data-toggle="modal" data-target="#education-modal" class="acc-education">Education Outreach</a></li>

							<form method="POST" action="{{ route('customer.logout') }}">
			                    @csrf
			                    <x-dropdown-link :href="route('customer.logout')"
			                            onclick="event.preventDefault();
			                                        this.closest('form').submit();">
			                    <li><label class="acc-logout">Log Out</label></li>
			                    </x-dropdown-link>
			                </form>

							
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<div class="modal fade" id="account-detail-modal" tabindex="-1" role="dialog" aria-labelledby="account-detail-modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content border-r10">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body py-5 px-md-5 mx-lg-5">
                <button type="button" data-dismiss="modal" aria-label="Close" class="back-button"><img src="{{ URL::asset('assets/images/website/back.jpg')}}" alt="" class="border-50"></button>
                <form action="{{ route('update') }}" method="post" enctype="multipart/form-data">
                	@csrf
                	@method('POST')
                    <h5 class="mb-5 text-center mt-5 mt-md-0">Account details</h5>
                    <div class="row align-items-center">
                        <div class="col-md-6 pr-xl-5 text-center">
                            <div class="account-profile-image">
                                <img src="{{ url(Auth::user()->profile_picture) }}" alt="" class="border-r20" id="blah">
                                <div class="mt-3">
                                    <label class="image-upload t-black">Change
                                    	<input type="file" name="image" onchange="readURL(this);" data-parsley-max-file-size="5" data-parsley-filemimetypes="image/jpeg, image/png" accept="image/*" data-parsley-file-mime-types-message="Only allowed jpeg & png files">
                                    </label>
                                </div>
                            </div>	
                        </div>
                        <div class="col-md-6 border-left border-left-mobile-0 px-xl-5 mt-5 mt-md-0">
                        	<div class="mb-2">
                        		<div class="form-group mb-0 border-bottom-login">
	                                <label class="font-14 mb-0 w-100 t-grey">First Name<span class="text-danger">*</span>
	                                    <input type="text" name="first_name" required placeholder="Enter first name" value="{{ Auth::user()->first_name }}" class="sign-in-input-field"  parsley-trigger="change" data-parsley-errors-container="#fname_error">
	                                </label>
	                                
	                            </div>
	                            <div id="fname_error"></div>
                        	</div>

                        	<div class="mb-2">
                        		<div class="form-group mb-0 border-bottom-login">
	                                <label class="font-14 mb-0 w-100 t-grey">Last Name<span class="text-danger">*</span>
	                                    <input type="text" name="last_name" required placeholder="Enter last name" value="{{ Auth::user()->last_name }}" class="sign-in-input-field"  parsley-trigger="change" data-parsley-errors-container="#lname_error">
	                                </label>
	                                
	                            </div>
	                            <div id="lname_error"></div>
                        	</div>

                           
                            <div class="form-group mb-3 border-bottom-login">
                                <label class="font-14 mb-0 w-100 t-grey">Email Address
                                    <input type="text" name="email" value="{{ Auth::user()->email }}" disabled class="sign-in-input-field">
                                </label>
                            </div>

                            <div class="mb-2">
                        		<div class="form-group mb-0 border-bottom-login">
	                                <label class="font-14 mb-0 w-100 t-grey">Mobile Number<span class="text-danger">*</span> 
	                                	@if(Auth::user()->phone == '')
	                                	<label class="ml-1" id="send-otp">Click here for verify</label>
	                                	@else
	                                	<label class="ml-1 d-none" id="send-otp">Click here for verify</label>
	                                	@endif
	                                    @php
	                            		$phone = Auth::user()->phone;
	                            		if($phone != ''){
	                            			$phone = CommonHelper::SetPhoneFormat($phone);
	                            			$disabled = 'disabled';
	                            		}else{
	                            			$phone = '';
	                            			$disabled = '';
	                            		}
	                            		
	                            		@endphp
	                                   <!--  <input type="text" name="phone" value="{{ $phone }}" {{ $disabled }} class=" sign-in-input-field" required data-parsley-errors-container="#phone_error"> -->
	                                   <input type="text" name="phone"  value="{{ $phone }}" {{ $disabled }} parsley-trigger="change" placeholder="1(XXX)XXX-XXXX" data-mask="1(999)999-9999" class="sign-in-input-field number" data-parsley-maxlength="14"  data-parsley-minlength="14" data-parsley-minlength-message="This value must be at least 14 characters" data-parsley-maxlength="14" data-parsley-maxlength-message="This value must be at least 14 characters" data-parsley-errors-container="#phone_error" required>
	                                </label>
	                                
	                            </div>
	                            <div id="phone_error"></div>
                        	</div>

                            <div class="form-group mb-3 border-bottom-login">
                                <label class="font-14 mb-0 w-100 t-grey">Date Of Birth
                                	@if(isset(Auth::user()->dob) && Auth::user()->dob != '')
                                		@php
                                		$dob = Auth::user()->dob;	
                                		$dob = date('m-d-Y', strtotime($dob));
                                		@endphp
                                	@else
                                		@php
                                		$dob = '';
                                		@endphp
                                	@endif
                                    <input type="text" name="dob" value="{{ $dob }}" id="datepicker-autoclose" placeholder="DD/MM/YYYY" readonly class="sign-in-input-field">
                                </label>
                            </div>
                            <div class="mt-5 pt-3 text-center">
                                <input type="submit" class="btn-blue2" value="Save">
                            </div>
                        </div>
                    </div>
                </form>
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

<div class="modal fade" id="education-modal" tabindex="-1" role="dialog" aria-labelledby="education-modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content border-r10">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body py-md-5 px-md-5 mx-lg-5">
                <button type="button" data-dismiss="modal" aria-label="Close" class="back-button"><img src="{{ URL::asset('assets/images/website/back.jpg')}}" alt="" class="border-50"></button>
                <h4 class="text-center t-black mb-5 mt-5 mt-md-0">Education</h4>
                <?= $education?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="interest-ads-modal" tabindex="-1" role="dialog" aria-labelledby="interest-ads-modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content border-r10">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body py-md-5 px-md-5 mx-lg-5">
                <button type="button" data-dismiss="modal" aria-label="Close" class="back-button"><img src="{{ URL::asset('assets/images/website/back.jpg')}}" alt="" class="border-50"></button>
                <h4 class="text-center t-black mb-5 mt-5 mt-md-0">Interest Based Ads</h4>
                
                <?= $interest ?>
            </div>
        </div>
    </div>
</div>
@endsection
<script type="text/javascript">
     
	
	function readURL(input) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();

	        reader.onload = function (e) {
	            $('#blah').attr('src', e.target.result);
	        };
	        reader.readAsDataURL(input.files[0]);
	    }
	}


</script>
