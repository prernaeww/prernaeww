@extends('website.layouts.master')
@section('content')
    @include('website.layouts.nav')

    <section class="account-page my-5 pb-lg-5">
        <div class="container">
            @include('website.include.flash-message')
            <h3 class="t-blue mb-4 pb-3 border-bottom">Edit Address</h3>
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('address.update', $address->id) }}" method="post" id="form">
                        @csrf
                        @method('PUT')
                        <div class="bg-white border-r10 h-100">
                            <div class="col-xl-8 col-lg-10 mx-auto px-0 mt-5 pb-md-5">
                                <!-- <input type="text" name="" class="enter-zipcode-field" placeholder="Search address"> -->
                                <input type="hidden" id="administrative_area_level_2" class="zipcode" required
                                    data-parsley-errors-container="#google-address-error"
                                    data-parsley-errors-message="Please select the proper street address from google suggestion."
                                    name="zipcode" value="{{ $address->zipcode }}">
                                <input type="hidden" id="administrative_area_level_1" class="state" required
                                    data-parsley-errors-container="#google-address-error"
                                    data-parsley-errors-message="Please select the proper street address from google suggestion."
                                    name="state" value="{{ $address->state }}">
                                <input type="hidden" id="zipcode" class="zipcode" class="zipcode" required
                                    data-parsley-errors-container="#google-address-error"
                                    data-parsley-errors-message="Please select the proper street address from google suggestion."
                                    name="zipcode" value="{{ $address->zipcode }}">
                                <input type="hidden" id="city" class="city" class="city" required
                                    data-parsley-errors-container="#google-address-error"
                                    data-parsley-errors-message="Please select the proper street address from google suggestion."
                                    name="city" value="{{ $address->city }}">
                                <input type="hidden" id="longitude" class="longitude" class="longitude" required
                                    data-parsley-errors-container="#google-address-error"
                                    data-parsley-errors-message="Please select the proper street address from google suggestion."
                                    name="longitude" value="{{ $address->log }}">
                                <input type="hidden" id="latitude" class="latitude" class="latitude" required
                                    data-parsley-errors-container="#google-address-error"
                                    data-parsley-errors-message="Please select the proper street address from google suggestion."
                                    name="latitude" value="{{ $address->lat }}">
                                <input type="hidden" id="address_id" class="address_id" class="address_id" required
                                    data-parsley-errors-container="#google-address-error"
                                    data-parsley-errors-message="Please select the proper street address from google suggestion."
                                    name="address_id" value="{{ $address->id }}">
                                <input type="hidden" id="user_id" class="user_id" class="user_id" required
                                    data-parsley-errors-container="#google-address-error"
                                    data-parsley-errors-message="Please select the proper street address from google suggestion."
                                    name="user_id" value="{{ $address->user_id }}">

                                <div class="form-group my-4 pt-2 mx-md-3 border-bottom-login">
                                    <label class="font-18 mb-0 w-100 t-grey">Your location
                                        <div class="d-flex">
                                            <input type="text" name="address" id="autocomplete" onFocus="geolocate()"
                                                placeholder="ABC Screen,NY,US - 388-345"
                                                class="sign-in-input-field complete_address" required
                                                data-parsley-errors-container="#address-error" autocomplete="off"
                                                value="{{ $address->complete_address }}">
                                            <button type="submit" class="t-blue font-500">Change</button>
                                        </div>
                                    </label>
                                </div>
                                <div id="address-error"></div>
                                <div id="google-address-error"></div>
                                <input type="hidden" name="address_type" value="{{ $address->address_type }}">
                                <div class="mx-md-3">
                                    <p class="t-black mb-3 pt-2 font-16">Tag this location</p>
                                    <ul class="sign-up-as d-flex align-items-center flex-wrap">
                                        <li class="mr-2 mr-sm-4 address_type @if ($address->address_type == '3') active @endif " data-value='3'><a
                                                href="#" title="">Home</a></li>
                                        <li class="mr-2 mr-sm-4 address_type @if ($address->address_type == '1') active @endif " data-value='1'><a
                                                href="#" title="">Office</a></li>
                                        <li class="mr-2 mr-sm-4 address_type @if ($address->address_type == '4') active @endif " data-value='4'><a
                                                href="#" title="">Other</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="mt-5 text-center">
                                <!-- <a href="#" data-dismiss="modal" data-toggle="modal" data-target="#address-modal" title="" class="btn-blue2">Save</a> -->
                                <button type="submit" title="" class="btn-blue2">Save</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </section>
    <script
        src='https://maps.googleapis.com/maps/api/js?key={{ $mapkey }}&libraries=places&callback=initAutocomplete'
        async defer></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js') }}"></script>
    <script>
        $(window).load(function() {
            $('#autocomplete').change(function() {
                $('#latitude').val('');
                $('#longitude').val('');
                $('#zipcode').val('');
                $('.latitude').val('');
                $('.longitude').val('');
                $('.city').val('');
            });
        });
        var placeSearch, autocomplete;

        var componentForm = {

            administrative_area_level_2: 'long_name',
            administrative_area_level_1: 'long_name',

        };



        function initAutocomplete() {
            autocomplete = new google.maps.places.Autocomplete(
                (document.getElementById('autocomplete')), {
                    types: ['geocode'],
                    componentRestrictions: {
                        country: "US"
                    }
                });
            autocomplete.addListener('place_changed', fillInAddress);
        }

        function fillInAddress() {
            var place = autocomplete.getPlace();
            for (var component in componentForm) {
                console.log('component: ', component);
                document.getElementById(component).value = '';
                document.getElementById(component).disabled = false;
            }

            if (typeof place.address_components != "undefined" || place.address_components != null) {


                // console.log(place.address_components);
                for (var i = 0; i < place.address_components.length; i++) {
                    for (var j = 0; j < place.address_components[i].types.length; j++) {
                        console.log(place.address_components[i]);
                        if (place.address_components[i].types[j] == "postal_code") {
                            console.log('zipcode: ', place.address_components[i].long_name);
                            $('#zipcode').val(place.address_components[i].long_name);
                        }
                        if (place.address_components[i].types[j] == "latitude") {
                            $('.latitude').val(place.address_components[i].long_name);
                            console.log('latitude: ', place.address_components[i].long_name);
                        }
                        if (place.address_components[i].types[j] == "longitude") {
                            $('.longitude').val(place.address_components[i].long_name);
                            console.log('longitude: ', place.address_components[i].long_name);
                        }
                        if (place.address_components[i].types[j] == "administrative_area_level_2") {
                            $('.city').val(place.address_components[i].long_name);
                        }

                    }
                    $('.latitude').val(place.geometry.location.lat());
                    $('.longitude').val(place.geometry.location.lng());

                    var addressType = place.address_components[i].types[0];
                    console.log('addressType : ', addressType);
                    if (componentForm[addressType]) {
                        var val = place.address_components[i][componentForm[addressType]];
                        console.log('val : ', val);
                        document.getElementById(addressType).value = val;
                    }
                }
            }
        }

        function geolocate() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var geolocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                        zip: position.coords.zipcode,
                    };

                    // console.log(geolocation);
                    var circle = new google.maps.Circle({
                        center: geolocation,
                        radius: position.coords.accuracy
                    });
                    autocomplete.setBounds(circle.getBounds());
                });
            }
        }

        $(document).ready(function() {
            $('#autocomplete').on("cut copy paste", function(e) {
                e.preventDefault();
            });
        });

        function modal(e) {
            $('#edit-address-modal').modal('show');
            $('.address_id').val($(e).data('address_id'));
            $('.user_id').val($(e).data('user_id'));
            $('.complete_address').val($(e).data('complete_address'));
            $('.zipcode').val($(e).data('zipcode'));
            $('.state').val($(e).data('state'));
            $('.city').val($(e).data('city'));
            $('.latitude').val($(e).data('lat'));
            $('.longitude').val($(e).data('log'));
            $('.address_type').val($(e).data('address_type'));
        }

        function delete_address(address_id) {
            console.log('address_id: ', address_id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#loader').show();
            $.ajax({
                type: "POST",
                url: '/delete_address',
                // dataType: 'json',        
                data: {
                    'address_id': address_id
                }, // serializes the form's elements.
                success: function(data) {
                    $('#loader').hide();
                    console.log(data);
                    $('#delete_address_' + address_id).css('display', 'none');
                }
            });
        }

        $('.address_type').click(function() {
            console.log($(this));
            $('input[name="address_type"]').val($(this).attr('data-value'));
        })

        function your_fav_product(store_id, product_id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#loader').show();
            $.ajax({
                type: "post",
                dataType: "json",
                url: '/my_favorite',
                data: {
                    'store_id': store_id,
                    'product_id': product_id
                },
                success: function(data) {
                    operation = data.operation;
                    $('#loader').hide();
                    var url = '{{ url('/') }}';
                    if (operation == "delete") {
                        var heart_img = url + '/assets/images/website/bd-heart.png';
                        $('#your_wishlist_' + store_id + '_' + product_id).attr('src', heart_img);
                    } else if (operation == "add") {
                        var heart_img = url + '/assets/images/website/saved.png';
                        $('#your_wishlist_' + store_id + '_' + product_id).attr('src', heart_img);
                    }
                }
            });
        }
    </script>
@endsection
