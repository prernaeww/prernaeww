@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    {{ Breadcrumbs::render('editboard')}}
                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
            </div>
		</div>
	</div>
	<div class="row">
		<div class="col-xl-6">
			<div class="card">
				<div class="card-body" >
                <form action="{{ route('admin.board.update',$user->id) }}" method="POST"  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first_name">Board Name<span class="text-danger">*</span></label>
                                <input type="text" name="first_name" parsley-trigger="change" value="{{$user->first_name}}" required placeholder="Enter Board Name" class="form-control" id="first_name">
                                @error('first_name')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="email">Email address<span class="text-danger">*</span></label>
                                <input type="email" name="email" parsley-trigger="change" value="{{$user->email}}" required placeholder="Enter email" class="form-control" id="email" readonly>
                                @error('email')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="phone">Phone<span class="text-danger">*</span></label>
                                <input type="text" name="phone" parsley-trigger="change" value="{{$user->phone_formatted}}" required  class="form-control" id="phone" readonly>
                                @error('phone')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="image">Image<span class="text-danger">*</span></label>
                                <input type="file" data-parsley-trigger="change"  data-parsley-max-file-size="5" data-parsley-filemimetypes="image/jpeg, image/png" accept="image/*" data-parsley-file-mime-types-message="Only allowed jpeg & png files" onchange="readURL1(this);" id="image" name="image" class="form-control" />
                                @error('image')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6"> 
                            <div class="form-group">
                            @php
                                $default = '/images/default.png';
                            @endphp
                                <img class="border rounded p-0"  src="{{env('AWS_S3_URL').$user->profile_picture}}" onerror="this.src='{{$default}}'" alt="your image" style="height: 130px;width: 130px; object-fit: cover;" id="blah1"/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-right m-b-0">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">
                            Submit
                        </button>
                        <a href="{{ route('admin.board.index') }}" class="btn btn-secondary waves-effect m-l-5">Cancel</a>
                        <!-- <button type="reset" class="btn btn-secondary waves-effect m-l-5">
                            Cancel
                        </button> -->
                    </div>

                </form>

				</div>
			</div>	
		</div>
	</div>
</div>
@endsection
@section('script')
<script type="text/javascript">
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