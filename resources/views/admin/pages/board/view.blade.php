@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                {{ Breadcrumbs::render('viewboard')}}
                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
            </div>
		</div>
	</div>
    <div class="row">
        <div class="col-lg-4 col-xl-4">
            <div class="card-box text-center">
                <img src="{{env('AWS_S3_URL').$user['profile_picture']}}" class="rounded-circle avatar-lg img-thumbnail"
                    alt="profile-image">

                <h4 class="mb-0">{{$user['first_name']}}</h4>
                <p class="text-muted">{{$user['email']}}</p>

                <div class="text-left mt-3">
                    <!-- <h4 class="font-13 text-uppercase">About Me :</h4> -->
                    <p class="text-muted mb-2 font-13"><strong>Board Name :</strong> <span class="ml-2">{{$user['first_name']}}</span></p>

                    <p class="text-muted mb-2 font-13"><strong>Phone :</strong><span class="ml-2">{{$user['phone_formatted']}}</span></p>

                    <p class="text-muted mb-2 font-13"><strong>Email :</strong> <span class="ml-2">{{$user['email']}}</span></p>
                </div>
            </div> <!-- end card-box -->


        </div> <!-- end col-->

     

        </div> <!-- end col -->
        @if(count($banner) > 0)
        <h3 class="text-dark">Banner</h3>
        @endif

        <div class="row">
        @foreach($banner as $banner)
        <div class="col-xl-3">
            <div class="card">
              <img class="card-img-top"  style="height: 219px;object-fit: contain;" src="{{$banner->image}}" alt="Card image cap">
              <!-- <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
              </div> -->
               <ul class="list-group list-group-flush">
                    <li class="list-group-item"> Start Date: {{date('m-d-Y', strtotime($banner->start_date))}} </li>
                    <li class="list-group-item"> End Date : {{date('m-d-Y', strtotime($banner->end_date))}} </li>   
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