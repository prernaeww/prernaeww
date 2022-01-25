@extends('website.layouts.master')
@section('content')
    @include('website.layouts.nav')
    <section class="banner">
        <div class="container">
            @include('website.include.flash-message')
            <div class="row">
                <div class="col-md-10 col-lg-8">
                    <h1 class="text-white mb-3">Let the drinks come to you.</h1>
                    <!-- <p class="font-20 text-white">Beer, wine and liquor delivered in under 60 minutes.</p> -->
                    <p class="font-20 text-white">Beer, wine and liquor delivered to your doorstep.</p>
                    <input type="hidden" id="administrative_area_level_2" name="zipcode">
                    <input type="hidden" id="administrative_area_level_1" name="state">
                    <input type="hidden" id="zipcode" class="zipcode" name="zipcode">
                    <input type="hidden" id="city" class="city" name="city">
                    <input type="hidden" id="longitude" class="longitude" name="longitude">
                    <input type="hidden" id="latitude" class="latitude" name="latitude">
                    <div class="banner-search mt-5 d-sm-flex align-items-center text-center">
                        <input type="text" name="" placeholder="Enter your delivery address" id="autocomplete"
                            autocomplete="off">
                        <button type="submit" data-toggle="modal" data-target="" id="get_location">Find Store</button>
                    </div>
                    <div>
                        <p id="lat_log_required" class="t-red"></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-5 pb-lg-5">
        <div class="container pb-xl-5">
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-5">
                    <h1 class="mb-5">How it <span class="font-400">Works</span></h1>
                    <div class="mb-4 mb-md-5">
                        <h3 class="mb-3"><span class="t-blue">01</span> Get started.</h3>
                        <p>Enter your address and browse the biggest selection of new, local, well-known and
                            not-so-well-known products EVER ASSEMBLED EVER.</p>
                    </div>
                    <div class="mb-4 mb-md-5">
                        <h3 class="mb-3"><span class="t-blue">02</span> Shop.</h3>
                        <p>Pick your drinks, choose which store (or stores) you want to get them from and press that magical
                            order button. We work with retailers in your area, which means you get to support local
                            businesses every time you order.</p>
                    </div>
                    <div class="mb-4 mb-md-5">
                        <h3 class="mb-3"><span class="t-blue">03</span> Get it delivered.</h3>
                        <p>We’ll let you know when your driver is on their way with the goods and BOOM, drinks delivered in
                            under 60 minutes.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-7 mt-4 mt-sm-0">
                    <div class="relative pl-lg-5">
                        <img src="{{ URL::asset('assets/images/website/how-it-works.jpg') }}" alt=""
                            class="img-fluid border-r20">
                        <div class="order-complete">
                            <label class="mb-0 py-3 font-600 px-4 border-r50 bg-white">Order Complete</label>
                        </div>
                        <div class="total-downloads bg-white p-3 border-r20 shadow">
                            <h3 class="t-blue">800K+ <span class="t-black font-26">Total Downloads</span></h3>
                            <p class="font-14 mb-0">We were about to write all the reasons why, but someone gave us a
                                glass of wine and sorry that was just more important. It’s got some cool stuff.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-5 pt-xl-5">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="{{ URL::asset('assets/images/website/best-liquor-app.png') }}" alt=""
                        class="img-fluid pr-xl-5">
                </div>
                <div class="col-md-6 mt-4 mt-md-0">
                    <h2 class="mb-3">The Best Liquor Delivery App. <span class="font-400">Download
                            Now!</span></h2>
                    <p>Browse thousands of new, local, well-known and not-so-well-known products. Select your favorites,
                        pick your local liquor store(s) and press that magical order button.</p>
                    <h4 class="pt-4 mb-4">Download Available</h4>
                    <div class="row">
                        <div class="col-md-5 col-7">
                            <a href="https://play.google.com/" target="_blank" title="" class="mb-4"><img
                                    src="{{ URL::asset('assets/images/website/play-store.jpg') }}" alt=""
                                    class="img-fluid border-r5"></a>
                        </div>
                        <div class="col-md-5 col-7">
                            <a href="https://www.apple.com/in/store" target="_blank" title="" class="mb-4"><img
                                    src="{{ URL::asset('assets/images/website/app-store.jpg') }}" alt=""
                                    class="img-fluid border-r5"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-blue py-5 mt-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 p-md-4">
                    <img src="{{ URL::asset('assets/images/website/customers.jpg') }}" alt=""
                        class="img-fluid border-r30">
                </div>
                <div class="col-md-6 mt-4 mt-md-0">
                    <h2 class="mb-3">What Customers <br><span class="font-400">Say About us</span></h2>
                    <p>Nobody will miss out on a memory because they were schlepping to a liquor store.</p>
                    <div class="food-lovers d-flex align-items-center pt-3">
                        <div class="food-lovers-img mr-3">
                            <img src="{{ URL::asset('assets/images/website/angel-jessica.jpg') }}" alt=""
                                class="border-50">
                        </div>
                        <div>
                            <h6 class="font-26 mb-0">Angel Jessica</h6>
                            <p class="mb-0">Food Lovers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--age confirmation Modal -->
    <div class="modal fade" id="age-confirmation" tabindex="-1" role="dialog" aria-labelledby="age-confirmationTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content border-r10">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-body py-5 col-xl-10 mx-auto text-center">
                    <div>
                        <a href="index.php" title="" class="login-logo"><img
                                src="{{ URL::asset('assets/images/website/modal-logo.jpg') }}" alt=""></a>
                    </div>
                    <div class="my-5 bg-blue border-r20 p-4 p-md-5">
                        <h3>Let The Good Times Flow</h3>
                        <h4 class="t-blue font-400 my-4">Are you 21 ?</h4>
                        <ul class="age-confirm d-flex justify-content-center align-items-center mb-0">
                            <li><button class="submit" data-dismiss="modal" data-toggle="modal"
                                    data-target="#age-restriction-modal">No</button></li>
                            <li><button id="map-nearby-store" class="submit get-both-data" data-dismiss="modal"
                                    data-toggle="modal" data-target="">Yes</button></li>
                            <!-- <li><button class="submit" data-dismiss="modal" data-toggle="modal" data-target="#enter-zip-code">Yes</button></li> -->
                        </ul>
                    </div>
                    <p class="font-14 t-black mb-4 col-md-10 col-lg-8 col-xl-6 mx-auto px-0">By entering this site you agree
                        to our <a href="{{ $url['about_us'] }}" title="" class="text-underline grey-link"
                            target="_blank">cookie policy</a>, <a href="{{ $url['term_of_service'] }}" title=""
                            class="text-underline grey-link" target="_blank">terms and conditions</a> and <a
                            href="{{ $url['privacy_notice'] }}" title="" class="text-underline grey-link"
                            target="_blank">privacy policy</a>. We use analytics cookies to enhance your browsing experience
                        and improve our website. Find out more in our <a href="{{ $url['about_us'] }}" title=""
                            class="text-underline grey-link" target="_blank">cookie policy</a>.</p>
                    <h5 class="t-blue mb-5">DRINK RESPONSIBLY.</h5>
                </div>
            </div>
        </div>
    </div>

    <!--age restriction-modal Modal -->
    <div class="modal fade" id="age-restriction-modal" tabindex="-1" role="dialog"
        aria-labelledby="age-restriction-modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content border-r10">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-body py-5 text-center">
                    <div>
                        <a href="index.php" title="" class="login-logo mb-4"><img
                                src="{{ URL::asset('assets/images/website/modal-logo.jpg') }}" alt=""></a>
                    </div>
                    <div class="my-5 broken-bottle">
                        <img src="{{ URL::asset('assets/images/website/broken-bottle.jpg') }}" alt="">
                    </div>
                    <p class="font-20 t-black">Sorry! You need to be 21 to use <br> this App!</p>
                </div>
            </div>
        </div>
    </div>

    <!--map and nearby store Modal-->
    <div class="modal fade" id="map-nearby-store-modal" tabindex="-1" role="dialog"
        aria-labelledby="map-nearby-store-modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content border-r10">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-body py-5 px-md-5 mx-lg-5">
                    <div class="col-xl-7 col-lg-9 mx-auto px-0 mt-5 pb-5">
                        <input type="text" name="" class="enter-zipcode-field" placeholder="Search any store"
                            id="suggestion_store" list="suggestion_stores">
                        <div style="width: 100%;" id="suggestion_stores_list">
                            <datalist id="suggestion_stores" class="suggestion_stores_list">

                            </datalist>
                        </div>

                    </div>
                    <div class="google-map mb-4">
                        <div id="map" style="height: 200px;width:100%; display:block;"></div>
                    </div>
                    <div class="stores-tab col-xl-7 col-lg-9 mx-auto px-0">
                        <ul class="nav nav-pills border-r10 bg-white shadow w-100 text-center">
                            <li class="nav-item">
                                <a class="nav-link get-both-data" data-toggle="pill" href="#favorites" role="tab"
                                    aria-controls="pills-favorites" aria-selected="true">Favorites</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="pill" href="#nearby" role="tab"
                                    aria-controls="pills-nearby" aria-selected="false">Nearby</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-4 pt-3">
                            <div class="tab-pane fade  " id="favorites" role="tabpanel" aria-labelledby="favorites-tab">
                                <div id="favorite-store-listing" class="home-store-listing pr-2"
                                    style="max-height: 400px; overflow: auto; ">
                                    <div class="text-center justify-content-between mb-4 ">
                                        <p class="t-red mb-0 font-20">No Store Found</p>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show active" id="nearby" role="tabpanel"
                                aria-labelledby="profile-tab">
                                <div id="nearby-store-listing" class="home-store-listing pr-2"
                                    style="max-height: 400px; overflow: auto; ">
                                    <div class="text-center justify-content-between mb-4 ">
                                        <p class="t-red mb-0 font-20">No Store Found</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src='https://maps.googleapis.com/maps/api/js?key={{ $mapkey }}&libraries&libraries=places' async defer>
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js') }}"></script>
    <script type="text/javascript">
        var locations = [
            //   ['Bondi Beach', -33.890542, 151.274856]
        ];
        var map;
        var markers = [];

        function init() {
            var url = '{{ url('/') }}';
            map = new google.maps.Map(document.getElementById('map'), {
                //   center: new google.maps.LatLng(-33.92, 151.25),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
            map.setOptions({
                minZoom: 10,
                // maxZoom: 15
            });
            console.log(`map: `, map);
            console.log(`locations: `, locations);

            var bounds = new google.maps.LatLngBounds();
            var infowindow = new google.maps.InfoWindow();

            var num_markers = locations.length;
            console.log(`num_markers: `, num_markers);
            for (var i = 0; i < num_markers; i++) {
                const loc = locations[i];
                var marker = new google.maps.Marker({
                    position: {
                        lat: locations[i][1],
                        lng: locations[i][2]
                    },
                    map: map,
                    html: locations[i][0],
                    icon: `${url}/assets/images/website/location-pin.png`,
                    id: i,
                });
                markers[i] = marker;
                bounds.extend(marker.position);
                google.maps.event.addListener(markers[i], 'click', function() {
                    const tooltip = `
                    <a class ="market_text" href="${url}/store-detail/${loc[3]}" >${this.html}</a>
                `;
                    var infowindow = new google.maps.InfoWindow({
                        id: this.id,
                        content: tooltip,
                        position: this.getPosition()
                    });
                    // window.open(`${url}/store-detail/${loc[3]}`, '_blank');
                    // window.location.href = `${url}/store-detail/${loc[3]}`;        
                    google.maps.event.addListenerOnce(infowindow, 'closeclick', function() {
                        markers[this.id].setVisible(true);
                    });
                    // this.setVisible(false);
                    // map.setZoom(1000);
                    // map.panTo(markers[this.id].position);
                    infowindow.open(map, markers[this.id]);
                });
            }
            map.fitBounds(bounds);

        }


        $(window).load(function() {
            $('#autocomplete').change(function() {
                $('#latitude').val('');
                $('#longitude').val('');
            });
            $('#get_location').click(function() {
                var latitude = $('#latitude').val();
                var longitude = $('#longitude').val();
                if (!longitude || !latitude) {
                    var msg = 'Please select from google suggestion';
                    $('#lat_log_required').text(msg);
                    // Notiflix.Notify.Failure(msg);
                    return;
                }
                $('#age-confirmation').modal('show');
            });

            // $('#map-nearby-store').click(function() {
            $('.get-both-data').click(function() {
                var latitude = $('#latitude').val();
                var longitude = $('#longitude').val();
                if (!longitude || !latitude) {
                    var msg = 'Please select from google suggestion';
                    $('#lat_log_required').text(msg);
                    // Notiflix.Notify.Failure(msg);
                    return;
                }
                $('#suggestion_store').val('');
                console.log('latitude:', latitude);
                console.log('longitude:', longitude);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('#loader').show();
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: '/store_list',
                    data: {
                        'latitude': latitude,
                        'longitude': longitude
                    },
                    success: function(response) {
                        $('#loader').hide();
                        $('#map-nearby-store-modal').modal('show');
                        locations = [];
                        console.log('Store List: ', response);
                        var near_by_store = response.near_by_store.data;
                        var favorite_store = response.favorite_store.data;
                        console.log('Store Near By Store: ', near_by_store);
                        console.log('Store Favorite Store: ', favorite_store);

                        if (near_by_store && near_by_store.length > 0) {
                            var near_by_store_data = '';
                            var suggestion_stores_list = '';
                            $(near_by_store).each(function(mainkey, mainval) {
                                $(mainval).each(function(key, val) {
                                    locations.push([
                                        `${val.first_name} ${val.last_name}`,
                                        parseFloat(val.latitude),
                                        parseFloat(val.longitude), val
                                        .id
                                    ]);
                                    var url = '{{ url('/') }}';
                                    var heart_img = url +
                                        '/assets/images/website/bd-heart.png';
                                    // var heart_img = url + '/assets/images/website/heart-empty.png';                        
                                    var add_remove = 1;
                                    if (val.favorite) {
                                        var heart_img = url +
                                            '/assets/images/website/saved.png';
                                        var add_remove = 0;
                                    }

                                    var delivery_type =
                                        `<img src="{{ URL::asset('assets/images/website/pick-up-method1.jpg') }}" alt="" class="mr-3">
                                            <img src="{{ URL::asset('assets/images/website/pick-up-method2.jpg') }}" alt="">`;
                                    if (val.delivery_type == 1) {
                                        var delivery_type =
                                            `<img src="{{ URL::asset('assets/images/website/pick-up-method1.jpg') }}" alt="" >`;
                                    } else if (val.delivery_type == 2) {
                                        var delivery_type =
                                            `<img src="{{ URL::asset('assets/images/website/pick-up-method2.jpg') }}" alt="">`;
                                    }

                                    if (response.user_logged_in == true) {
                                        var fav_btn =
                                            `<button type="submit" onclick="add_remove_fav_store(${val.id}, ${add_remove})"><img id="your_wishlist_store_${val.id}" class="your_wishlist_store_${val.id}" src="${heart_img}" alt="" data-add_remove='${add_remove}'></button>`;
                                    } else {
                                        var fav_btn =
                                            `<button type="submit" onclick="checkUserLogin();"><img id="" class="" src="${heart_img}" alt="" data-add_remove='${add_remove}'></button>`;
                                    }


                                    near_by_store_data +=
                                        `<div class="nearby-store-listing d-sm-flex justify-content-between mb-4">
                                    <a href="${url}/store-detail/${val.id}" >
                                        <div class="mr-3">
                                            <p class="t-black mb-0 font-20">${val.first_name} ${val.last_name}</p>
                                            <p class="t-black mb-0 font-14">${val.address}</p>
                                            <p class="t-black mb-0 font-14"><span>Miles ${val.distance}</span> <span class="font-26 line-height-0 px-2">.</span> <span>Open until ${val.start_time} to ${val.end_time}</span></p>
                                        </div>
                                        <div class="nearby-store-listing-right d-flex flex-sm-column justify-content-sm-between justify-content-start mt-4 mt-sm-0">
                                            <div class="text-right mb-sm-3 mr-3 mr-sm-0">
                                                <a href="tel:${val.phone}" title="" class="mr-3"><img src="{{ URL::asset('assets/images/website/call-icon.svg') }}" alt="" class="bg-white border-50 border-white border"></a>` +
                                        fav_btn +
                                        `</div>
                                            <div class="text-right">
                                                ${delivery_type}
                                            </div>
                                        </div>
                                    </a>
                                </div>`;

                                    suggestion_stores_list +=
                                        ` <option value="${val.first_name} ${val.last_name}" class="store-details" data-id="${val.id}" style="cursor: pointer; "> `;
                                })
                            });


                            $('#nearby-store-listing').empty();
                            $('#nearby-store-listing').append(near_by_store_data);

                            $('.suggestion_stores_list').empty();
                            $('.suggestion_stores_list').append(suggestion_stores_list);
                        } else {
                            $('#nearby-store-listing').empty();
                            var near_by_store_data = `<div class="text-center justify-content-between mb-4 ">
                                            <p class="t-red mb-0 font-20">No Store Found</p>
                                        </div>`;
                            $('#nearby-store-listing').append(near_by_store_data);

                            var suggestion_stores_list = ``;
                            $('.suggestion_stores_list').empty();
                            $('.suggestion_stores_list').append(suggestion_stores_list);
                        }

                        if (favorite_store && favorite_store.length > 0) {
                            var favorite_store_data = '';
                            $(favorite_store).each(function(mainkey, mainval) {
                                $(mainval).each(function(key, val) {
                                    var url = '{{ url('/') }}';
                                    var heart_img = url +
                                        '/assets/images/website/bd-heart.png';
                                    // var heart_img = url + '/assets/images/website/heart-empty.png';
                                    var add_remove = 1;
                                    if (val.favorite) {
                                        var heart_img = url +
                                            '/assets/images/website/saved.png';
                                        var add_remove = 0;
                                    }

                                    var delivery_type =
                                        `<img src="{{ URL::asset('assets/images/website/pick-up-method1.jpg') }}" alt="" class="mr-3">
                                            <img src="{{ URL::asset('assets/images/website/pick-up-method2.jpg') }}" alt="">`;
                                    if (val.delivery_type == 1) {
                                        var delivery_type =
                                            `<img src="{{ URL::asset('assets/images/website/pick-up-method1.jpg') }}" alt="" >`;
                                    } else if (val.delivery_type == 2) {
                                        var delivery_type =
                                            `<img src="{{ URL::asset('assets/images/website/pick-up-method2.jpg') }}" alt="">`;
                                    }

                                    if (response.user_logged_in == true) {
                                        var fav_btn =
                                            `<button type="submit" onclick="add_remove_fav_store(${val.id}, ${add_remove})"><img id="your_wishlist_store_${val.id}" src="${heart_img}" class="your_wishlist_store_${val.id}" alt="" data-add_remove='${add_remove}'></button>`;
                                    } else {
                                        var fav_btn =
                                            `<button type="submit" onclick="checkUserLogin()"><img id="" src="${heart_img}" class="" alt=""></button>`;
                                    }

                                    favorite_store_data +=
                                        `<div class="nearby-store-listing d-sm-flex justify-content-between mb-4 remove_fav_store_${val.id}" id="favorite_store_listing">
                                    <a href="${url}/store-detail/${val.id}" >
                                        <div class="mr-3">
                                            <p class="t-black mb-0 font-20">${val.first_name} ${val.last_name}</p>
                                            <p class="t-black mb-0 font-14">${val.address}</p>
                                            <p class="t-black mb-0 font-14"><span>Miles ${val.distance}</span> <span class="font-26 line-height-0 px-2">.</span> <span>Open until ${val.start_time} to ${val.end_time}</span></p>
                                        </div>
                                        <div class="nearby-store-listing-right d-flex flex-sm-column justify-content-sm-between justify-content-start mt-4 mt-sm-0">
                                            <div class="text-right mb-sm-3 mr-3 mr-sm-0">
                                                <a href="tel:${val.phone}" title="" class="mr-3"><img src="{{ URL::asset('assets/images/website/call-icon.svg') }}" alt="" class="bg-white border-50 border-white border"></a>` +
                                        fav_btn +
                                        `</div>
                                            <div class="text-right">
                                                ${delivery_type}
                                            </div>
                                        </div>
                                    </a>
                                </div>`;
                                })
                            });
                            $('#favorite-store-listing').empty();
                            $('#favorite-store-listing').append(favorite_store_data);
                        } else {
                            $('#favorite-store-listing').empty();
                            var favorite_store_data = `<div class="text-center justify-content-between mb-4 ">
                                            <p class="t-red mb-0 font-20">No Store Found</p>
                                        </div>`;
                            $('#favorite-store-listing').append(favorite_store_data);
                        }
                        init();
                        google.maps.event.trigger(map, "resize");

                    }
                });
            });
            console.log($('#map-nearby-store-modal'));
            $('#map-nearby-store-modal').on('change', 'input', function() {
                const value = $(this).val();
                const options = $(this).next().find('option');
                const option = options.filter((index, option) => $(option).val() == value);
                console.log(option);
                console.log($(option).attr('data-id'));
                const store_id = $(option).attr('data-id');
                var url = '{{ url('/') }}';
                if (!store_id) return;
                $('#loader').show();
                window.location.href = `${url}/store-detail/${store_id}`;
            })
        });


        function add_remove_fav_store(store_id, add_remove = 0) {

            var add_remove = $('.your_wishlist_store_' + store_id).attr('data-add_remove');
            console.log(`add_remove: `, add_remove);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#loader').show();
            $.ajax({
                type: "post",
                dataType: "json",
                url: '/add_remove_fav_store',
                data: {
                    'store_id': store_id,
                    'add_remove': add_remove
                },
                success: function(returnData) {

                    console.log(returnData);
                    //return false;
                    if (returnData.status == false && returnData.redirect == 'login') {
                        window.location.href = '/login';
                    } else if (returnData.status == false && returnData.redirect == 'account') {
                        window.location.href = '/account?verify=mobile';
                    }


                    if (returnData.status == true) {
                        $('#loader').hide();
                        var url = '{{ url('/') }}';
                        var line_heart_img = url + '/assets/images/website/bd-heart.png';
                        var filled_heart_img = url + '/assets/images/website/saved.png';

                        if (add_remove == 0) {
                            $('.your_wishlist_store_' + store_id).attr('src', line_heart_img);
                            $('.your_wishlist_store_' + store_id).attr('data-add_remove', '1');
                            $('.remove_fav_store_' + store_id).remove();

                            var total_fav_store_count = $("#favorite_store_listing").length;
                            console.log(`total_fav_store_count: `, total_fav_store_count);
                            if (total_fav_store_count == 0) {
                                $('#favorite-store-listing').empty();
                                var favorite_store_data = `<div class="text-center justify-content-between mb-4 ">
                                            <p class="t-red mb-0 font-20">No Store Found</p>
                                        </div>`;
                                $('#favorite-store-listing').append(favorite_store_data);
                            }


                        } else {
                            $('.your_wishlist_store_' + store_id).attr('src', filled_heart_img);
                            $('.your_wishlist_store_' + store_id).attr('data-add_remove', '0');
                        }
                        //Notiflix.Notify.Success(returnData.message);
                    } else {
                        Notiflix.Notify.Failure(returnData.message);
                    }
                }
            });
        }



        //===============================================
        google.maps.event.addDomListener(window, 'load', function() {
            var pickup_places = new google.maps.places.Autocomplete(document.getElementById('autocomplete'), {
                types: ['(regions)'],
                componentRestrictions: {
                    country: "US"
                }
            });

            google.maps.event.addListener(pickup_places, 'place_changed', function() {
                var pickup_place = pickup_places.getPlace();
                var address = pickup_place.address_components;
                var street = city = state = '';
                $.each(address, function(i, val) {
                    if ($.inArray('street_number', val['types']) > -1) {
                        street += val['long_name'];
                    }
                    if ($.inArray('route', val['types']) > -1) {
                        street += ' ' + val['long_name'];
                    }
                    if ($.inArray('locality', val['types']) > -1) {
                        city += val['long_name'];
                    }
                    if ($.inArray('administrative_area_level_1', val['types']) > -1) {
                        state += val['long_name'];
                    }
                });
                $('.latitude').val(pickup_place.geometry.location.lat());
                $('.longitude').val(pickup_place.geometry.location.lng());
            });
        });
        //===============================================

        // var placeSearch, autocomplete;

        // var componentForm = {

        //     administrative_area_level_2: 'long_name',
        //     administrative_area_level_1: 'long_name', 
        // };

        // function initAutocomplete() {    
        //   autocomplete = new google.maps.places.Autocomplete(
        //  (document.getElementById('autocomplete')),
        //   {types: ['(regions)'] , componentRestrictions: {country: "US"} });
        // //   autocomplete.addListener('place_changed', fillInAddress);
        // }

        // function fillInAddress() {
        //   var place = autocomplete.getPlace();
        //    for (var component in componentForm) {
        //       console.log('component: ',component);
        //     document.getElementById(component).value = '';
        //     document.getElementById(component).disabled = false;
        //   }

        //   if (typeof place.address_components != "undefined" || place.address_components != null){


        //   // console.log(place.address_components);
        //       for (var i = 0; i < place.address_components.length; i++) {
        //           for (var j = 0; j < place.address_components[i].types.length; j++){
        //               console.log(place.address_components[i]);
        //               if (place.address_components[i].types[j] == "postal_code") {
        //                   console.log('zipcode: ',place.address_components[i].long_name);
        //                   $('#zipcode').val(place.address_components[i].long_name);
        //               }
        //               if (place.address_components[i].types[j] == "latitude") {
        //                   $('.latitude').val(place.address_components[i].long_name);
        //                   console.log('latitude: ',place.address_components[i].long_name);
        //               }
        //               if (place.address_components[i].types[j] == "longitude") {
        //                   $('.longitude').val(place.address_components[i].long_name);
        //                   console.log('longitude: ',place.address_components[i].long_name);
        //               }

        //           }
        //           $('.latitude').val(place.geometry.location.lat());
        //           $('.longitude').val(place.geometry.location.lng());

        //           var addressType = place.address_components[i].types[0];
        //           console.log('addressType : ',addressType);
        //           if (componentForm[addressType]) {
        //               var val = place.address_components[i][componentForm[addressType]];
        //               console.log('val : ',val);
        //               document.getElementById(addressType).value = val;
        //           }
        //       }        
        //   }
        // }

        // function geolocate() {
        //   if (navigator.geolocation) {
        //     navigator.geolocation.getCurrentPosition(function(position) {
        //       var geolocation = {
        //         lat: position.coords.latitude,
        //         lng: position.coords.longitude,
        //         zip: position.coords.zipcode,
        //       };

        //       // console.log(geolocation);
        //       var circle = new google.maps.Circle({
        //         center: geolocation,
        //         radius: position.coords.accuracy
        //       });
        //       autocomplete.setBounds(circle.getBounds());
        //     });
        //   }
        // }
    </script>
@endsection
