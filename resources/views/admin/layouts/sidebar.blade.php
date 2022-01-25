<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">
    <div class="slimscroll-menu">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">
                <li>
                    <a href="{{route('admin.dashboard')}}">
                        <i class="fe-airplay"></i>
                        <!-- <span class="badge badge-success badge-pill float-right">4</span> -->
                        <span> Dashboard </span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.users.index')}}">
                        <i class="fas fa-users"></i>
                        <!-- <span class="badge badge-success badge-pill float-right">4</span> -->
                        <span> User Management </span>
                    </a>
                </li>

                <li>
                    <a href="{{route('admin.banner.index')}}">
                        <i class="fas fa-film"></i>
                        <!-- <span class="badge badge-success badge-pill float-right">4</span> -->
                        <span> Banner Management </span>
                    </a>
                </li>

                <li>
                    <a href="{{route('admin.board.index')}}">
                        <i class="fas fa-suitcase"></i>
                        <span> Board Management </span>
                    </a>
                </li>

                <li>
                    <a href="{{route('admin.store.index')}}">
                        <i class="fas fa-store"></i>
                        <span> Store Management </span>
                    </a>
                </li>

                <li>
                    <a href="{{route('admin.category.index')}}">
                        <i class="fas fa-cocktail"></i>
                        <span> Category Management </span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.family.index')}}">
                        <i class="fas fa-sitemap"></i>
                        <span> Family Management </span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.product.index')}}">
                        <i class="fas fa-glass-cheers"></i>
                        <span> Product Management </span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.inventory.index')}}">
                        <i class="fas fa-cubes"></i>
                        <span> Inventory Management </span>
                    </a>
                </li>

                <li>
                    <a href="{{route('admin.measurement.index')}}">
                        <i class="fas fa-sort-amount-up"></i>
                        <span> Measurement </span>
                    </a>
                </li>

                <li>
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-clipboard"></i> <span>{{ __('Send Mail') }}</span><span class="menu-arrow"></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li>
                            <a href="{{route('admin.mail.store')}}" class="waves-effect"><span>{{__('Store')}}</span></a>
                        </li>
                        <li>
                            <a href="{{route('admin.mail.board')}}" class="waves-effect"><span>{{__('Board')}}</span></a>
                        </li>
                        <li>
                            <a href="{{route('admin.mail.customer')}}" class="waves-effect"><span>{{__('Customer')}}</span></a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-clipboard"></i> <span>{{ __('CMS') }}</span><span class="menu-arrow"></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li>
                            <a href="{{route('admin.terms_conditions')}}" class="waves-effect"><span>{{__('Terms Conditions')}}</span></a>
                        </li>
                        <li>
                            <a href="{{route('admin.privacy_policy')}}" class="waves-effect"><span>{{__('Privacy Policy')}}</span></a>
                        </li>
                        <li>
                            <a href="{{route('admin.interest_bases_ads')}}" class="waves-effect"><span>{{__('Interest Bases Ads')}}</span></a>
                        </li>
                        <li>
                            <a href="{{route('admin.education_out_reach')}}" class="waves-effect"><span>{{__('Education Out Reach')}}</span></a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="{{route('admin.orders.index')}}">
                        <i class="fas fa-clipboard"></i>
                        <span> Order </span>
                    </a>
                </li>

                <!-- <li>
                    <a href="{{route('admin.report.all')}}">
                        <i class="fas fa-file"></i>
                        <span> Daily Orders </span>
                    </a>
                </li> -->

             <!--    <li>
                    <a href="javascript:void(0);" class="waves-effect"><i class="fas fa-exclamation"></i> <span>{{ __('Issue Management') }}</span><span class="menu-arrow"></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li>
                            <a href="{{route('admin.issue.index')}}" class="waves-effect"><span>{{__('Issue Subjects')}}</span></a>
                        </li>
                        <li>
                            <a href="{{route('admin.reported.issue')}}" class="waves-effect"><span>{{__('Reported Issues')}}</span></a>
                        </li>
                    </ul>
                </li> -->
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
                </li>
               
                
            </ul>
        </div>
        <!-- End Sidebar -->
        <div class="clearfix"></div>
    </div>
    <!-- Sidebar -left -->
</div>
<!-- Left Sidebar End
