        <!-- Vendor js -->
        <script src="{{ URL::asset('assets/js/vendor.min.js')}}"></script>

        @yield('script')

        <!-- App js -->
        <script src="{{ URL::asset('assets/js/app.min.js')}}"></script>
        <script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js')}}"></script>
        <script src="{{ URL::asset('assets/js/common.js')}}"></script>
        <script src="{{ URL::asset('assets/libs/jquery-mask-plugin/jquery-mask-plugin.min.js')}}"></script>
        <script src="{{ URL::asset('assets/libs/notiflix/notiflix-2.1.2.js')}}"></script>
        <script src="{{ URL::asset('assets/libs/select2/select2.min.js')}}"></script>
        <script src="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
        <script src="{{ URL::asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js')}}"></script>
        <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
        <!-- <script src="{{ URL::asset('assets/js/pages/form-advanced.init.js')}}"></script> -->
        <script type="text/javascript">
$(document).ready(function() {
    $('form').parsley();
    $('.select2').select2();
    $(".alert").fadeTo(2000, 500).slideUp(500, function() {
        $(".alert").slideUp(500);
    });

});
var path = window.location.pathname;
console.log(path);
if (path.split('/')[2] == "order") {
    $(".metismenu li a[href*='" + path.split('/')[1] + '/' + path.split('/')[2] + '/' + path.split('/')[3] + "']")
        .addClass("active").closest('li').addClass("active").closest('ul').addClass('in');
} else {
    $(".metismenu li a[href*='" + path.split('/')[1] + '/' + path.split('/')[2] + "']").addClass("active").closest('li')
        .addClass("active").closest('ul').addClass('in');
}
window.Parsley.addValidator('maxFileSize', {
    validateString: function(_value, maxSize, parsleyInstance) {
        if (!window.FormData) {
            alert('You are making all developpers in the world cringe. Upgrade your browser!');
            return true;
        }
        var files = parsleyInstance.$element[0].files;
        return files.length != 1 || files[0].size <= maxSize * 1024 * 1024;
    },
    requirementType: 'integer',
    messages: {
        en: 'This file should not be larger than %s MB',
    }
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

@if(!Auth::guest())
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
        @yield('script-bottom')
        <div id="loader"
            style="width: 100%; height: 100%; position: fixed;display: block;top: 0;left: 0;text-align: center;opacity: 1;background-color: #ffffff73;z-index: 99; display: none;">
            <lottie-player src="https://assets3.lottiefiles.com/packages/lf20_i2iugofy.json" background="transparent"
                speed="1" style="width: 250px; height: 250px; position: absolute;top: 36%;left: 46%;z-index: 100;"
                autoplay loop></lottie-player>
        </div>