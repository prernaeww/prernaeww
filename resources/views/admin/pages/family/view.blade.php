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

        <div class="col-lg-8 col-xl-8">
            <div class="card-box">
                <ul class="nav nav-pills navtab-bg nav-justified">
                    <li class="nav-item">
                        <a href="#school" data-toggle="tab" aria-expanded="false" class="nav-link">
                            Schools
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#category" data-toggle="tab" aria-expanded="true" class="nav-link active">
                            Category
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#product" data-toggle="tab" aria-expanded="false" class="nav-link">
                            Product
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" id="school">

                        <div class="table-responsive">
                            <table class="table table-borderless mb-0"  id="school_table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($user['canteen_school'] as $school)
                                <tr>
                                        <td>{{$school['id']}}</td>
                                        <td><u><a href="{{route('admin.school.show',$school['id'])}}"> {{$school['name']}}</a></u></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div> <!-- end tab-pane -->
                    <!-- end about me section content -->
                    <div class="tab-pane show active" id="category">

                    <div class="table-responsive">
                            <table class="table table-borderless mb-0"  id="category_table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($user['category'] as $category)
                                <tr>
                                        <td>{{$category['id']}}</td>
                                        <td>{{$category['name']}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <!-- end timeline content-->

                    <div class="tab-pane" id="product">
                    <div class="table-responsive">
                            <table class="table table-borderless mb-0"  id="product_table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Category Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($user['product'] as $product)
                                <tr>
                                        <td>{{$product['id']}}</td>
                                        <td>{{$product['name']}}</td>
                                        <td>{{$product['category_name']}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                    </div>
                    <!-- end settings content-->

                </div> <!-- end tab-content -->
            </div> <!-- end card-box-->

        </div> <!-- end col -->
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