@extends('board.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                {{ Breadcrumbs::render('profile')}}
                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
            </div>
		</div>
	</div>
    @include('board.include.flash-message')
    <div class="row">
        <div class="col-lg-4 col-xl-4">
            <div class="card-box text-center">
                <img src="{{ URL::to($user['profile_picture'])}}" class="rounded-circle avatar-lg img-thumbnail"
                    alt="profile-image">

                <h4 class="mb-0">{{$user['first_name']}} </h4>
                <p class="text-muted">{{$user['email']}}</p>

                <div class="text-left mt-3">
                    <p class="text-muted mb-2 font-13"><strong>Board Name :</strong> <span class="ml-2">{{$user['first_name']}}</span></p>

                    <p class="text-muted mb-2 font-13"><strong>Phone :</strong><span class="ml-2">{{$user['phone_formatted']}}</span></p>

                    <p class="text-muted mb-2 font-13"><strong>Email :</strong> <span class="ml-2">{{$user['email']}}</span></p>
                    
                    <!-- <p class="text-muted mb-2 font-13"><strong>Gender :</strong> <span class="ml-2">{{$user['gender'] == '1' ? 'Male' : 'Female'}}</span></p> -->

                    <!-- <p class="text-muted mb-2 font-13"><strong>DOB :</strong> <span class="ml-2">{{ $user['dob'] }}</span></p> -->
                    <!-- 
                    <p class="text-muted mb-2 font-13"><strong>Department :</strong> <span class="ml-2">{{ $user['department'] }}</span></p>
                    <p class="text-muted mb-2 font-13"><strong>Grade :</strong> <span class="ml-2">{{ $user['grade'] }}</span></p>
                    <p class="text-muted mb-2 font-13"><strong>Class :</strong> <span class="ml-2">{{ $user['class'] }}</span></p> -->
                </div>
            </div> <!-- end card-box -->


        </div> <!-- end col-->

        <div class="col-lg-8 col-xl-8">
            <div class="card-box">
                <h3 class="page-title mb-3"><b>Update Profile</b></h3>

                <form action="{{ route('board.profile.update',$user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                    <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="first_name">Board Name<span class="text-danger">*</span></label>
                                <input type="text" name="first_name" parsley-trigger="change" value="{{$user->first_name}}" required placeholder="Enter Board name" class="form-control" id="first_name">
                                @error('first_name')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                          
                    </div>

                    <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="email">Email address</label>
                                <input type="email" name="email" parsley-trigger="change" value="{{$user->email}}" placeholder="Enter email" class="form-control" id="email" readonly>
                                @error('email')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="phone">Phone</label>
                                <input type="text" name="phone" parsley-trigger="change" value="{{$user->phone_formatted}}" placeholder="9999999999" class="form-control" id="phone"  readonly>
                                @error('phone')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                    </div>
                    <label for="image">Profile Picture<span class="text-danger">*</span></label>
                    <div class="col-6">
                            <div class="form-group">
                                <input type="file" data-parsley-trigger="change"  data-parsley-max-file-size="5" data-parsley-filemimetypes="image/jpeg, image/png" accept="image/*" data-parsley-file-mime-types-message="Only allowed jpeg & png files" onchange="readURL1(this);" id="inputGroupFile04" name="profile_picture" class="custom-file-input" />
                                <label class="custom-file-label" for="inputGroupFile04">Choose file</label>
                                @error('profile_picture')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                             
                    </div>

                    <div class="col-lg-6">
                            <div class="form-group">
                            @php
                                $default = '/images/default.png';
                            @endphp
                                <img class="border rounded p-0"  src="{{env('AWS_S3_URL').$user['profile_picture']}}" onerror="this.src='{{$default}}'" alt="your image" style="height: 130px;width: 130px; object-fit: cover;" id="blah1"/>
                            </div>
                    </div>

                    <div class="form-group text-right m-b-0">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">
                            Submit
                        </button>
                        <button type="reset" class="btn btn-secondary waves-effect m-l-5">
                            Reset
                        </button>
                    </div>

                </form>

            </div> <!-- end card-box-->

        </div> <!-- end col -->
    </div>
    <div class="row">
        <div class="col-lg-4 col-xl-4">


        </div> <!-- end col-->

        <div class="col-lg-8 col-xl-8">
            <div class="card-box">
                <h3 class="page-title mb-3"><b>Change Password</b></h3>
                <form action="{{ route('board.change.password',$user->id) }}" method="POST">
                @csrf
                    <div class="form-group">
                        <label for="product-summary">Current Password</label>
                        <!-- <textarea class="form-control" id="product-summary" rows="1" placeholder="Please enter password" name="correct_answer">{{ old('correct_answer') }}</textarea> -->
                        <input type="password" name="current_password" class="form-control" required>
                        @error('current_password')
                                    <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="product-summary">New Password</label>
                        <!-- <textarea class="form-control" id="product-summary" rows="1" placeholder="Please enter password" name="correct_answer">{{ old('correct_answer') }}</textarea> -->
                        <input type="password" name="new_password" class="form-control" required>
                        @error('new_password')
                                    <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="product-summary">Confirm New Password</label>
                        <!-- <textarea class="form-control" id="product-summary" rows="1" placeholder="Please enter password" name="correct_answer">{{ old('correct_answer') }}</textarea> -->
                        <input type="password" name="confirm_new_password" class="form-control" required>
                        @error('confirm_new_password')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                    </div>

                    <div class="form-group text-right m-b-0">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">
                            Submit
                        </button>
                        <button type="reset" class="btn btn-secondary waves-effect m-l-5">
                            Reset
                        </button>
                    </div>

                </form>

            </div> <!-- end card-box-->

        </div> <!-- end col -->
    </div>
</div>

@endsection
@section('script')
<script>
// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});

function readURL1(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#blah1').attr('src', e.target.result);
            $('.blah1').attr('href', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
} 
</script>
@endsection	