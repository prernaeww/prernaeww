<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">
    <div class="slimscroll-menu">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">
                <li>
                    <a href="{{route('store.dashboard')}}">
                        <i class="fe-airplay"></i>
                        <!-- <span class="badge badge-success badge-pill float-right">4</span> -->
                        <span> Dashboard </span>
                    </a>
                </li>
                


                 <li>
                    <a href="{{route('store.product.index')}}">
                        <i class="fas fa-glass-cheers"></i>
                        <!-- <span class="badge badge-success badge-pill float-right">4</span> -->
                        <span> Product Management </span>
                    </a>
                </li>

                <!-- <li>
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-clipboard"></i> <span>{{ __('Orders Management') }}</span><span class="menu-arrow"></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li>
                            <a href="{{route('admin.order.all')}}" class="waves-effect"><span>{{__('All Orders')}}</span></a>
                        </li>
                        <li>
                            <a href="{{route('admin.order.create')}}" class="waves-effect"><span>{{__('Create Order')}}</span></a>
                        </li>
                    </ul>
                </li> -->
                <li>
                    <a href="{{route('store.orders.index')}}">
                        <i class="fas fa-clipboard"></i>
                        <span> Order </span>
                    </a>
                </li>

            <!--     <li>
                    <a href="{{route('admin.report.all')}}">
                        <i class="fas fa-file"></i>
                        <span> Daily Orders </span>
                    </a>
                </li> -->

               <!--  <li>
                    <a href="javascript:void(0);" class="waves-effect"><i class="fas fa-exclamation"></i> <span>{{ __('Issue Management') }}</span><span class="menu-arrow"></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li>
                            <a href="{{route('admin.issue.index')}}" class="waves-effect"><span>{{__('Issue Subjects')}}</span></a>
                        </li>
                        <li>
                            <a href="{{route('admin.reported.issue')}}" class="waves-effect"><span>{{__('Reported Issues')}}</span></a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-settings"></i> <span>{{ __('Configuration') }}</span><span class="menu-arrow"></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li>
                            <a href="{{route('admin.system.config')}}" class="waves-effect"><span>{{__('System')}}</span></a>
                        </li>
                        <li>
                            <a href="{{route('admin.app.config')}}" class="waves-effect"><span>{{__('Application')}}</span></a>
                        </li>
                    </ul>
                </li> -->
               
                
            </ul>
        </div>
        <!-- End Sidebar -->
        <div class="clearfix"></div>
    </div>
    <!-- Sidebar -left -->
</div>
<!-- Left Sidebar End -->