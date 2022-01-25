@extends('website.layouts.master')
@section('content')
    @include('website.layouts.nav')
    <section class="cart-page my-5 pb-lg-5">
        <div class="container">
            @include('website.include.flash-message')
            @if (isset($status) && $status == true)
                <div class="mb-5 pb-3 border-bottom d-flex justify-content-between align-items-center" id="list-div-main">
                    <h3 class="t-blue font-700">Cart <span class="font-400 font-16"
                            id="cart-count">({{ $data['cart_products_count'] }} Items)</span></h3>
                </div>
                <div class="row" id="list-div">
                    <div class="col-lg-6 cart-product-list-wrap">

                        @if (isset($data['cart_products']) && !empty($data['cart_products']))
                            @foreach ($data['cart_products'] as $value)
                                <div class="cart-product-list d-sm-flex align-items-center"
                                    id="cart_product_list{{ $value['id'] }}">
                                    <div
                                        class="cart-product-list-image mr-sm-4 border-r10 bg-white d-flex align-items-center justify-content-center">
                                        <img src="{{ url($value['product']['image']) }}" alt="">
                                    </div>
                                    <div class="cart-product-list-detail w-100 mt-4 mt-sm-0">
                                        <div class="bg-white d-flex align-items-center justify-content-center float-right">
                                            <button id="remove_cart_item{{ $value['id'] }}"
                                                onclick="delete_cart(this);return false;" data-id="{{ $value['id'] }}"
                                                data-token="{{ csrf_token() }}"><i class="fa fa-trash text-danger"
                                                    style="font-size:26px;" aria-hidden="true"></i></button>
                                        </div>

                                        <!-- <label class="mb-3"><a href="">delete</a></label> -->
                                        <!-- <button type="submit" class="bd-heart-btn" tabindex="0">d</button> -->

                                        <h5 class="font-700">{{ $value['product']['name'] }}</h5>
                                        <p class="mb-3 t-grey font-16">{{ $value['product']['quantity'] }}
                                            {{ $value['product']['measurement_name'] }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h4 class="t-blue d-inline-block m-0 cart_product_price_{{ $value['id'] }}">
                                                ${{ $value['product_total'] }}</h4>
                                            <div class="quantity-select">
                                                <div class="counter d-flex align-items-center">
                                                    <button class="down" id="btn_down_{{ $value['id'] }}"
                                                        onclick="quantityPlusMinus({{ $value['id'] }}, '0')" @if ($value['qty'] == 1)
                            @endif>-</button>
                            <input type="text" value="{{ $value['qty'] }}" id="input-quantity{{ $value['id'] }}"
                                class="quantity_plus_minus_{{ $value['id'] }}">
                            <button class="up"
                                onclick="quantityPlusMinus({{ $value['id'] }}, '1')">+</button>
                    </div>
                </div>
        </div>
        </div>
        </div>
        @endforeach
        @endif

        </div>
        <div class="col-xl-2 col-lg-1 d-none d-lg-block">

        </div>
        <div class="col-xl-4 col-lg-5 mt-5 mt-lg-0">
            <div class="bg-darkblue py-4 px-3 px-sm-4 border-r10">
                <h4 class="text-white mb-4">Order Summary</h4>

                @if (isset($data['cart_products']) && !empty($data['cart_products']))
                    @foreach ($data['cart_products'] as $value)
                        <div class="mb-2 d-flex justify-content-between align-items-center"
                            id="product_details{{ $value['id'] }}">
                            <p class="text-white mb-0"><span
                                    class="quantity_plus_minus_{{ $value['id'] }}">{{ $value['qty'] }}</span> x
                                {{ $value['product']['name'] }}</p>
                            <p class="text-white mb-0 ml-2 cart_product_price_{{ $value['id'] }}">
                                ${{ $value['product_total'] }}</p>
                        </div>
                    @endforeach
                @endif
                <div class="border-bottom my-3"></div>
                <div class="d-flex justify-content-between align-items-center">
                    <p class="text-white mb-0">Subtotal</p>
                    <p class="text-white mb-0 ml-2" id="sub_total">${{ $data['sub_total'] }}</p>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <p class="text-white mb-0">Tax</p>
                    <p class="text-white mb-0 ml-2" id="tax">${{ $data['tax'] }}</p>
                </div>
                <div class="mt-2 d-flex justify-content-between align-items-center">
                    <p class="text-white mb-0 font-24 font-700">Total</p>
                    <p class="text-white mb-0 font-24 font-700 ml-2" id="total">${{ $data['total'] }}</p>
                </div>
                <div class="text-center mt-5">
                    <a href="javascript:void(0)" title="" onclick="checkoutSuccess()" class="btn-white2 w-100">Checkout</a>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('home') }}" title="" class="continue-shopping-btn w-100">Continue Shopping</a>
            </div>
        </div>
        </div>
    @else
        <div class="text-center col-lg-12">
            <img class="mt-4" src="{{ url('assets/images/website/ic_no_cart_items.png') }}">
            <br>
            <br>
            <h3 class="t-blue font-400 text-center text-danger">oops! Your cart is empty</h3>
            <h5 class="t-blue">Looks like you haven't added anything to your cart yet.</h5>

            <a href="{{ route('home') }}" class="btn-blue2 mt-3">Shop Now</a>
        </div>
        @endif
        <div id="no-data-div"></div>
        </div>
    </section>
@endsection
@section('script')
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
    <script type="text/javascript">
        function quantityPlusMinus(cart_product_id, update_qty) {

            $('#loader').show();
            $.ajax({

                type: "post",
                url: '/qty_update',
                dataType: 'json',
                data: {
                    'cart_product_id': cart_product_id,
                    'update_qty': update_qty,
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {

                    $('#loader').hide();

                    if (response.status) {

                        Notiflix.Notify.Success(response.message);
                        $('.quantity_plus_minus_' + cart_product_id).text(response.data.cart_product.qty);
                        $('.quantity_plus_minus_' + cart_product_id).val(response.data.cart_product.qty);
                        $('.cart_product_price_' + cart_product_id).html('$' + response.data.cart_product
                            .product_total);
                        $('#sub_total').html('$' + response.data.sub_total);
                        $('#tax').html('$' + response.data.tax);
                        $('#total').html('$' + response.data.total);
                        $('#cart_product_count').text(response.data.cart_products_count + ' ');
                        $('#btn_down_' + cart_product_id).attr('disabled', false);
                        if (response.data.cart_product.qty == 1) {

                            $('#btn_down_' + cart_product_id).attr('disabled', true);
                        }

                    } else {
                        Notiflix.Notify.Failure(response.message);
                    }


                },
                error: function(error) {}
            });

        }

        function checkoutSuccess() {

            $('#loader').show();
            $.ajax({
                type: "get",
                url: '/cart_verify',
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {
                    console.log(response);
                    //return false;

                    if (response.status == true) {
                        //Notiflix.Notify.Success(response.message);
                        window.location.href = "/checkout";
                    } else {

                        if (response.cart_available == true) {
                            if (response.store_deactivated == false) {

                                if (response.product_deleted == true) {
                                    location.reload();

                                } else {
                                    $('#loader').hide();
                                    Notiflix.Notify.Failure(response.message);
                                    return false;
                                }

                            } else {
                                window.location.href = "/home";
                            }
                        } else {

                            window.location.href = "/home";
                        }
                    }



                }

            })
        }

        function delete_cart(e) {

            var id = $(e).data('id');
            var token = $(e).data('token');

            Notiflix.Confirm.Show(
                'Confirm',
                'Are you sure that you want to delete this record?',
                'Yes',
                'No',
                function() {
                    $('#loader').show();
                    $.ajax({
                        url: 'cart_delete/' + id,
                        type: 'post',
                        dataType: "JSON",
                        data: {
                            "cart_product_id": id,
                            "_token": token
                        },
                        success: function(returnData) {

                            $('#loader').hide();
                            console.log(returnData.status);
                            if (returnData.status == true) {
                                Notiflix.Notify.Success('Deleted');
                                $('#cart_product_list' + id).remove();
                                $('#product_details' + id).remove();
                                $('#sub_total').html('$' + returnData.data.sub_total);
                                $('#tax').html('$' + returnData.data.tax);
                                $('#total').html('$' + returnData.data.total);
                                $('#cart_product_count').html(returnData.data.cart_products_count);
                                $('#cart-count').html('(' + returnData.data.cart_products_count +
                                    ' Items)');
                                if (returnData.data.cart_products_count == 0) {
                                    $('#list-div').remove();
                                    $('#list-div-main').remove();
                                    var havnt = "haven't";
                                    $('#no-data-div').html(
                                        '<div class="text-center col-lg-12"><img class="mt-4" src="{{ url('assets/images/website/ic_no_cart_items.png') }}"><br><br><h3 class="t-blue font-400 text-center text-danger">oops! Your cart is empty</h3><h5 class="t-blue">Looks like you ' +
                                        havnt +
                                        ' added anything to your cart yet.</h5><a href="{{ route('home') }}" class="btn-blue2 mt-3">Shop Now</a></div>'
                                    );
                                }
                            } else {
                                Notiflix.Notify.Failure(returnData.message);
                            }

                        }
                    });
                });
        }
    </script>
@endsection
