<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>{{config('app.name')}}</title>
	<link rel="icon" href="{{ URL::asset('images/favicon.ico')}}" type="image/gif" sizes="16x13">
	<link rel="stylesheet" href="{{ URL::asset('assets/front/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{ URL::asset('assets/front/css/style.css')}}">
	<link rel="stylesheet" href="{{ URL::asset('assets/front/css/responsive.css')}}">
	<link rel="stylesheet" href="{{ URL::asset('assets/front/css/slick.css')}}">
</head>
<body class="bg-transparent">
	<section class="top-padding">
		<div class="container">
			<div class="row mx-2">
				<div class="col-md-12">
					<div class="text-center pb-lg-5">
						<!-- <h1 class="t-blue font-weight-bold border-bottom1 pb-3 mb-5 d-inline-block"> Education Outreach</h1> -->
					</div>					
					<!-- <b><p>What is Lorem Ipsum?</p></b> -->
					
					<?= $data ?>
					
				</div>
			</div>
		</div>
	</section>
</body>
</html>
<script src="{{ URL::asset('assets/front/js/jquery.min.js')}}"></script>
<script src="{{ URL::asset('assets/front/js/bootstrap.min.js')}}"></script>
<script src="{{ URL::asset('assets/front/js/popper.min.js')}}"></script>
<script src="{{ URL::asset('assets/front/js/slick.min.js')}}"></script>
<script src="{{ URL::asset('assets/front/js/custom.js')}}"></script>