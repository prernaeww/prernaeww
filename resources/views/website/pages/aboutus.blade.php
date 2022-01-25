@extends('website.layouts.master')
@section('content')
@include('website.layouts.nav')
<section class="mt-5">
	<div class="container">
		<div class="row">
			<div class="col-md-12 text-center">
				<label class="mb-0 page-label mb-5">about us</label>
				<h3>Plan for quick Food growth</h3>
				<p class="t-grey">we let you shop a huge selection of beer, wine and liquor and get it delivered to your door in under 60 minutes. It’s super simple and lets you get back to doing whatever you’re doing.</p>
			</div>
		</div>
		<div class="row align-items-center mt-5">
			<div class="col-md-6">
				<img src="{{url('assets/images/website/company-profile.jpg')}}" alt="" class="img-fluid border-r20">
			</div>
			<div class="col-md-6 mt-4 mt-md-0">
				<h6 class="t-blue mb-4">OUR MISSION</h6>
				<h4>What's a company profile?</h4>
				<p class="t-grey">Eight years ago, AbcToGo started as a simple text from one friend to another: “Why can't you get alcohol delivered?" When we realized that alcohol delivery was, in fact, legal, we set out with a little bit of luck and a lotta bit of determination to build a three-tier compliant technology company that would change the way we shop for beer, wine, and spirits.</p>
				<a href="#scroll_for_explore"><label class="scroll-for-explore mb-0">Scroll for Explore</label></a>
			</div>
		</div>
		<div class="row mt-5 pt-lg-4" id="scroll_for_explore">
			<div class="col-md-9">
				<div class="our-vision bg-blue p-4 p-md-5">
					<div class="col-lg-9 px-0">
						<h6 class="t-blue mb-4">OUR VISION</h6>
				        <h4>The vision behind </h4>
				        <p class="t-grey">Today, AbcToGo is the largest online marketplace for alcohol in North America. Our purpose is to be there when it matters – committed to life's moments and the people who create them. We partner with thousands of retailers in more than 1,400 cities to empower them to grow their businesses and make our customers' good times better. AbcToGo is available to 100M+ customers and counting across the U.S. and Canada, offering a rich e-commerce shopping experience with personalized content, competitive and transparent pricing, and an unrivaled selection.</p>
					</div>
				</div>
			</div>
			<div class="col-md-8 ml-auto mt-4 mt-md-0">
				<div class="custom-video px-2 px-sm-4 px-md-0">
					<div class="video-container">
						<div class="js-video ng-isolate-scope" >
							<div class="video-poster">

								<img src="{{url('assets/images/website/video-thumb.jpg')}}" alt="" class="w-100 border-r20">
							</div>
							<div class="play"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row mt-5 pt-lg-5">
			<div class="col-md-12 mb-5">
				<h6 class="t-blue mb-4 text-center">OUR TEAM</h6>
				<h4 class="text-center">Team That Has Expertise In Varies Fields </h4>
			</div>
			<div class="col-md-3 col-sm-6 mb-4">
				<img src="{{url('assets/images/website/team1.jpg')}}" alt="" class="w-100 border-r20">
			</div>
			<div class="col-md-3 col-sm-6 mb-4">
				<img src="{{url('assets/images/website/team2.jpg')}}" alt="" class="w-100 border-r20">
			</div>
			<div class="col-md-3 col-sm-6 mb-4">
				<img src="{{url('assets/images/website/team3.jpg')}}" alt="" class="w-100 border-r20">
			</div>
			<div class="col-md-3 col-sm-6 mb-4">
				<img src="{{url('assets/images/website/team4.jpg')}}" alt="" class="w-100 border-r20">
			</div>
		</div>
		<div class="about-us-faq row mt-5 pt-lg-5">
			<div class="col-md-6 pr-lg-5">
				<h6 class="t-blue mb-4 ">FAQs</h6>
				<h3 class="mb-5 col-xl-9 px-0">Frequently Asked Question</h3>
				<div id="accordion">
					<div class="card mb-3 mb-md-4">
						<div class="card-header faq-1 p-2 p-md-4 shadow"  id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
							<p class="mb-0 t-black font-700"> What is AbcToGo ?</p>
							<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
								<div class="card-body mt-2 p-0">
									AbcToGo is the technology company powering the fastest, most convenient way for consumers of legal drinking age to buy alcohol and have it delivered right to their door.
								</div>
							</div>
						</div>
					</div>
					<div class="card mb-3 mb-md-4">
						<div class="card-header faq-2 p-2 p-md-4 shadow collapsed" id="headingTwo" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
							<p class="mb-0 t-black font-700">How does AbcToGo ?</p>
							<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
								<div class="card-body mt-2 p-0">
									AbcToGo partners with local liquor retailers to bring their inventory to your fingertips. Just download the AbcToGo app to a smartphone (iOS and Android), or use your Web browser to get your favorite beer, wine or liquor delivered to your doorstep.
								</div>
							</div>
						</div>
					</div>
					<div class="card mb-3 mb-md-4">
						<div class="card-header faq-3 p-2 p-md-4 shadow collapsed" id="headingThree" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
							<p class="mb-0 t-black font-700"> Are you sure alcohol delivery is legal ? </p>
							<div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
								<div class="card-body mt-2 p-0">
									Yes, alcohol delivery is perfectly legal in the cities and states we serve. We've been helping local stores deliver beer, wine and liquor since 2012, so you can trust us that it’s all above board!
								</div>
							</div>
						</div>
					</div>
					<div class="card mb-3 mb-md-4">
						<div class="card-header faq-4 p-2 p-md-4 shadow collapsed" id="headingfour" data-toggle="collapse" data-target="#collapsefour" aria-expanded="false" aria-controls="collapsefour">
							<p class="mb-0 t-black font-700">How many products are available through AbcToGo ? </p>
							<div id="collapsefour" class="collapse" aria-labelledby="headingfour" data-parent="#accordion">
								<div class="card-body mt-2 p-0">
									Typically more than 2,000 products are made available from any given store. Products include beer, wine, liquor, and extras like soda, mixers, non-alcoholic drinks, and snacks (subject to state law). We directly link to most of our partner stores' inventories so you can see what they currently have listed in stock.
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 mt-4 mt-md-0 pl-lg-5">
				<p class="t-grey text-center mb-5 col-xl-8 px-0 mx-auto">Provide convenience services with attractive and fun options.</p>
			    <div class="faq-img relative">
			    	<img src="{{url('assets/images/website/hands.jpg')}}" alt="" class="w-100 border-r20">
			    </div>
			</div>
		</div>
	</div>
</section>
@endsection