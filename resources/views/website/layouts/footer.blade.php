    <footer class="pt-5" style="margin-top: 50px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-sm-6 mb-4 pr-xl-5">
                    <a class="mb-3 d-inline-block" href="{{url('/home')}}"><img src="{{ URL::asset('assets/images/website/logo.svg') }}" alt="" class="img-fluid"></a>
                    <p>Beer, wine and liquor delivered in under 60 minutes.</p>
                    <div class="mt-4">
                        <a href="{{CommonHelper::ConfigGet('facebook')}}" target="_blank" title="" class="d-inline-block mr-4 facebook"></a>
                        <a href="{{CommonHelper::ConfigGet('instagram')}}" target="_blank" title="" class="d-inline-block mr-4 instagram"></a>
                        <a href="{{CommonHelper::ConfigGet('twitter')}}" target="_blank" title="" class="d-inline-block mr-4 twitter"></a>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-6 mb-4">
                    <h5 class="t-black font-700 mb-4">Links</h5>
                    <ul class="quick-links mb-0">
                        <li><a href="{{url('home')}}" title="" class="blue-link">Home</a></li>
						@auth
                        <li><a href="{{url('orders/process')}}" title="" class="blue-link">My Order</a></li>
						@endauth
                        <li><a href="{{url('about-us')}}" title="" class="blue-link">About Us</a></li>
                        <!-- <li><a href="{{url('contactus')}}" title="" class="blue-link">Contact Us</a></li> -->
                    </ul>
                </div>
                <div class="col-lg-2 col-sm-6 mb-4">
                    <h5 class="t-black font-700 mb-4">About</h5>
                    <ul class="quick-links mb-0">
                        <li><a href="{{url('term-of-service')}}" target="_blank" title="" class="blue-link">Terms & Conditions</a></li>
                        <li><a href="{{url('privacy-notice')}}" target="_blank" title="" class="blue-link">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-sm-6 mb-4 pl-xl-5">
                    <h5 class="t-black font-700">Download App</h5>
                    <div class="row mx-0">
                        <div class="col-md-6 col-7 px-0 mt-4 pr-3">
                            <a href="https://play.google.com/" target="_blank" title=""><img src="{{ URL::asset('assets/images/website/play-store.jpg') }}" alt="" class="img-fluid border-r5"></a>
                        </div>
                        <div class="col-md-6 col-7 px-0 mt-4 pr-3">
                            <a href="https://www.apple.com/in/store" target="_blank" title=""><img src="{{ URL::asset('assets/images/website/app-store.jpg') }}" alt="" class="img-fluid border-r5"></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="py-3 mt-3 border-top d-sm-flex justify-content-between">
                <p class="text-sm-left text-center mb-0">Copyright © {{date('Y')}} AbcToGo</p>
                <div class="mt-2 text-sm-right text-center mt-sm-0">
                    <a href="{{url('privacy-notice')}}" target="_blank" title="" class="grey-link mr-4 mr-md-5">Privacy Policy</a>
                    <a href="{{url('term-of-service')}}" target="_blank" title="" class="grey-link">Term & Services</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>

