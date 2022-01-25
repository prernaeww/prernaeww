@extends('website.layouts.master')
@section('content')
    @include('website.layouts.nav')
    <section class="my-order-page my-5 pb-lg-5">
        <div class="container">
            <h3 class="t-blue mb-4 pb-3 border-bottom">My Orders</h3>
            <div class="stores-tab row">
                <div class="col-md-8 col-lg-6 col-xl-5 mx-auto">
                    <ul class="nav nav-pills border-r10 bg-white shadow w-100 text-center">
                        <li class="nav-item">
                            <a class="nav-link  @if ($type == 'process') active @endif" href="{{ url('/orders') }}/process">In
                                Process</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if ($type == 'history') active @endif" href="{{ url('/orders') }}/history">History</a>
                        </li>
                    </ul>
                </div>

                @if ($type == 'process')
                    <div class="col-md-12 tab-content mt-5 pt-3">
                        <div class="tab-pane fade show active" id="favorites" role="tabpanel"
                            aria-labelledby="favorites-tab">
                            <div class="row">
                                @if (count($inprocess) > 0)
                                    @foreach ($inprocess as $key => $row)
                                        <div class="col-md-6 mb-4">
                                            @php
                                                $redirect_url = '/order/detail/' . $row['id'];
                                                
                                            @endphp
                                            <div class="bg-white border-r10 shadow p-4" style="cursor: pointer;"
                                                onclick="location.href = '{{ $redirect_url }}';">
                                                <div class="border-bottom pb-2">
                                                    <h4 class="t-blue mb-0">{{ $row['store']['first_name'] }}
                                                        {{ $row['store']['last_name'] }}</h4>
                                                    <p class="font-20 t-black mb-0">{{ $row['store']['address'] }}</p>
                                                </div>
                                                <div class="mt-2">
                                                    <p class="t-grey2 mb-0 font-16">ITEMS</p>
                                                    @php $explode = array(); @endphp
                                                    @foreach ($row['order_products'] as $key => $val)
                                                        @php $explode[] = $val['qty'].' x '.$val['product']['name'] @endphp
                                                    @endforeach
                                                    <p class="t-black mb-3">{{ implode(',', $explode) }}</p>
                                                    <p class="t-grey2 mb-0 font-16">ORDERED ON</p>
                                                    <p class="t-black mb-3">{{ $row['order_on_formatted'] }}</p>
                                                    <div class="d-lg-flex align-items-center justify-content-between">
                                                        <div class="mr-3">
                                                            <p class="t-grey2 mb-0 font-16">TOTAL AMOUNT</p>
                                                            <p class="t-black mb-0">$ {{ $row['total'] }}</p>
                                                        </div>
                                                        <div class="mt-4 mt-lg-0">
                                                            @if ($row['pickup_method'] == '1')
                                                                <span><img
                                                                        src="{{ URL::asset('assets/images/website/pick-up-method1.jpg') }}"
                                                                        alt="" class="mr-3"></span>
                                                                <span>In-Store Pick-Up</span>
                                                            @else
                                                                <span><img
                                                                        src="{{ URL::asset('assets/images/website/pick-up-method2.jpg') }}"
                                                                        alt="" class="mr-3"></span>
                                                                <span>CurbSide Pick-Up</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-lg-12" style="text-align: center;">
                                        <span><img src="{{ URL::asset('assets/images/website/ic_no_orders.png') }}"
                                                alt="" class="" style="margin-left: 40px;"></span>
                                        <h4 class="mt-3 text-danger"><b>No Order History</b></h4>
                                        <p class="mt-3 t-blue">You have not placed any orders yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <nav aria-label="Page navigation example pagination-lg" style="margin-top: 50px;">
                        {{ $inprocess->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                    </nav>
                @else
                    <div class="col-md-12 tab-content mt-5 pt-3">
                        <div class="tab-pane fade show active" id="nearby" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row">
                                @if (count($history) > 0)
                                    @foreach ($history as $key => $row)
                                        <div class="col-md-6 mb-4">
                                            @php
                                                $redirect_url = '/order/detail/' . $row['id'];
                                                
                                            @endphp
                                            <div class="bg-white border-r10 shadow p-4" style="cursor: pointer;"
                                                onclick="location.href = '{{ $redirect_url }}';">
                                                <div class="border-bottom pb-2">
                                                    <h4 class="t-blue mb-0">{{ $row['store']['first_name'] }}
                                                        {{ $row['store']['last_name'] }}</h4>
                                                    <p class="font-20 t-black mb-0">{{ $row['store']['address'] }}
                                                    </p>
                                                </div>
                                                <div class="mt-2">
                                                    <p class="t-grey2 mb-0 font-16">ITEMS</p>
                                                    @php $explode = array(); @endphp
                                                    @foreach ($row['order_products'] as $key => $val)
                                                        @php $explode[] = $val['qty'].' x '.$val['product']['name'] @endphp
                                                    @endforeach
                                                    <p class="t-black mb-3">{{ implode(',', $explode) }}</p>
                                                    <p class="t-grey2 mb-0 font-16">ORDERED ON</p>
                                                    <p class="t-black mb-3">{{ $row['order_on_formatted'] }}</p>
                                                    <div class="d-lg-flex align-items-center justify-content-between">
                                                        <div class="mr-3">
                                                            <p class="t-grey2 mb-0 font-16">TOTAL AMOUNT</p>
                                                            <p class="t-black mb-0">$ {{ $row['total'] }}</p>
                                                        </div>
                                                        <div class="mt-4 mt-lg-0">
                                                            @if ($row['pickup_method'] == '1')
                                                                <span><img
                                                                        src="{{ URL::asset('assets/images/website/pick-up-method1.jpg') }}"
                                                                        alt="" class="mr-3"></span>
                                                                <span>In-InStore Pick-Up</span>
                                                            @else
                                                                <span><img
                                                                        src="{{ URL::asset('assets/images/website/pick-up-method2.jpg') }}"
                                                                        alt="" class="mr-3"></span>
                                                                <span>In-CurbSide Pick-Up</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-lg-12" style="text-align: center;">
                                        <span><img src="{{ URL::asset('assets/images/website/ic_no_orders.png') }}"
                                                alt="" class="" style="margin-left: 40px;"></span>
                                        <h4 class="mt-3 text-danger"><b>No Order History</b></h4>
                                        <p class="mt-3 t-blue">You have not placed any orders yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <nav aria-label="Page navigation example pagination-lg" style="margin-top: 50px;">
                        {{ $history->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                    </nav>
                @endif

            </div>
        </div>
    </section>
@endsection
