<script type="text/javascript" src="https://www.bridgepaynetsecuretest.com/WebSecurity/TokenPay/plain-js/tokenPay.js">
</script>
<!DOCTYPE html>
<html>

<head>
    <title>ABCTOGO | Pay</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="{{ URL::asset('assets/js/vendor.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/app.min.js') }}"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <style>
        #amount {
            width: 75px;
        }

        #card {
            background: #ffffffa3;
            height: 100px;
            padding: 10px 12px;
            border-radius: 5px;
            border: 1px solid #84a6ae;
            box-sizing: border-box;
            line-height: 0;
            margin: 9px 0px;
            width: 500px;
        }

        #cardNumber {
            background-color: #000;
        }

    </style>
    <style id="customStyles">
        .input-style {
            color: #32a866;
        }

        .input-hint {
            /*color: #5e95a1;*/
            color: #a4a7b4;
            border-color: #a4a7b4;
            /*border-color: #9ac0c8;*/
        }

        .form-wrapper {
            width: 700px;
        }

        .input-wrapper {
            width: 300px;
            /*display: block;*/
        }

        .sub-wrapper {
            width: 300px;
        }

        .valid-class {
            color: #217195;
        }

        .invalid-class {
            color: #b05826;
        }

    </style>
</head>

<body>

    <form id="paymentForm" action="{{ route('test_make_payment') }}" method="post" style="margin: 10px;">
        @csrf
        @method('POST')
        <!-- <form id="paymentForm" action="https://www.bridgepaynetsecuretest.com/WebSecurity/echo.aspx" method="post" style="margin: 10px;"> -->
        <!-- <div id="card" style="border: solid 1px lightgray; height: 100px; width: 500px; padding: 20px 10px; border-radius: 5px; margin: 10px 0px; background: #79797914;"></div> -->
        <div id="card"
            style="border: solid 1px lightgray; height: 200px; width: auto; padding: 20px 10px; border-radius: 10px; margin: 10px 0px; overflow-y: scroll; color:#5E97BE !important;">
        </div>
        <input type="hidden" name="price" value="{{ $price }}">
        <div id="errorMessage" style="margin-bottom: 10px; color: #c0392b;"></div>
        <button type="submit" class="btn btn-lg" style="background-color: #5E97BE; color: white;">Pay Now</button>
    </form>

</body>

</html>

<script>
    var tokenpay = TokenPay('tokenpay1430api20215628035637543');
    tokenpay.initialize({
        dataElement: 'card',
        errorElement: 'errorMessage',
        amountElement: 'amount',

        useACH: false,
        //if displaying all 4 fields then useStyles=false, disableZip=false, disableCvv=false
        //if displaying 3 out of 4 fields then useStyles=false, and set disableZip or disableCvv equal to true
        //if displaying 2 out of 4 fields then useStyles=true, disableZip=true, disableCvv=true
        useStyles: true,
        disableZip: false,
        disableCvv: false
    });
    var form = document.getElementById('paymentForm');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        $('#loader').show();
        tokenpay.createToken(function(result) {
            $('#loader').show();
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'token');
            hiddenInput.setAttribute('value', result.token);
            form.appendChild(hiddenInput);
            form.submit();
        }, function(result) {
            $('#loader').hide();
            console.log("error: " + result);
        });
    });
</script>

<html>

<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
    <meta name="Robots" content="NOINDEX " />
</head>

<body>
</body>
<script type="text/javascript">
    var gearPage = document.getElementById('GearPage');
    if (null != gearPage) {
        gearPage.parentNode.removeChild(gearPage);
        document.title = "Error";
    }
</script>

</html>
<html>

<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
    <meta name="Robots" content="NOINDEX " />
</head>

<body></body>
<script type="text/javascript">
    var gearPage = document.getElementById('GearPage');
    if (null != gearPage) {
        gearPage.parentNode.removeChild(gearPage);
        document.title = "Error";
    }
</script>

</html>
<div id="loader"
    style="position: absolute;top: 0;left: 0;right: 0;height: 100%; opacity: 1;background-color: #ffffff73;z-index: 99; display: none;">
    <lottie-player src="https://assets3.lottiefiles.com/packages/lf20_i2iugofy.json" background="transparent" speed="1"
        style="width: 250px; height: 250px; margin: 0 auto; z-index: 100;position: absolute;top: 50%;left: 0;right: 0;transform: translateY(-50%);"
        autoplay loop></lottie-player>
</div>
