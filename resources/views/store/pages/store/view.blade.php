@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                {{ Breadcrumbs::render('viewstore')}}
                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
            </div>
		</div>
	</div>
    <div class="row">
        <div class="col-lg-4 col-xl-4">
            <div class="card-box text-center">
                <img src="{{$user['profile_picture']}}" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">

                <h4 class="mb-0">{{$user['first_name']}} {{$user['last_name']}}</h4>
                <p class="text-muted">{{$user['email']}}</p>

                <div class="text-left mt-3">
                    <h4 class="font-13 text-uppercase">About Me :</h4>
                    <p class="text-muted mb-2 font-13"><strong>First Name :</strong> <span class="ml-2">{{$user['first_name']}}</span></p>

                    <p class="text-muted mb-2 font-13"><strong>Last Name :</strong> <span class="ml-2">{{$user['last_name']}}</span></p>

                    <p class="text-muted mb-2 font-13"><strong>Phone :</strong><span class="ml-2">{{$user['phone_formatted']}}</span></p>

                    <p class="text-muted mb-2 font-13"><strong>Email :</strong> <span class="ml-2">{{$user['email']}}</span></p>
                </div>
            </div> <!-- end card-box -->    


        </div> <!-- end col-->

       
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