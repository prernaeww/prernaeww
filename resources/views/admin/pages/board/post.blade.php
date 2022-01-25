@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    {{ Breadcrumbs::render('addboard')}}
                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
            </div>
		</div>
	</div>
	<div class="row">
		<div class="col-xl-6">
			<div class="card">
				<div class="card-body" >
                <form action="{{ route('admin.board.store') }}" method="POST"  enctype="multipart/form-data">
                @csrf
                @method('POST')

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first_name">Board Name<span class="text-danger">*</span></label>
                                <input type="text" name="first_name" parsley-trigger="change" value="{{old('first_name')}}" required placeholder="Enter Board name" class="form-control" id="first_name">
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
                                <input type="email" name="email" parsley-trigger="change" value="{{old('email')}}" required placeholder="Enter Email Address" class="form-control" id="email">
                                @error('email')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="phone">Phone<span class="text-danger">*</span></label>
                                
                                <input type="text" name="phone" parsley-trigger="change" placeholder="1(XXX)XXX-XXXX" data-mask="1(999)999-9999" required  class="form-control" data-parsley-maxlength="14"  data-parsley-minlength="14" data-parsley-minlength-message="This value must be at least 14 characters" data-parsley-maxlength="14" data-parsley-maxlength-message="This value must be at least 14 characters">
                                @error('phone')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group ">
                                <label for="image">Image<span class="text-danger">*</span></label>
                                <input type="file" required data-parsley-trigger="change"  data-parsley-max-file-size="5" data-parsley-filemimetypes="image/jpeg, image/png" accept="image/*" data-parsley-file-mime-types-message="Only allowed jpeg & png files" onchange="readURL1(this);" id="image" name="image" class="form-control">
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
                                <img class="border rounded p-0"  src="" onerror="this.src='{{$default}}'" alt="your image" style="height: 130px;width: 130px; object-fit: cover;" id="blah1"/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-right m-b-0">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">
                            Submit
                        </button>
                        <button type="reset" class="btn btn-secondary waves-effect m-l-5">
                            Cancel
                        </button>
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