<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ABCTOGO</title>
    <link rel="stylesheet" href="{{ URL::asset('assets/css/website/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/website/bootstrap-slider.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/website/style.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/website/responsive.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/website/slick.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}">
    <link href="{{ URL::asset('assets/libs/notiflix/notiflix-2.1.2.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="" id="age-restriction" tabindex="-1" role="dialog"
        aria-labelledby="age-restriction" aria-hidden="true">
        <div class="" role="document">
            <div class=" border-r10">
                <div class=" py-5 text-center">
                    <div>
                        <a href="javascript:void(0);" title="" class="login-logo mb-4"><img
                                src="{{ URL::asset('assets/images/website/modal-logo.jpg') }}" alt=""></a>
                    </div>
                    <div class="my-5 broken-bottle">
                        <img src="{{ URL::asset('assets/images/website/broken-bottle.jpg') }}" alt="">
                    </div>
                    <p class="font-20 t-black">Sorry! You need to be 21 to use <br> this Website!</p>
                </div>
            </div>
        </div>
</div>
</body>
    <script src="{{ URL::asset('assets/js/website/jquery.min.js')}}"></script>
    <script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js')}}"></script>
    <script src="{{ URL::asset('assets/libs/notiflix/notiflix-2.1.2.js')}}"></script>
    <script src="{{ URL::asset('assets/libs/select2/select2.min.js')}}"></script>
    <script src="{{ URL::asset('assets/js/website/bootstrap.min.js')}}"></script>
    <script src="{{ URL::asset('assets/js/website/popper.min.js')}}"></script>
    <script src="{{ URL::asset('assets/js/website/slick.min.js')}}"></script>
    <script src="{{ URL::asset('assets/js/website/bootstrap-slider.min.js')}}"></script>
    <script src="{{ URL::asset('assets/js/website/custom.js')}}"></script>
    <script src="{{ URL::asset('assets/libs/lottiefiles/lottie-player.js')}}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
</html>
