@extends('website.layouts.master')

@section('content')
@include('website.layouts.nav')
<section class="my-order-page my-5 pb-lg-5">

    @if($status== TRUE)
        @if(count($data) > 0)
	   <div class="container">
        @include('website.include.flash-message')        
		<h3 class="t-blue mb-4 pb-3 border-bottom">Order Details</h3>
        <h5 class="text-center">{{$data['status_string']}}</h5>
        <div class="col-lg-12 bg-white border-r10 shadow p-4">
            <div class="text-center">
                <b class="text-danger">Your Order Number is: {{$data['id']}}</b>
                <div class="row mt-3">
                    <div class="col-lg-6 border-right">
                        @if($data['pickup_method'] == '1')
                        <img src="{{ URL::asset('assets/images/website/pick-up-method1.jpg')}}" alt="" class="mr-3">
                        <span>In-Store Pick-up | </span><span class="text-muted"><small>Order will be waiting inside the store.</small></span>
                        @else
                        <img src="{{ URL::asset('assets/images/website/pick-up-method2.jpg')}}" alt="" class="mr-3">
                        <span>In-CurbSide Pick-Up | </span><span class="text-muted"><small>Order will be waiting inside the store.</small></span>
                        @endif
                    </div>
                    <div class="col-lg-6">
                        <div class="align-items-center t-blue"><b>{{$data['store_name']}}</b>
                            <a href="tel:{{$data['phone_formatted']}}"><img src="{{ URL::asset('assets/images/website/call.svg')}}"  alt="" class="ml-3 pointer"></a>
                        </div>
                        <div class="text-muted">{{$data['store']['address']}}</div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="list-group mb-3">
                <ul class="list-unstyled bg-white border-r10 shadow p-3">
                    @foreach($data['order_products'] as $value)
                    <li class="media my-3">
                        <img class="mr-3 align-self-start bg-white border-r10 shadow p-2" src="{{url($value['product']['image'])}}" style="height: 150px; width: 150px; object-fit: scale-down;">
                        <div class="media-body">
                          <h6 class="my-3">{{$value['product']['name']}}</h6>
                          <span class="text-muted"><small>{{$value['product']['quantity']}} {{$value['product']['measurement_name']}} <b>&bull;</b> Qty - {{$value['qty']}}</small></span>
                          <br>
                          <b>${{$value['price']}}</b>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <ul class="list-group mb-3">
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                  <div>
                    <span class="text-muted">Sub total:</span>
                  </div>
                  <span class="text-muted">${{$data['sub_total']}}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                  <div>
                    <span class="text-muted">Tax:</span>
                  </div>
                  <span class="text-muted">${{$data['tax']}}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                  <div>
                    <b>Total:</b>
                  </div>
                  <b>${{$data['total']}}</b>
                </li>
            </ul>
            <div class="t-blue">
                {{$data['status_string']}}
            </div>
            
            
            <div class="mt-5 mb-3 text-center">
                @if($data['show_imhere'] ==  TRUE)
                <a href="/order/here/{{$data['id']}}"><button class="btn btn-blue">I am here</button></a>
                @else
                <button class="btn btn-blue" style="pointer-events: none;" disabled>I am here</button>
                @endif
            </div>
        
        </div>              
	</div>
        @endif
    @endif
</section>
@endsection
