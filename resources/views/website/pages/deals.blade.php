@extends('website.layouts.master')
@section('content')
    @include('website.layouts.nav')
    <section class="best-deals-page my-3 pb-lg-5">
        <div class="container">
            <div class="mb-4 pb-3 border-bottom d-sm-flex justify-content-between align-items-center">
                <h3 class="t-blue d-inline-block mr-3">Best Deals</h3>
            </div>
            @if ($status == true)
                @if (count($data) > 0)
                    @foreach ($data as $key => $value)

                        <div class="row bd-product-listing mb-5">
                            <div class="col-md-12">
                                <div class=" mb-4 d-flex justify-content-between align-items-center">
                                    <h4 class="font-400 t-black d-inline-block mb-0">{{ $value['store_name'] }}</h4>
                                    @if ($value['view_all'] == true)
                                        <a href="{{ url('products/') }}/{{ Crypt::encrypt($value['id']) }}/best-deals"
                                            title="" class="blue-link font-24">
                                            View all <img src="{{ asset('assets/images/website/view-all.jpg') }}" alt=""
                                                class="ml-2">
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <!-- <div class="bd-product-listing__slider"> -->
                            @if (count($value['products']) > 0)
                                @foreach ($value['products'] as $p_key => $p_value)
                                    <div class="col-md-3 mb-4">
                                        <div class="bd-product-item p-3 text-center border-r10 bg-white h-100 relative">
                                            <div class="bd-product-item-image mb-3">
                                                <a
                                                    href="{{ url('/product') . '/' . Crypt::encrypt($value['id']) . '/' . $p_value['id'] }}"><img
                                                        src="{{ $p_value['image'] }}" class="product-list-img" alt="">
                                                </a>
                                            </div>
                                            <a
                                                href="{{ url('/product') . '/' . Crypt::encrypt($value['id']) . '/' . $p_value['id'] }}">
                                                <h6 class="font-20">
                                                    {{ mb_strimwidth($p_value['name'], 0, 23, '...') }}</h6>
                                            </a>
                                            <p class="border-0 font-16 t-grey mb-1">{{ $p_value['quantity'] }}
                                                {{ $p_value['measurement_name'] }}</p>
                                            @php
                                                if (!Auth::guest() && Auth::user()->user_type == '2') {
                                                    $discount = $p_value['business_discount'];
                                                    $previous_price = $p_value['previous_price_business'];
                                                    $current_price = $p_value['current_price_business'];
                                                } else {
                                                    $discount = $p_value['retail_discount'];
                                                    $previous_price = $p_value['previous_price_retail'];
                                                    $current_price = $p_value['current_price_retail'];
                                                }
                                                
                                            @endphp
                                            <p class="mb-0 t-black">
                                                @if ($discount > 0)
                                                    <strike class="t-red"><span
                                                            class="font-400 t-black font-20">${{ $previous_price }}</span></strike>
                                                    <span class="mx-2 t-grey">|</span>
                                                @endif
                                                <span class="font-700 font-24">${{ $current_price }}</span>
                                            </p>


                                            @auth
                                                @if (!Auth::guest())
                                                    @if ($p_value['favorite'])
                                                        <button type="submit" class="bd-heart-btn"
                                                            onclick="add_remove_fav_product({{ $value['id'] }}, {{ $p_value['id'] }}, 0)"><img
                                                                id="your_wishlist_{{ $value['id'] }}_{{ $p_value['id'] }}"
                                                                class="your_wishlist_{{ $value['id'] }}_{{ $p_value['id'] }}"
                                                                src="{{ URL::asset('assets/images/website/saved.png') }}"
                                                                alt="" data-add_remove='0'></button>
                                                    @else
                                                        <button type="submit" class="bd-heart-btn"
                                                            onclick="add_remove_fav_product({{ $value['id'] }}, {{ $p_value['id'] }}, 1)"><img
                                                                id="your_wishlist_{{ $value['id'] }}_{{ $p_value['id'] }}"
                                                                class="your_wishlist_{{ $value['id'] }}_{{ $p_value['id'] }}"
                                                                src="{{ URL::asset('assets/images/website/bd-heart.png') }}"
                                                                alt="" data-add_remove='1'></button>
                                                    @endif
                                                @endif
                                            @else
                                                <button type="button" class="bd-heart-btn"><img onclick="checkUserLogin();"
                                                        src="{{ URL::asset('assets/images/website/bd-heart.png') }}" alt=""
                                                        data-add_remove='1'></button>
                                            @endauth

                                            @if ($discount > 0)
                                                <label class="mb-0 bd-product-offer">{{ $discount }}% <br>OFF</label>
                                            @endif
                                        </div>
                                    </div>

                                @endforeach
                            @endif
                            <!-- </div> -->

                        </div>

                    @endforeach
                @else
                    <div class="col-lg-12 text-center " style="margin-top: 140px;">
                        <img src="{{ URL::asset('assets/images/website/ic_no_products.png') }}" alt="">
                        <div class="mt-3 text-danger">No Data Found</div>
                    </div>
                @endif
            @else
                <div class="col-lg-12 text-center " style="margin-top: 140px;">
                    <img src="{{ URL::asset('assets/images/website/ic_no_products.png') }}" alt="">
                    <div class="mt-3 text-danger">No Data Found</div>
                </div>
            @endif


        </div>
    </section>
@endsection
