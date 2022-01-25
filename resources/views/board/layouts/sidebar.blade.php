<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">
    <div class="slimscroll-menu">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">
                <li>
                    <a href="{{route('board.dashboard')}}">
                        <i class="fe-airplay"></i>
                        <!-- <span class="badge badge-success badge-pill float-right">4</span> -->
                        <span> Dashboard </span>
                    </a>
                </li>

                <li>
                    <a href="{{route('board.store.index')}}">
                        <i class="fas fa-store"></i>
                        <span> Store Management </span>
                    </a>
                </li>


                <li>
                    <a href="{{route('board.banner.index')}}">
                        <i class="fas fa-film"></i>
                        <span> Banner Management </span>
                    </a>
                </li>

                <li>
                    <a href="{{route('board.category.index')}}">
                        <i class="fas fa-cocktail"></i>
                        <span> Category Management </span>
                    </a>
                </li>
                <li>
                    <a href="{{route('board.product.index')}}">
                        <i class="fas fa-glass-cheers"></i>
                        <span> Product Management </span>
                    </a>
                </li>
              
                <!-- <li>
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-clipboard"></i> <span>{{ __('Orders Management') }}</span><span class="menu-arrow"></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li>
                            <a href="{{route('board.order.all')}}" class="waves-effect"><span>{{__('All Orders')}}</span></a>
                        </li>
                        <li>
                            <a href="{{route('board.order.inprocess')}}" class="waves-effect"><span>{{__('Current Orders')}}</span></a>
                        </li>
                        <li>
                            <a href="{{route('board.order.completed')}}" class="waves-effect"><span>{{__('Completed Orders')}}</span></a>
                        </li>
                        <li>
                            <a href="{{route('board.order.fail')}}" class="waves-effect"><span>{{__('Failed Orders')}}</span></a>
                        </li>
                    </ul>
                </li> -->
                  <li>
                    <a href="{{route('board.orders.index')}}">
                        <i class="fas fa-clipboard"></i>
                        <span>  Order </span>
                    </a>
                </li>
               <!--  <li>
                    <a href="{{route('board.report.all')}}">
                        <i class="fas fa-file"></i>
                        <span> Daily Orders </span>
                    </a>
                </li> -->
            </ul>
        </div>
        <!-- End Sidebar -->
        <div class="clearfix"></div>
    </div>
    <!-- Sidebar -left -->
</div>
<!-- Left Sidebar End -->