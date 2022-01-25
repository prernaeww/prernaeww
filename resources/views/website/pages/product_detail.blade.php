@extends('website.layouts.master')
@section('content')
    @include('website.layouts.nav')

    <style type="text/css">
        .like-product-btn img {
            position: absolute;
            right: 0;
            top: 20px;
            width: 24px;
            height: 21px !important;
        }

    </style>

    <section class="product-detail-page mb-5 pb-lg-5">
        <div class="container-fluid">
            @if ($status == true)
                <?php
                //dd($data);
                ?>
                @if (!empty($data) && isset($data))
                    <div class="row">
                        <div class="col-md-12">
                            <div id="content" class="d-lg-flex">
                                <div class="product-detail w-100">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div id="featured_img"
                                                class="product-details-img relative d-flex align-items-center justify-content-center flex-column">
                                                @php
                                                    if (!Auth::guest() && Auth::user()->user_type == '2') {
                                                        $discount = $data['business_discount'];
                                                        $previous_price = $data['previous_price_business'];
                                                        $current_price = $data['current_price_business'];
                                                    } else {
                                                        $discount = $data['retail_discount'];
                                                        $previous_price = $data['previous_price_retail'];
                                                        $current_price = $data['current_price_retail'];
                                                    }
                                                    
                                                @endphp
                                                @if ($discount > 0)
                                                    <label class="mb-0 discount-rate-label">{{ $discount }}% <br>
                                                        OFF</label>
                                                @endif
                                                <img id="img" src="{{ $data['image'] }}" style="max-height: 600px;">
                                                <small class="text-center font-10 mt-3 mb-2" style="">*actual product may
                                                    differ from image</small>
                                                <p class="text-center mb-0 font-14">{{ $data['item_code'] }}</p>
                                                @auth
                                                    @if (!Auth::guest())
                                                        @if ($data['favorite'])
                                                            <button type="submit" class="bd-heart-btn"
                                                                onclick="add_remove_fav_product({{ $store_id }}, {{ $data['id'] }}, 0)"><img
                                                                    id="your_wishlist_{{ $store_id }}_{{ $data['id'] }}"
                                                                    class="your_wishlist_{{ $store_id }}_{{ $data['id'] }}"
                                                                    src="{{ URL::asset('assets/images/website/saved.png') }}"
                                                                    alt="" data-add_remove='0'></button>
                                                        @else
                                                            <button type="submit" class="bd-heart-btn like-product-btn"
                                                                onclick="add_remove_fav_product({{ $store_id }}, {{ $data['id'] }}, 1)"><img
                                                                    id="your_wishlist_{{ $store_id }}_{{ $data['id'] }}"
                                                                    class="your_wishlist_{{ $store_id }}_{{ $data['id'] }}"
                                                                    src="{{ URL::asset('assets/images/website/bd-heart.png') }}"
                                                                    alt="" data-add_remove='1'></button>
                                                        @endif
                                                    @endif
                                                @else
                                                    <button type="button" class="bd-heart-btn like-product-btn"
                                                        onclick="checkUserLogin();"><img
                                                            src="{{ URL::asset('assets/images/website/bd-heart.png') }}"
                                                            alt=""></button>
                                                @endauth

                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-5 mt-md-0">
                                            <p class="add-case-btn font-24 mb-0">{{ $data['family_name'] }}</p>
                                            <h3 class="mb-2 md-5">{{ $data['name'] }}</h3>
                                            <p class="add-case-btn font-24 mb-0">{{ $data['quantity'] }}
                                                {{ $data['measurement_name'] }}</p>

                                            <div class="mt-4 mb-md-5">
                                                <p class="t-blue font-600">Select Quantity</p>
                                                <select name="" class="product-quantity-select" id="product-quantity">
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                </select>
                                            </div>
                                            @php
                                                if (!Auth::guest() && Auth::user()->user_type == '2') {
                                                    $discount = $data['business_discount'];
                                                    $previous_price = $data['previous_price_business'];
                                                    $current_price = $data['current_price_business'];
                                                } else {
                                                    $discount = $data['retail_discount'];
                                                    $previous_price = $data['previous_price_retail'];
                                                    $current_price = $data['current_price_retail'];
                                                }
                                            @endphp
                                            <div class="mt-5 border-top border-bottom py-3 d-lg-flex align-items-center">
                                                <div class="mr-5">
                                                    <h2 class="d-flex align-items-start"><span
                                                            class="font-26">$</span>{{ $current_price }}</h2>
                                                </div>
                                                <div class="mt-4 mt-lg-0">
                                                    <div>
                                                        @auth
                                                            <a href="javascript:void(0)" onclick="addTocart('0');"
                                                                title="Add to cart" class="btn-blue font-22 mb-3">Add to
                                                                cart</a>
                                                        @else
                                                            <a href="javascript:void(0)" onclick="checkUserLogin();"
                                                                title="Add to cart" class="btn-blue font-22 mb-3">Add to
                                                                cart</a>
                                                        @endauth
                                                    </div>
                                                    <div>
                                                        @auth
                                                            <a href="javascript:void(0)" onclick="addTocart('1')"
                                                                title="Add Case"
                                                                class="continue-shopping-btn add-case-btn font-22">Add Case
                                                                <span class="font-18">(12 Units)</span></a>
                                                        @else
                                                            <a href="javascript:void(0)" onclick="checkUserLogin()"
                                                                title="Add Case"
                                                                class="continue-shopping-btn add-case-btn font-22">Add Case
                                                                <span class="font-18">(12 Units)</span></a>
                                                        @endauth
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-md-12 text-center" style="margin-top: 121px;">
                                            <span><img src="{{ URL::asset('assets/images/website/ic_no_products.png') }}"
                                                    alt="" class="mr-3"></span>
                                        </div>
                                    </div>
                @endif
            @else
                <div class="row">
                    <div class="col-md-12 text-center" style="margin-top: 200px;">
                        <span><img src="{{ URL::asset('assets/images/website/ic_no_products.png') }}" alt=""
                                class="mr-3"></span>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <input type="hidden" value="{{ $data['id'] }}" id="product-id">
    <input type="hidden" value="{{ $data['stock'] }}" id="stock">
    <input type="hidden" value="{{ $store_id }}" id="store_id">
    <input type="hidden" value="{{ $data['cart_store_id'] }}" id="cart_store_id">
    <input type="hidden" value="{{ $data['name'] }}" id="data-name">
@endsection
