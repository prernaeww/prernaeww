@extends('board.layouts.master')
@section('content')
<!-- Start Content-->
<div class="container-fluid">
    
    <!-- start page title -->
    @include('board.include.flash-message')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    
                </div>
                <h4 class="page-title">Dashboard</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    
    <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card-box">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle  border-danger border">
                            <i class="fe-dollar-sign font-22 avatar-title text-danger"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-right">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup">{{$total}}</span></h3>
                            <p class="text-muted mb-1 text-truncate">Total Earnings</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div> <!-- end widget-rounded-circle-->
        </div>

      

        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card-box">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle border-warning border">
                            <i class="fas fa-store font-22 avatar-title text-warning"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-right">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup">{{$store}}</span></h3>
                            <p class="text-muted mb-1 text-truncate">Total Stores</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card-box">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle border-primary  border">
                            <i class="fe-shopping-cart font-22 avatar-title text-primary"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-right">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup">{{$orders_completed}}</span></h3>
                            <p class="text-muted mb-1 text-truncate">Total Completed Orders</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div> <!-- end widget-rounded-circle-->
        </div> 
        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card-box">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle border-primary border">
                            <i class="fe-shopping-cart font-22 avatar-title text-primary"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-right">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup">{{$orders}}</span></h3>
                            <p class="text-muted mb-1 text-truncate">Total Orders</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div> <!-- end widget-rounded-circle-->
        </div> 
    </div>
    <!-- end row -->
    
    </div> <!-- container -->
    @endsection