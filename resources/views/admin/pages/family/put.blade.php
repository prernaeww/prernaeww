@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    {{ Breadcrumbs::render('editfamily')}}
                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
            </div>
		</div>
	</div>
	<div class="row">
		<div class="col-xl-6">
			<div class="card">
				<div class="card-body" >
                <form action="{{ route('admin.family.update',$family->id) }}" method="POST"  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="first_name">Name<span class="text-danger">*</span></label>
                                <input type="text" name="name" parsley-trigger="change" value="{{$family->name}}" required placeholder="Enter Name" class="form-control" id="name">
                                @error('name')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                    </div>

                    <div class="form-group text-right m-b-0">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">
                            Submit
                        </button>
                        <a href="{{ route('admin.family.index') }}" class="btn btn-secondary waves-effect m-l-5">Cancel</a>
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