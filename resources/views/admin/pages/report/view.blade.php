@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                {{ Breadcrumbs::render($breadcrumb_name)}}
                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
            </div>
		</div>
	</div>
    @include('admin.include.flash-message')
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
                    <div class="col-md-6">
                    <h4>Canteen Details</h4>
                    <address>Canteeny</address>
                       <!--  <address>
                            {{$order['canteen_name']}}<br>
                            {{$order['canteen']['email']}}<br>
                            <abbr title="Phone">Ph:</abbr> {{$order['canteen']['phone_formatted']}}
                        </address> -->
                    @if(isset($order['customer']))
                        <h4>Customer Details</h4> 
                        <address>
                            {{$order['customer_name']}}<br>
                            {{$order['customer']['email']}}<br>
                            @if($order['customer']['phone_formatted'] != "")
                            <abbr title="Phone">Ph:</abbr> {{$order['customer']['phone_formatted']}} 
                            @endif
                            <br>
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
                            <abbr title="Grade">Grade:</abbr> {{$order['customer']['grade_name'] }} <br>
                            <abbr title="Class">Class:</abbr> {{$order['customer']['class'] }} <br>
                            @endif
                        </address>
                    @endif
                    </div><!-- end col -->
                    <div class="col-md-4 offset-md-2">
                        <div class="mt-3 float-right">
                            <p class="m-b-10"><strong>Order Date : </strong> <span class="float-right"> &nbsp;&nbsp;&nbsp;&nbsp; {{$order['order_on_formatted']}}</span></p>
                            @if($order['current_status'] == 'current')
                            <p class="m-b-10"><strong>Order Status : </strong> <span class="float-right"><span class="badge badge-warning">In Process</span></span></p>
                            @elseif($order['current_status'] == 'past') 
                            <p class="m-b-10"><strong>Order Status : </strong> <span class="float-right"><span class="badge badge-success">Completed</span></span></p>
                            @else
                            <p class="m-b-10"><strong>Order Status : </strong> <span class="float-right"><span class="badge badge-danger">Failed</span></span></p>
                            @endif
                            <p class="m-b-10"><strong>Order No. : </strong> <span class="float-right">#{{$order['id']}} </span></p>
                            <p class="m-b-10"><strong>From Date : </strong> <span class="float-right">{{$order['from_formatted']}} </span></p>
                            <p class="m-b-10"><strong>To Date : </strong> <span class="float-right">{{$order['to_formatted']}} </span></p>
                            <p class="m-b-10"><strong>Total Days : </strong> <span class="float-right">{{$order['total_days']}} Days</span></p>
                            <p class="m-b-10"><strong>Per Day Price : </strong> <span class="float-right"> {{$order['price']}} KD </span></p>
                            <p class="m-b-10"><strong>Price * {{$order['total_days']}} Days  : </strong> <span class="float-right"> {{$order['sub_total']}} KD </span></p>
                            <p class="m-b-10"><strong>Tax  : </strong> <span class="float-right">{{$order['tax']}} KD </span></p>
                            <p class="m-b-10"><strong>Total  : </strong> <span class="float-right">{{$order['total']}} KD </span></p>
                            <p class="m-b-10"><strong>Transaction Id  : </strong> <span class="float-right">{{$order['transaction_id']}} </span></p>
                        </div>
                    </div><!-- end col -->
                </div>
                <div class="row">
                    <div class="col-lg-12">
                    <h4>Meal Details</h4>
                    <hr>
                    </div>
                </div>
                <!-- end row -->
                <div class="row">
                    <div class="col-lg-3">
                        <div>
                            <img src="{{ URL::to($order['meal']['image'])}}" class="img-fluid img-thumbnail" width="200" alt="profile-image">
                        </div> 
                    </div> 
                    <div class="col-9">
                        <div>
                            <h4 class="mb-2">{{$order['meal']['name']}} - {{$order['meal']['price']}} KD/day</h4>  
                            <h6 class="mb-3">{{$order['meal']['description']}}</h6>  
                            
                            <div class="text-left mt-1">
                                @foreach ($order['meal']['category'] as $category)
                                <div class="media mb-2">
                                    <img class="img-fluid avatar-sm d-flex align-self-end rounded mr-2" style="object-fit: cover;" src="{{ URL::to($category['category_image'])}}" alt="Generic placeholder image" height="64">
                                    <div class="media-body">
                                    <p class="text-muted mb-2 font-13"><strong>{{$category['category_name']}}</strong> <span class="ml-2"> X  </span><span class="ml-2">{{$category['items_number']}}</span></p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table mt-4 table-centered">
                                <thead>
                                <tr><th>#</th>
                                    <th>Date</th>
                                    <th>Day</th>
                                    <th>Products</th>
                                    <th>Status</th>
                                </tr></thead>
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
                                    <b>{{ " , " .$oproduct['product_name']}}</b> 
                                    @endif
                                    @endforeach
                                    </td>
                                    <td>
                                    @if($order_dates['status'] == 0)
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($order_dates['status'] == 1)
                                        <span class="badge badge-success">Delivered</span>
                                    @elseif($order_dates['status'] == 2)
                                    <span class="badge badge-danger mr-2">Issue Reported</span>
                                        <button  type="button" class="btn btn-primary btn-xs waves-effect waves-light" data-id="{{$order_dates['id']}}" data-issue="{{$order_dates['issue']}}" data-description="{{$order_dates['description']}}" data-toggle="modal" data-target=".bs-example-modal-center" id="view">View</button>
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
                <div class="mt-4 mb-1">
                    <div class="text-right d-print-none">
                        <a href="javascript:window.print()" class="btn btn-primary waves-effect waves-light"><i class="mdi mdi-printer mr-1"></i> Print</a>
                    </div>
                </div>
            </div> <!-- end card-box -->
        </div> <!-- end col -->
    </div>

    <div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="myCenterModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myCenterModalLabel">Report Issue</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover"> 
                      <tbody>
                          <tr>
                              <td>Issue</td>
                              <td id="modal-issue"></td>
                          </tr>
                          <tr>
                              <td>Description</td>
                              <td id="modal-description">--</td>
                          </tr>
                      </tbody>
                  </table>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>


@endsection
@section('script')
<script type="text/javascript">
$(document).on('click',"#view", function(){
    $('#modal-issue').html($(this).data("issue"));
    if ($(this).data("description") != ''){
        $('#modal-description').html(($(this).data("description")));
    }
});
</script>
@endsection
