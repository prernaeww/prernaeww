@extends('admin.layouts.master')
@section('content')                             
<!-- Start Content-->
<div class="container-fluid">
    
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    {{ Breadcrumbs::render('vieworder')}}
                </div>
                <h4 class="page-title">Invoice</h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 

    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <!-- Logo & title -->
                <div class="clearfix">
                    <div class="float-left">
                        <img src="assets/images/logo-dark.png" alt="" height="20">
                    </div>
                    <div class="float-right">
                        <h4 class="m-0 d-print-none">Invoice</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mt-3">
                            <img src="{{url('assets/images/invoicelogo.png')}}" style="height:10px;width:10px;height: 79px;width: 120px;margin-top:-58px;margin-bottom: 31px;">
                        </div>  

                    </div><!-- end col -->
                    <div class="col-md-4 offset-md-2">

                        <div class="mt-3 float-right">
                            <p class="m-b-10"><strong>Order No. : </strong> <span class="float-right">#{{$order->id}} </span></p>
                            <p class="m-b-10"><strong>Order Date Time : </strong> <span class="float-right"> &nbsp;&nbsp;&nbsp;&nbsp; {{$order->order_on_formatted}}</span></p>
                            <p class="m-b-10"><strong>Order Status :  </strong> 
                                <span class="float-right"><span class="badge badge-primary">{{$order->current_status}}</span></span>
                            </p>
                            @if($order->reached != Null)
                            <p class="m-b-10"><strong>Reached At : </strong> <span class="float-right">{{$order->reached_on_formatted}} </span></p>
                            @else
                            <p class="m-b-10"></p>
                            @endif
                            @if($order->pickup_method=='1')
                            <p class="m-b-10"><strong>PickUp Type : </strong> <span class="float-right">Instore </span></p>
                            @else($order->pickup_method=='2')
                            <p class="m-b-10"><strong>PickUp Type : </strong> <span class="float-right">CurbSide </span></p>
                            @endif
                            @if($order->user_type == '0')
                            <p class="m-b-10"><strong>User Type : </strong> <span class="float-right">Retailer  </span></p>
                            @else($order->user_type=='1')
                            <p class="m-b-10"><strong>User Type : </strong> <span class="float-right">Business  </span></p>
                            @endif
                             <!-- <p class="m-b-10"><strong>Store Name : </strong> <span class="float-right">{{$order->store_name}}  </span></p> -->
                            <p class="m-b-10"><strong>Customer Name : </strong> <span class="float-right"><a href="{{route('admin.user.show',$order->user_id)}}" target="_blank">{{$order->customer_name}} </a> </span></p>

                        </div>
                    </div><!-- end col -->
                </div>
                <!-- end row -->

                <div class="row mt-3">
                    <div class="col-sm-6">
                        @if($order->vehicle_description!='')
                        <strong>Vehicle Description</strong>
                        <address>
                            {{$order->vehicle_description}}<br>
                        </address>
                        @else
                        <h6></h6>
                        <address>
                            <br>
                        </address>
                        @endif

                    </div> <!-- end col -->

                    <div class="col-sm-6 text-right">
                        <strong>Store </strong>
                        <address>
                            <a href="{{route('admin.store.show',$order->store_id)}}" target="_blank">{{$order->store_name}}</a> <br>
                            <a href="tel:{{$order->store->phone_formatted}}">Call {{$order->store->phone_formatted}}</a>
                            <br>
                            {{$order->store_address}}
                        </address>
                    </div> <!-- end col --> 
                   
                </div> 
                <!-- end row -->
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table mt-4 table-centered">
                                <thead>
                                <tr><th>Image</th>
                                    <th>Products</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th class="text-right">Total</th>
                                </tr></thead>

                                <tbody>
                                @if(isset($products) && count($products) > 0)
                                @foreach ($products as $products)
                                <tr>
                                    <td><img src="{{$products->product->image}}" style="height: 72px;object-fit: scale-down;"></td>
                                      <td>{{$products->product->name}}<br>{{$products->product->quantity}}&nbsp;{{$products->product->measurement_name}}</td>
                                    <td>{{$products->qty}}</td>
                                    <td>${{$products->price}}</td>
                                    <td class="text-right">${{number_format((float)$products->price * $products->qty, 2, '.', '')}}</td>
                                </tr>
                                @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div> <!-- end table-responsive -->
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-sm-6">
                        <div class="clearfix pt-5">
                            <strong class="d-block">Name:</strong>

                            <small class="text-muted">
                                {{$order->name}}
                            </small>
                            <br>
                            <strong class="d-block">Number:</strong>

                            <small class="text-muted">
                                {{$order->number}}
                            </small>
                            <br>
                            @if($order->pickup_notes!='')
                            <strong class="d-block">Pickup Notes:</strong>

                            <small class="text-muted">
                                {{$order->pickup_notes}}
                            </small>
                            @else
                            <strong class="text-muted"></strong>
                            <small class="text-muted">
                                
                            </small>
                            @endif
                        </div>
                    </div> <!-- end col -->
                    <div class="col-sm-6">
                        <div class="float-right">
                            <p><b>Sub total:</b> <span class="float-right">${{$order->sub_total}}</span></p>
                            <p><b>Tax:</b> <span class="float-right">${{$order->tax}}</span></p>
                            
                            <h3>${{$order->total}} USD</h3>
                        </div>
                        <div class="clearfix"></div>
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

                <div class="mt-4 mb-1">
                    <div class="text-right d-print-none">
                        <a href="javascript:window.print()" class="btn btn-primary waves-effect waves-light"><i class="mdi mdi-printer mr-1"></i>Print</a>
                       
                    </div>
                </div>
            </div> <!-- end card-box -->
        </div> <!-- end col -->
    </div>
    <!-- end row --> 
    
</div> <!-- container -->
@endsection