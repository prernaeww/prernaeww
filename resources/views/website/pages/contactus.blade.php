@extends('website.layouts.master')
@section('content')
<style type="text/css">
	.parsley-errors-list > li:before
	{
		display: none;
	}
</style>
@include('website.layouts.nav')

<section class="contact-us-page my-5 py-lg-5">

	<div class="container">
		@include('website.include.flash-message')
		<div class="row align-items-center">

			<div class="col-md-6 pr-xl-5">
				<h6 class="t-blue mb-4">OUR MISSION</h6>
				<h3>How Can We Help You?</h3>
				<p class="t-grey">You can fill the form give here,</p>
				<p class="t-grey">or else you can drop an Email regarding your questions or queries.</p>
				<div class="contact-info mt-5">
					<a href="tel:(239) 555-0108" title="" class="contact-info-call d-block mb-4 font-600"> <img src="{{url('assets/images/website/call.jpg')}}" alt="" class="shadow border-50 mr-3" width="50" height="50">(239) 555-0108</a>
					<a href="mailto:abctogo@info.com" title="" class="contact-info-mail d-block mb-4 font-600"> <img src="{{url('assets/images/website/mail.jpg')}}" alt="" class="shadow border-50 mr-3" width="50" height="50">abctogo@info.com</a>
				</div>
			</div>
			<div class="col-md-6 mt-5 mt-md-0">
				<div class="contact-us-form p-lg-5 py-5 p-4 border-r20">
					<form action="{{route('contactus.store')}}" method="POST" enctype="multipart/form-data" >
						@csrf
						<div class="form-group mb-4">
							<label class=" mb-0 t-blue font-20 font-700 w-100">Your Name
								<input type="text" name="user_name" parsley-trigger="change" value="" placeholder="Amelie Jack" class="input-field mt-2 border-r50" required>
								@error('user_name')
                                    <div class="error">{{ $message }}</div>
                                @enderror
							</label>
						</div>
						<div class="form-group mb-4">
							<label class=" mb-0 t-blue font-20 font-700 w-100">Your Email
								<input type="email" name="email" value="" placeholder="ameliejack@mail.com" class="input-field mt-2 border-r50" parsley-trigger="change" required>
								@error('email')
                                    <div class="error">{{ $message }}</div>
                                @enderror
							</label>
						</div>
						<div class="form-group mb-4">
							<label class=" mb-0 t-blue font-20 font-700 w-100">Your Message
								<textarea rows="5" placeholder="Write here..." class="input-field mt-2 border-r20" name="message" required></textarea>
								@error('message')
                                    <div class="error">{{ $message }}</div>
                                @enderror
							</label>
						</div>
						<div class="form-group mb-4">
							<label class=" mb-0 upload-file t-blue font-18 font-700 w-100">File Attechment
								<input type="file" name="document">
								@error('document')
                                    <div class="error">{{ $message }}</div>
                                @enderror
							</label>
						</div>
						<div class="text-center pt-4">
							<!-- <a href="#" title="" class="bg-darkblue d-block p-2 border-r50 text-white">Submit</a> -->
							<button type="submit" class="bg-darkblue p-2 border-r50 text-white d-block w-100">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection