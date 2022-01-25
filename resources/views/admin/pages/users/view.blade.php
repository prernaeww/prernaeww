@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                {{ Breadcrumbs::render('viewuser')}}
                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
            </div>
		</div>
	</div>
    <div class="row">
        <div class="col-lg-4 col-xl-4">
            <div class="card-box text-center">
                <img src="{{ URL::to($user['profile_picture'])}}" class="rounded-circle avatar-lg img-thumbnail"
                    alt="profile-image">

                <h4 class="mb-0">{{$user['first_name']}} {{$user['last_name']}}</h4>
                <p class="text-muted">{{$user['email']}}</p>

                <div class="text-left mt-3">
                    @if($user_group['group_id'] == '6')
                    <p class="text-muted mb-2 font-13"><strong>Parent Name :</strong><a target="_blank" href="{{route('admin.user.show',$user['parent_id'])}}"> {{$user['parent_name']}}</a></p>
                    @endif
                    <p class="text-muted mb-2 font-13"><strong>First Name :</strong> <span class="ml-2">{{$user['first_name']}}</span></p>

                    <p class="text-muted mb-2 font-13"><strong>Last Name :</strong> <span class="ml-2">{{$user['last_name']}}</span></p>
                    @if($user_group['group_id'] == '3' || $user_group['group_id'] == '4' || $user_group['group_id'] == '5' || $user_group['group_id'] == '2')
                    <p class="text-muted mb-2 font-13"><strong>Phone :</strong><span class="ml-2">{{$user['phone_formatted']}}</span></p>
                    <p class="text-muted mb-2 font-13"><strong>Email :</strong> <span class="ml-2">{{$user['email']}}</span></p>
                    @endif
                    
                     @if($user['dob']!=NULL)
                    <p class="text-muted mb-2 font-13"><strong>Date OF Birth :</strong> <span class="ml-2">{{date('m-d-Y',strtotime($user['dob']))}}</span></p>
                    @else
                    <p class="text-muted mb-2 font-13"><strong>Date OF Birth :</strong> <span class="ml-2">-</span></p>
                    @endif
                    
                    <p class="text-muted mb-2 font-13"><strong>User Type :</strong> <span class="ml-2"> {{ ($user['user_type'] == '1')?"Business":"Retailer" }}</span></p>

                    @if($user['user_type'] == 1)
                    <p class="text-muted mb-2 font-13"><strong>Business Name :</strong> <span class="ml-2"> {{$user['business_name']}}</span></p>
                    @endif

                    
                </div>
            </div> <!-- end card-box -->


        </div> <!-- end col-->
    
    @if($user_group['group_id'] == '2' )
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
                                        <td><u><a target="_blank" href="{{route('admin.school.show',$school['id'])}}"> {{$school['name']}}</a></u></td>
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
    @endif

    @if($user_group['group_id'] == '3' )
        <div class="col-lg-8 col-xl-8">
            <div class="card-box">
                <ul class="nav nav-pills navtab-bg nav-justified">
                    <li class="nav-item">
                        <a href="#child" data-toggle="tab" aria-expanded="false" class="nav-link active">
                            List of Children
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane show active" id="child">

                        <div class="table-responsive">
                            <table class="table table-borderless mb-0"  id="child_table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>School</th>
                                        <th>Grade</th>
                                        <th>Class</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($user['children'] as $child)
                                <tr>
                                        <td>{{$child['id']}}</td>
                                        <td><u><a target="_blank" href="{{route('admin.user.show',$child['id'])}}"> {{$child['first_name']." ".$child['last_name']}}</a></u></td>
                                        <td><u><a target="_blank" href="{{route('admin.school.show',$child['school'])}}"> {{$child['school_name']}}</a></u></td>
                                        <td> {{$child['grade_name']}}</td>
                                        <td> {{$child['class']}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div> <!-- end tab-pane -->
                    <!-- end about me section content -->
                </div> <!-- end tab-content -->
            </div> <!-- end card-box-->

        </div> <!-- end col -->
    @endif
    </div>
</div>

@endsection
@section('script')
@include('admin.include.table_script_multiple')
<script type="text/javascript">
$(function() {
    $('#child_table').DataTable();
    $('#school_table').DataTable();
    $('#category_table').DataTable();
    $('#product_table').DataTable();
});
</script>
@endsection	