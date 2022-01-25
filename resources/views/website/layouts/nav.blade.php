<div class="menu relative zindex-1">
    <nav class="navbar navbar-expand-lg navbar-light container py-1">
        <a class="navbar-brand mr-0 mr-sm-3" href="{{ route('home') }}"><img
                src="{{ URL::asset('assets/images/website/logo.svg') }}" alt="" class="img-fluid"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                @if (!Auth::guest())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('orders') }}/process">My Order</a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('deals') }}">Deals</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about-us') }}">About Us</a>
                </li>
                <!-- <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact-us') }}">Contact Us</a>
                    </li> -->
                @if (Auth::guest())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer.login') }}">Login</a>
                    </li>
                @endif
            </ul>
        </div>
        <div class="header-right-section d-flex align-items-center">
            @if (Request::segment(1) == 'products' || Request::segment(1) == 'store-detail')
                <div class="header-search relative ml-3 ml-xl-4">
                    <button id="click" class="white-link d-inline-block">
                        <img src="{{ URL::asset('assets/images/website/search-icon.png') }}" alt="">
                    </button>
                    <form id="search-field" class="search-field1" action="">
                        <input type="text" placeholder="Search here..." list="search_product" id="search_text"
                            autocomplete="off">
                        <datalist id="search_product" class="search-product-listing"
                            style="max-height: 400px; overflow: auto; "></datalist>
                        <!-- <input type="submit" value="search"> -->
                    </form>
                </div>
            @endif
            @if (!Auth::guest())
                <div class="cart-box ml-2 ml-sm-3 ml-xl-4">
                    <a href="{{ route('cart') }}" title="" class="relative"><img
                            src="{{ URL::asset('assets/images/website/shopping-cart.png') }}" alt=""
                            class="mr-sm-3"><span class="cart-box-count " id="cart_product_count">
                            {{ $cart_products_count }}</span> <span class="d-none d-sm-inline-block">
                            Item(s)</span></a>
                </div>

                <div class="notification ml-2 ml-sm-3 ml-xl-4">
                    <div class="dropdown show open-notification" id="open-notification">
                        <a class="dropdown-toggle open-notification" href="javascript:void(0)" role="button"
                            id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img
                                src="{{ URL::asset('assets/images/website/bell.png') }}" alt=""></a>
                        <div class="dropdown-menu shadow bg-white border-r20 d-none" aria-labelledby="dropdownMenuLink">
                            <h6 class="border-bottom mb-0 py-2 text-center">Notifications</h6>
                            <div class="notification-listing px-3 py-2" id="show-notification"
                                style="max-height: 400px; overflow: auto; ">
                                <div class="notification-wrap align-items-center mb-3">
                                    <p class="t-blue mb-0 text-center">No Notifications found</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="account ml-2 ml-sm-3 ml-xl-4">
                    <a href="{{ route('account') }}" title=""><img
                            src="{{ URL::asset('assets/images/website/default-profile.png') }}" alt=""></a>
                </div>
            @endif
        </div>
    </nav>
</div>
