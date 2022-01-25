<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
<style type="text/css">
	body{
margin-top: 150px;
background-color: #C4CCD9;
}
.error-main{
background-color: #fff;
box-shadow: 0px 10px 10px -10px #5D6572;
}
.error-main h1{
font-weight: bold;
color: #444444;
font-size: 100px;
text-shadow: 2px 4px 5px #6E6E6E;
}
.error-main h6{
color: #42494F;
}
.error-main p{
color: #9897A0;
font-size: 14px;
}
</style>
</head>
<body>
<div class="container">
<div class="row">
<div class="col-lg-12 col-sm-12 col-12 p-3 error-main text-center">
<div class="row">
<div class="col-12">
<h1 class="m-0">{{ $main_text }}</h1>
<h6>{{ $sub_text }}</h6>
</div>
</div>
</div>
</div>
</div>
</body>
</html>