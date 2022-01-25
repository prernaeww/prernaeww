@extends('admin.layouts.master')
@section('content')
<!-- Start Content-->
<div class="container-fluid">
    
    <!-- start page title -->
   <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    {{ Breadcrumbs::render('app_config')}}
                </div>
                <h4 class="page-title">{{$title}}</h4>
            </div>
          <div class="btn-group float-right mt-2 mb-2">
                    <a href="javascript:void(0);" onclick="$('#config_form').submit();" class="btn btn-primary waves-effect waves-light">
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
                <form id="config_form" enctype="multipart/form-data" action="{{route('admin.app.config.submit')}}" method="post" >
                    @csrf
                    <div class="portlet ">
                        <div class="portlet-heading clearfix">
                            <h3 class="portlet-title">
                             {{ __('app maintenance & update settings') }}
                            </h3>
                            <div class="portlet-widgets">
                                <a data-toggle="collapse" data-parent="#accordion1" href="#customer-app"><i class="ion-minus-round"></i></a>
                            </div>
                            
                        </div>
                        <div id="customer-app" class="panel-collapse collapse show">
                            <div class="portlet-body">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">{{ __('Android version')}}</label>
                                        <input type="text" value="<?php echo $app[0]->version; ?>" class="form-control" name="android_version">
                                        <div class="checkbox checkbox-custom m-t-10">
                                            <input id="android_update" name="android_update" type="checkbox"  <?php echo $app[0]->force_update==1?"checked":""; ?>>
                                            <label for="android_update" class="text-danger">
                                                {{__('Compulsory update')}}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label"><?php echo __('IOS version'); ?></label>
                                        <input type="text" value="<?php echo $app[1]->version; ?>" class="form-control" name="ios_version">
                                        <div class="checkbox checkbox-custom m-t-10">
                                            <input id="ios_update" name="ios_update" type="checkbox"  <?php echo $app[1]->force_update==1?"checked":""; ?>>
                                            <label for="ios_update" class="text-danger">
                                                {{__('Compulsory update')}}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">{{ __('Maintenance Mode')}}</label>
                                        <div class="checkbox checkbox-custom m-t-10">
                                            <input id="customer_maintenance" name="maintenance" type="checkbox"  <?php echo $app[1]->maintenance==1?"checked":""; ?>>
                                            <label for="customer_maintenance" class="text-danger">
                                                {{__('Maintenance Mode Enable?')}}
                                            </label>
                                        </div>
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