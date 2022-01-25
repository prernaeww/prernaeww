@extends('board.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                {{ Breadcrumbs::render('viewproduct')}}
                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
            </div>
		</div>
	</div>

    <div class="row">
        @if(isset($storeproducts) && count($storeproducts) > 0)
            @foreach($storeproducts as $variant)
                <div class="col-xl-3">
                    <div class="card">
                        <img class="card-img-top" style="height: 300px;object-fit: contain;" src="{{$variant->product->image}}" alt="Card image cap">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Store Name : {{$variant->store->first_name }} </li>
                            <li class="list-group-item">Product Name : {{$variant->product->name }} </li>
                            <li class="list-group-item">Stock : {{$variant->stock}} </li>
                            <li class="list-group-item">Quantity : {{$variant->product->quantity}} {{$variant->product->measurement_name}} </li>
                            <li class="list-group-item">Previous Price Retail : ${{$variant->product->previous_price_retail}} </li>
                            <li class="list-group-item">Current Price Retail : ${{$variant->product->current_price_retail}} </li>
                            <li class="list-group-item">Previous Price business : ${{$variant->product->previous_price_business}} </li>
                            <li class="list-group-item">Current Price business : ${{$variant->product->current_price_business}} </li>
                            <li class="list-group-item">Retail Discount : {{$variant->product->retail_discount}}% </li>
                            <li class="list-group-item">Business Discount : {{$variant->product->business_discount}}% </li>
                            <li class="list-group-item">Age : {{$variant->product->age}}</li>
                            <li class="list-group-item">Proof : {{$variant->product->proof}}%</li>
                            <li class="list-group-item">Item Code : {{$variant->product->item_code}}</li>
                        </ul>
                    </div> 
                </div>
            @endforeach
        @endif
    </div>
</div>


@endsection
