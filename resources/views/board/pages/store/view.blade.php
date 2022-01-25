@extends('board.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                {{ Breadcrumbs::render('viewBoardstore')}}
                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
            </div>
		</div>
	</div>
    <div class="row">
        <div class="col-lg-6 col-xl-6">
            <div class="card-box text-center">
                <img src="{{$user['profile_picture']}}" class="rounded-circle avatar-lg img-thumbnail"
                    alt="profile-image">

                <h4 class="mb-0">{{$user['first_name']}} {{$user['last_name']}}</h4>
                <p class="text-muted">{{$user['email']}}</p>

               <div class="text-left mt-6">
                    <!-- <h4 class="font-13 text-uppercase">About Me :</h4> -->
                    <p class="text-muted mb-2 font-13"><strong>Store Name :</strong> <span class="ml-2">{{$user['first_name']}} {{$user['last_name']}}</span></p>

                    
                    <p class="text-muted mb-2 font-13"><strong>Phone :</strong><span class="ml-2">{{$user['phone_formatted']}}</span></p>

                    <p class="text-muted mb-2 font-13"><strong>Email Address :</strong> <span class="ml-2">{{$user['email']}}</span></p>

                    

                    <p class="text-muted mb-2 font-13"><strong>Start Time :</strong> <span class="ml-2">{{$user['start_time']}}</span></p>

                    <p class="text-muted mb-2 font-13"><strong>End Time :</strong> <span class="ml-2">{{$user['end_time']}}</span></p>

                    <p class="text-muted mb-2 font-13"><strong>Address :</strong> <span class="ml-2">{{$user['address']}}</span></p>

                    <p class="text-muted mb-2 font-13"><strong>Zipcode :</strong> <span class="ml-2">{{$user['zipcode']}}</span></p>

                    <p class="text-muted mb-2 font-13"><strong>Latitude :</strong> <span class="ml-2">{{$user['latitude']}}</span></p>

                    <p class="text-muted mb-2 font-13"><strong>Longitude :</strong> <span class="ml-2">{{$user['longitude']}}</span></p>


                    <p class="text-muted mb-2 font-13"><strong>Available Service :</strong> <span class="ml-2">
                    <?php
                        if ($user['delivery_type']=='1') 
                            {
                              echo "Instore";
                            } elseif ($user['delivery_type']=='2') 
                            {
                              echo "Curbside";
                            } else 
                            {
                              echo "Instore,Curbside";
                            }
                    ?>
                    </span></p>
                </div>
            </div> <!-- end card-box -->    


        </div> <!-- end col-->

        
        </div> <!-- end col -->
        <div class="row">
            @foreach($storeproducts as $key => $value)
        <div class="col-xl-3">
            <div class="card">
              <img class="card-img-top" style="height: 300px;object-fit: scale-down;" src="{{url($value->product->image)}}" alt="Card image cap">
              <!-- <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
              </div> -->
               <ul class="list-group list-group-flush">
                    <li class="list-group-item"> Product Name : {{$value->product->name}}</li>
                    <li class="list-group-item"> Stock : {{$value->stock}} </li>
                     <li class="list-group-item"> Quantity : {{$value->product->quantity}} {{$value->product->measurement_name}} </li>
                    <li class="list-group-item">Previous Price Retail : ${{$value->product->previous_price_retail}} </li>
                    <li class="list-group-item">Current Price Retail : ${{$value->product->current_price_retail}} </li>
                    <li class="list-group-item">Previous Price business : ${{$value->product->previous_price_business}} </li>
                    <li class="list-group-item">Retail Discount : {{$value->product->retail_discount}}% </li>
                    <li class="list-group-item">Current Price business : ${{$value->product->current_price_business}} </li>
                    <li class="list-group-item">Business Discount : {{$value->product->business_discount}}%</li>
                    <li class="list-group-item">Item Code : {{$value->product->item_code}}</li>
                   
                    
                </ul>
              <!-- <div class="card-body">
                <a href="#" class="card-link">Card link</a>
                <a href="#" class="card-link">Another link</a>
              </div> -->
            </div>
                
        </div>
            @endforeach
    </div>
    </div>
</div>

@endsection
@section('script')
@include('admin.include.table_script_multiple')
<script type="text/javascript">
$(function() {
    $('#school_table').DataTable();
    $('#category_table').DataTable();
    $('#product_table').DataTable();
});
</script>
@endsection	