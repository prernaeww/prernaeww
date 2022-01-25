<!-- Topbar Start -->
<div class="navbar-custom">
    <ul class="list-unstyled topnav-menu float-right mb-0">

        <li class="dropdown notification-list"> 
            <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <img src="{{ url(Auth::user()->profile_picture) }}" alt="user-image" class="rounded-circle">
                <span class="pro-user-name ml-1 text-uppercase">
                    {{ Auth::user()->first_name }} <i class="mdi mdi-chevron-down"></i> 
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                <!-- item-->
                <div class="dropdown-header noti-title">
                    <h6 class="text-overflow m-0">Welcome!</h6>
                </div>

                <!-- item-->
                <a href="{{ route('store.profile') }}" class="dropdown-item notify-item">
                    <i class="fe-user"></i>
                    <span>My Account</span>
                </a>

                <!-- item-->
                <!-- <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="fe-settings"></i>
                    <span>Settings</span>
                </a> -->

                <div class="dropdown-divider"></div>

                <!-- item-->
                <form method="POST" action="{{ route('store.logout') }}">
                    @csrf

                    <x-dropdown-link :href="route('store.logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                    <i class="fe-log-out"></i>
                        {{ __('Log out') }}
                    </x-dropdown-link>
                </form>
                

            </div>
        </li>
       <!--  <li class="dropdown notification-list">
                        <a href="javascript:void(0);" class="nav-link right-bar-toggle waves-effect waves-light">
                            <i class="fe-settings noti-icon"></i>
                        </a>
                    </li> -->
    </ul>

    <!-- LOGO -->
    <div class="logo-box">
        <a href="#" class="logo text-center">
            <span class="logo-lg">
                <img src="{{asset('images/logo.png')}}" alt="" height="70">
                <!-- <span class="logo-lg-text-light">MYWEGON</span> -->
            </span>
            <span class="logo-sm">
                <!-- <span class="logo-sm-text-white">C</span> -->
                <img src="{{asset('images/logo.png')}}" alt="" height="40">
            </span>
        </a>
    </div>

    <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
        <li>
            <button class="button-menu-mobile waves-effect waves-light">
                <i class="fe-menu"></i>
            </button>
        </li>
    </ul>
</div>
<!-- end Topbar -->