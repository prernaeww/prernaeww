<?php
$footer_cookie = request()->cookie('age-restriction');    
?>
@yield('script')

<script src="{{ URL::asset('assets/js/website/jquery.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/jquery-mask-plugin/jquery-mask-plugin.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/notiflix/notiflix-2.1.2.js') }}"></script>
<script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/website/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/website/popper.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/website/slick.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/website/bootstrap-slider.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/website/custom.js') }}"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="{{ URL::asset('assets/libs/lottiefiles/lottie-player.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>

<script type="text/javascript">
    var verifymobile = '<?php if (isset($_GET['verify'])) {
    echo $_GET['verify'];
} ?>';
    if (verifymobile == 'mobile') {
        $("#account-detail-modal").modal("show");
    }

    @if (Auth::guest() && $footer_cookie != 'Yes')  
        $(window).load(function() { 
        $('#age-confirmation').modal({  
        backdrop: 'static', 
        keyboard: false 
        }); 
        console.log('>> >> Show => (Are you 21 ?) ');   
        }); 
    @endif  

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

    $(document).ready(function() {
        $('form').parsley();
        $('.select2').select2();
        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
            $(".alert").slideUp(500);
        });

        $('#datepicker-autoclose').datepicker({
            format: 'mm-dd-yyyy',
            endDate: '-21y',
            autoclose: true
        });


    });

    var add_channel_url = 'add-channel-id';
    console.log('add_channel_url:' + add_channel_url);
    var OneSignal = window.OneSignal || [];
    OneSignal.push(function() {
        OneSignal.init({
            appId: "<?= config('app.appID') ?>",
            autoResubscribe: true,
            notifyButton: {
                enable: true,
            },
        });
    });

    @if (!Auth::guest())
        OneSignal.push(function() {
        OneSignal.on('subscriptionChange', function(isSubscribed) {
        // alert('here123');
        console.log('isSubscribed:' + isSubscribed);
        if (isSubscribed === true) {
    
        console.log('The user subscription state is now:', isSubscribed);
    
        OneSignal.getUserId(function(userId) {
        // Make a POST call to your server with the user ID
        console.log('The player id is :', userId);
    
        $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
        $.ajax({
        url: "<?= config('app.url') ?>" + add_channel_url,
        type: "POST",
        data: {
        channel_id: userId,
        },
        success: function(returnData) {
        returnData = $.parseJSON(returnData);
        if (typeof returnData != "undefined") {
        console.log(returnData);
        }
        },
        error: function(xhr, ajaxOptions, thrownError) {
        console.log('error in saving channel');
        }
        });
    
        });
    
        }
        });
    
        OneSignal.isPushNotificationsEnabled(function(isEnabled) {
        console.log('isEnabled:' + isEnabled);
        if (isEnabled) {
    
        // user has subscribed
        OneSignal.getUserId(function(userId) {
        console.log('player_id of the subscribed user is : ' + userId);
        // Make a POST call to your server with the user ID
        $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
        $.ajax({
        url: "<?= config('app.url') ?>" + add_channel_url,
        type: "POST",
        data: {
        channel_id: userId,
        },
        success: function(returnData) {
        returnData = $.parseJSON(returnData);
        if (typeof returnData != "undefined") {
        console.log(returnData);
        }
        },
        error: function(xhr, ajaxOptions, thrownError) {
        console.log('error in saving channel');
        }
        });
    
    
        });
        }
        });
        });
    @else
        console.log(' >> >> user not found...');
    @endif
</script>
<div id="loader"
    style="width: 100%; height: 100%; position: fixed;display: block;top: 0;left: 0;text-align: center;opacity: 1;background-color: #ffffff73;z-index: 111111; display: none;">
    <lottie-player src="{{ URL::asset('assets/libs/lottiefiles/lf20_i2iugofy.json') }}" background="transparent"
        speed="1"
        style="width: 250px; height: 250px; position: absolute;z-index: 1111; top: 50%; left: 50%; transform: translate(-50%, -50%);"
        autoplay loop></lottie-player>
</div>
<!--        <div id="loader" style="width: 100%; position: absolute;top: 0;left: 0;right: 0;height: 100%; opacity: 1;background-color: #ffffff73;z-index: 111111; display: none;">
        <lottie-player src="{{ URL::asset('assets/libs/lottiefiles/lf20_i2iugofy.json') }}" background="transparent" speed="1" style="width: 250px; height: 250px; margin: 0 auto; z-index: 1111;position: absolute;top: 50%;left: 0;right: 0;transform: translateY(-50%);" autoplay loop></lottie-player>
    </div> -->
