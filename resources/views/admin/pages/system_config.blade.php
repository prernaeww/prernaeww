@extends('admin.layouts.master')
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        {{ Breadcrumbs::render('config') }}
                    </div>
                    <h4 class="page-title">{{ $title }}</h4>
                </div>
                <div class="btn-group float-right mt-2 mb-2">
                    <a href="javascript:void(0);" onclick="$('#config_form').submit();"
                        class="btn btn-primary waves-effect waves-light">
                        <span class="btn-label">
                            <i class="fa fa-file-o"></i>
                        </span>
                        Save
                    </a>
                </div>
            </div>
        </div>
        @include('admin.include.flash-message')
        <!-- end page title -->

        <div class="row">
            <div class="col-sm-12">
                <form id="config_form" enctype="multipart/form-data" action="{{ route('admin.system.config.submit') }}"
                    method="POST">
                    @csrf
                    <div class="portlet">
                        <div class="portlet-heading clearfix">
                            <h3 class="portlet-title">
                                {{ __('General Settings') }}
                            </h3>
                            <div class="portlet-widgets">
                                <a data-toggle="collapse" data-parent="#accordion1" href="#tab-general"><i
                                        class="ion-minus-round"></i></a>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div id="tab-general" class="panel-collapse collapse show">
                            <div class="portlet-body">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">{{ __('Phone') }}</label>
                                        <input type="text" value="{{ CommonHelper::ConfigGet('phone ') }}"
                                            class="form-control" name="phone">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">{{ __('Twitter') }}</label>
                                        <input type="text" value="{{ CommonHelper::ConfigGet('twitter') }}"
                                            class="form-control" name="twitter">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">{{ __('Instagram') }}</label>
                                        <input type="text" value="{{ CommonHelper::ConfigGet('instagram') }}"
                                            class="form-control" name="instagram">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">{{ __('Facebook') }}</label>
                                        <input type="text" value="{{ CommonHelper::ConfigGet('facebook') }}"
                                            class="form-control" name="facebook">
                                    </div>
                                    <div class="form-group d-none">
                                        <label class="form-control-label">{{ __('Snapchat') }}</label>
                                        <input type="text" value="{{ CommonHelper::ConfigGet('snapchat') }}"
                                            class="form-control" name="snapchat">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">{{ __('Tax') }}</label>
                                        <input type="text" value="{{ CommonHelper::ConfigGet('tax') }}"
                                            class="form-control perc discount" name="tax">
                                        <!-- <input type="text" name="tax" parsley-trigger="change" value="{{ old('tax') }}" value="{{ CommonHelper::ConfigGet('tax') }}"  placeholder="Enter tax" class="form-control perc discount" id="name" min="0" max="100"> -->
                                    </div>



                                    <!--   <div class="form-group">
                                                                        <label class="form-control-label">{{ __('Order replacement before hour') }}</label>
                                                                        <input type="number" value="{{ CommonHelper::ConfigGet('order_replacement_before_hour') }}" class="form-control" name="order_replacement_before_hour">
                                                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="portlet ">
                        <div class="portlet-heading clearfix">
                            <h3 class="portlet-title">
                                {{ __('Notification & google map settings') }}
                            </h3>
                            <div class="portlet-widgets">
                                <a data-toggle="collapse" data-parent="#accordion1" href="#tab-notify-google"><i
                                        class="ion-minus-round"></i></a>
                            </div>

                        </div>
                        <div id="tab-notify-google" class="panel-collapse collapse show">
                            <div class="portlet-body">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">{{ __('FCM key') }}</label>
                                        <input type="text" value="{{ CommonHelper::ConfigGet('fcm_key') }}"
                                            class="form-control" name="fcm_key">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">{{ __('Google map key') }}</label>
                                        <input type="text" value="{{ CommonHelper::ConfigGet('map_key') }}"
                                            class="form-control" name="map_key">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="portlet ">
                        <div class="portlet-heading clearfix">
                            <h3 class="portlet-title">
                                {{ __('Email settings') }}
                            </h3>
                            <div class="portlet-widgets">
                                <a data-toggle="collapse" data-parent="#accordion1" href="#tab-email"><i
                                        class="ion-minus-round"></i></a>
                            </div>

                        </div>
                        <div id="tab-email" class="panel-collapse collapse show">
                            <div class="portlet-body">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">{{ __('From email') }}</label>
                                        <input type="text" value="{{ CommonHelper::ConfigGet('from_email') }}"
                                            class="form-control" name="from_email">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">{{ __('From name') }}</label>
                                        <input type="text" value="{{ CommonHelper::ConfigGet('from_name') }}"
                                            class="form-control" name="from_name">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">{{ __('Smtp host') }}</label>
                                        <input type="text" value="{{ CommonHelper::ConfigGet('smtp_host') }}"
                                            class="form-control" name="smtp_host">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">{{ __('Smtp port') }}</label>
                                        <input type="text" value="{{ CommonHelper::ConfigGet('smtp_port') }}"
                                            class="form-control" name="smtp_port">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">{{ __('Smtp user') }}</label>
                                        <input type="text" value="{{ CommonHelper::ConfigGet('smtp_user') }}"
                                            class="form-control" name="smtp_user">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">{{ __('Smtp password') }}</label>
                                        <input type="text" value="{{ CommonHelper::ConfigGet('smtp_pass') }}"
                                            class="form-control" name="smtp_pass">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="portlet ">
                        <div class="portlet-heading clearfix">
                            <h3 class="portlet-title">
                                {{ __('Bridge Pay Payment settings') }}
                            </h3>
                            <div class="portlet-widgets">
                                <a data-toggle="collapse" data-parent="#accordion1" href="#tab-email"><i
                                        class="ion-minus-round"></i></a>
                            </div>

                        </div>
                        <div id="tab-email" class="panel-collapse collapse show">
                            <div class="portlet-body">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">{{ __('Private Key') }}</label>
                                        <input type="text" value="{{ CommonHelper::ConfigGet('private_key') }}"
                                            class="form-control" name="private_key" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">{{ __('Bridge Pay User') }}</label>
                                        <input type="text" value="{{ CommonHelper::ConfigGet('bridge_pay_user') }}"
                                            class="form-control" name="bridge_pay_user" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">{{ __('Bridge Pay Password') }}</label>
                                        <input type="text" value="{{ CommonHelper::ConfigGet('bridge_pay_password') }}"
                                            class="form-control" name="bridge_pay_password" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div> <!-- container -->
@endsection
