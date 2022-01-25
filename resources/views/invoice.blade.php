<!DOCTYPE html>
<html lang="en">
    <head>
        <title>CANTEENY</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="" name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link href="{{ URL::asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <style type="text/css">
        @media print {
            body{
                background: white !important;
            }
        }
        html,body{
            background: white !important;height: 100%;
            margin-bottom: 0%;
            padding-bottom: 0%;
        }
        .media img{
            margin-left: 150px;
        }
        .media , .media-body{
            margin-bottom: 0%;
            padding-bottom: 0%;
        }
        .page-break {
            page-break-after: always;
        }
        </style>
    </head>
    <body>
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <!-- Logo & title -->
                <div class="clearfix">
                    <div class="float-left">
                        <img src="{{asset('images/logo.png')}}" alt="" height="60">
                    </div>
                    <div class="float-right">
                        <h4 class="m-0 d-print-none">Invoice</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="clearfix">
                        <div class="col-8 float-left">
                            <h4>Canteen Details</h4>
                            <address>
                                Canteeny
                               
                            </address>
                            @if(isset($order['customer']))
                            <h4>Customer Details</h4> 
                            <address>
                                {{$order['customer_name']}}<br>
                                {{$order['customer']['email']}}<br>
                                <abbr title="Phone">Ph:</abbr> {{$order['customer']['phone_formatted']}} <br>
                                <abbr title="School Name">School:</abbr> {{$order['customer']['school_name']}} <br>
                                @if($order['customer']['group'] == '6')
                                <abbr title="Parent Name">Parent Name:</abbr> {{$order['customer']['parent_name']}} <br>
                                @endif
                                @if($order['customer']['group'] == '3' || $order['customer']['group'] == '4' || $order['customer']['group'] == '5' || $order['customer']['group'] == '6')
                                <abbr title="Gender">Gender:</abbr> {{$order['customer']['gender'] == '1' ? 'Male' : 'Female'}} <br>
                                @endif
                                @if($order['customer']['group'] == '5')
                                <abbr title="Department">Department:</abbr> {{$order['customer']['department'] }} <br>
                                @endif
                                @if($order['customer']['group'] == '4' || $order['customer']['group'] == '5' || $order['customer']['group'] == '6')
                                <abbr title="Date Of Birth">DOB:</abbr> {{$order['customer']['dob'] }} <br>
                                @endif
                                @if($order['customer']['group'] == '4' || $order['customer']['group'] == '6')
                                <abbr title="Grade">Grade:</abbr> {{$order['customer']['grade'] }} <br>
                                <abbr title="Class">Class:</abbr> {{$order['customer']['class'] }} <br>
                                @endif
                            </address>
                            @endif
                        </div><!-- end col -->
                        <div class="col-4 float-right" >
                            <p><strong>Order Date: </strong> <span class="float-right"> {{$order['order_on_formatted']}}</span></p>
                            @if($order['current_status'] == 'current')
                            <p><strong>Order Status : </strong><span class="badge badge-warning float-right">In Process</span></p>
                            @elseif($order['current_status'] == 'past') 
                            <p><strong>Order Status : </strong><span class="badge badge-success float-right">Completed</span></p>
                            @else
                            <p><strong>Order Status : </strong><span class="badge badge-danger float-right">Failed</span></p>
                            @endif
                            <p><strong>Order No. : </strong> <span class="float-right">#{{$order['id']}} </span></p>
                            <p><strong>From Date : </strong> <span class="float-right">{{$order['from_formatted']}} </span></p>
                            <p><strong>To Date : </strong> <span class="float-right">{{$order['to_formatted']}} </span></p>
                            <p><strong>Total Days : </strong> <span class="float-right">{{$order['total_days']}} Days</span></p>
                            <p><strong>Per Day Price : </strong> <span class="float-right"> {{$order['price']}} KD </span></p>
                            <p><strong>Price * {{$order['total_days']}} Days  : </strong> <span class="float-right"> {{$order['sub_total']}} KD </span></p>
                            <p><strong>Tax  : </strong> <span class="float-right">{{$order['tax']}} KD </span></p>
                            <p><strong>Total  : </strong> <span class="float-right">{{$order['total']}} KD </span></p>
                            <p><strong>Transaction Id  : </strong> <span class="float-right">{{$order['transaction_id']}} </span></p>
                        </div><!-- end col -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                    <h4>Meal Details</h4>
                    <hr>
                    </div>
                </div>
                <!-- end row -->
                <div class="row">
                    <div class="clearfix">
                        <div class="col-4 float-left">
                            <img src="{{ URL::to($order['meal']['image'])}}" class="img-fluid img-thumbnail" width="150" alt="profile-image">
                        </div>
                        <div class="col-8 float-right">
                            <h4 class="mb-2">{{$order['meal']['name']}} - {{$order['meal']['price']}} KD/day</h4>  
                            <h5 class="mb-5" >{{$order['meal']['description']}}</h6>  
                            <div style="padding-top: 20px; display: block;">
                                @foreach ($order['meal']['category'] as $category)
                                <div class="media" style="padding-top: 10px; margin-top: 10px; ">
                                    <img class="img-fluid avatar-sm" style="object-fit: cover;" src="{{ URL::to($category['category_image'])}}" alt="Generic placeholder image" height="30" width="30">
                                    <div class="media-body" style="padding-top: 0px; margin-top: 0px;">
                                        <p class="text-muted font-13" style="padding-top: 0px; margin-top: 0px;">
                                            <strong>{{$category['category_name']}}</strong> 
                                            <span class="ml-2"> X  </span><span class="ml-2">{{$category['items_number']}}</span>
                                        </p>
                                    </div>
                                </div>  
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="page-break"></div> -->
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table mt-4 table-centered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Day</th>
                                        <th>Products</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($order['order_dates'] as $key => $order_dates)
                                <tr>
                                    <td>{{$order_dates['id']}}</td>
                                    <td>{{$order_dates['date']}}</td>
                                    <td>{{$order_dates['day']}}</td>
                                    <td>
                                    @foreach ($order_dates['order_products'] as $okey => $oproduct)
                                    @if($okey == 0)
                                    <b>{{$oproduct['product_name']}}</b> 
                                    @else
                                    <b>{{ ",".$oproduct['product_name']}}</b> 
                                    @endif
                                    @endforeach
                                    </td>
                                    <td>
                                    @if($order_dates['status'] == 0)
                                        <span class="badge badge-danger">Pending</span>
                                    @elseif($order_dates['status'] == 1)
                                        <span class="badge badge-success">Delivered</span>
                                    @elseif($order_dates['status'] == 2)
                                        <span class="badge badge-danger mr-2">Issue Reported</span>
                                    @elseif($order_dates['status'] == 3)
                                        <span class="badge badge-danger">Expired</span>
                                    @else
                                        <span class="badge badge-info">Changed</span>
                                    @endif
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div> <!-- end table-responsive -->
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

                @if($order['special_instruction'] != "")
                <div class="row">
                    <div class="col-sm-6">
                        <div class="clearfix pt-5">
                            <h6 class="text-muted">Special Instruction:</h6>
                            <small class="text-muted">
                            {{$order['special_instruction']}}
                            </small>
                        </div>
                    </div> <!-- end col -->
                </div>
                @endif
            </div> <!-- end card-box -->
        </div> <!-- end col -->
    </div>
    </body>
</html>