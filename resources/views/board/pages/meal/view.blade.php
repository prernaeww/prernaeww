@extends('canteen.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                {{ Breadcrumbs::render('viewmeal')}}
                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
            </div>
           
		</div>
	</div>
	<div class="row">
        <div class="col-lg-3">
            <div class="card-box">
                <img src="{{env('AWS_S3_URL').$meal['image']}}" class="img-fluid img-thumbnail" width="400" alt="profile-image">
            </div> 
        </div> 
        <div class="col-9">
            <div class="card-box">
                <p class="text-muted">{{$meal['canteen_name']}}</p>
                <h4 class="mb-2">{{$meal['name']}}</h4>
                <h6 class="mb-2">{{$meal['description']}}</h6>
                <h5 class="mb-0">Price : {{$meal['price']}} KD/day</h4>
                <div class="text-left mt-3">
                    @foreach ($meal['category'] as $category)
                        <p class="text-muted mb-2 font-13"><strong>{{$category['category_name']}}</strong> <span class="ml-2"> X  </span><span class="ml-2">{{$category['items_number']}}</span></p>
                    @endforeach
                </div>
            </div>
        </div>
	</div>
</div>


@endsection