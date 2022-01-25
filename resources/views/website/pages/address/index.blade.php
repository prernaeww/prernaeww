@extends('website.layouts.master')
@section('content')
    @include('website.layouts.nav')

    <section class="account-page my-5 pb-lg-5">
        <div class="container">
            @include('website.include.flash-message')
            <h3 class="t-blue mb-4 pb-3 border-bottom">Saved Address</h3>
            <div class="row">
                <div class="col-md-12">
                    <div class="bg-white border-r10 h-100">
                        @if (count($data) > 0)
                            @foreach ($data as $key => $value)
                                <div class="saved-address-list p-3 d-flex justify-content-between"
                                    id="delete_address_{{ $value->id }}">
                                    <div class="saved-address-list-detail text-left">
                                        <h5 class="t-blue font-400 mb-0">
                                            @if ($value->address_type == '1')
                                                Work
                                            @elseif($value->address_type == '2')
                                                Hotel
                                            @elseif($value->address_type == '3')
                                                Home
                                            @elseif($value->address_type == '4')
                                                Other
                                            @endif

                                        </h5>
                                        <p class="t-grey font-18 mb-0">{{ $value->complete_address }}</p>
                                    </div>
                                    <div class="relative">
                                        <button type="submit" class="more-button p-2"> <img
                                                src="{{ URL::asset('assets/images/website/more-button.jpg') }}"></button>
                                        <div class="address-edit shadow bg-white border-r20 px-3 py-2">
                                            <a href="{{ route('address.edit', $value->id) }}"
                                                class="border-bottom pb-1 mb-1 edit_address" style="color: black;">Edit</a>
                                            <button type="submit" class="t-red"
                                                onclick='delete_address({{ $value->id }})' ;>Delete</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <nav aria-label="Page navigation example pagination-lg" style="margin-top: 50px;">
                                {{ $data->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                            </nav>

                        @else
                            <div class="col-lg-12" style="text-align: center;">
                                <span><img id="no_address"
                                        src="{{ URL::asset('assets/images/website/ic_no_address.png') }}" alt=""
                                        class="mr-3"></span>
                            </div>
                        @endif
                        <div class="col-lg-12 d-none no_address" style="text-align: center;">
                            <span><img id="" src="{{ URL::asset('assets/images/website/ic_no_address.png') }}" alt=""
                                    class="mr-3"></span>
                        </div>
                        <div class="mt-3 text-center">
                            <a href="{{ route('add_address') }}" title="" class="btn-blue2 mt-2">Add Address</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <script src='https://maps.googleapis.com/maps/api/js?key={{ $mapkey }}&libraries=places&callback=initAutocomplete'
        async defer></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="{{ URL::asset('assets/libs/parsleyjs/parsleyjs.min.js') }}"></script>
    <script>
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

            Notiflix.Confirm.Show(
                'Confirm',
                'Are you sure that you want to delete this record?',
                'Yes',
                'No',
                function() {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $('#loader').show();
                    $.ajax({
                        type: "POST",
                        url: '/delete_address',
                        dataType: 'json',
                        data: {
                            'address_id': address_id
                        }, // serializes the form's elements.
                        success: function(data) {
                            // data = JSON.parse(data);
                            $('#loader').hide();
                            console.log(data);
                            Notiflix.Notify.Success('Deleted');
                            $('#delete_address_' + address_id).removeClass('d-flex');
                            $('#delete_address_' + address_id).addClass('d-none');

                            console.log('data_length: ', data.data.length);
                            if (data.data.length == 0) {
                                var url = '{{ url('/') }}';
                                var no_address_img = url + '/assets/images/website/ic_no_address.png';
                                $('.no_address').removeClass('d-none');
                            }
                        }
                    });
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
