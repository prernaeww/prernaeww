@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                {{ Breadcrumbs::render('editbanner')}}
                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
            </div>
		</div>
	</div>
	<div class="row">
		<div class="col-xl-6">
			<div class="card">
				<div class="card-body" >
                <form action="{{ route('admin.banner.update',$banner->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                    <div class="row">

                        <div class="col-12">
                            <div class="form-group">
                                <label for="parent_id">Select Board<span class="text-danger">*</span></label>
                                <select class="form-control select2" data-parsley-errors-container="#parent_error"  name="user_id" id="user_id" data-placeholder="Select Board" required>
                                <option selected disabled></option>
                                @if(isset($data) && count($data) > 0)
                                    @foreach($data as $board)
                                    <option value="{{$board->id}}" {{$banner->user_id == $board->id  ? 'selected' : ''}}>{{$board->first_name}}</option>
                                    @endforeach
                                @endif
                                    
                                </select>
                                <div id="parent_error"></div>
                                @error('user_id')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                         <div class="col-lg-12">
                            <div class="form-group ">
                                <label for="image">Start Date<span class="text-danger">*</span></label>
                                <input type="text"  id="datepicker" name="start_date" parsley-trigger="change" value="{{date('m-d-Y', strtotime($banner->start_date))}}" required placeholder="Enter Start Date" class="form-control datepicker-autoclose" id="name">
                                @error('start_date')
                                    <div class="error">{{ $message }}</div>
                                @enderror        
                            </div>
                        </div>

                         <div class="col-lg-12">
                            <div class="form-group ">
                                <label for="image">End Date<span class="text-danger">*</span></label>
                                <input type="text" name="end_date" parsley-trigger="change"  value="{{date('m-d-Y', strtotime($banner->end_date))}}" required placeholder="Enter End Date" class="form-control datepicker-autoclose" id="name">
                                @error('end_date')
                                    <div class="error">{{$message}}</div>
                                @enderror        
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="image">Image<span class="text-danger">*</span></label>
                                <input type="file" data-parsley-max-file-size="5" data-parsley-trigger="change" data-parsley-filemimetypes="image/jpeg, image/png" accept="image/*" data-parsley-file-mime-types-message="Only allowed jpeg & png files" onchange="readURL1(this);" id="image" name="image" class="form-control" />
                                <small id="img-help" class="form-text text-muted">Image size should be 373 X 98 to look proper in application. <a href="{{ url('/images/banner_demo.png') }}" target="_blank"><b>View Demo Banner</b></a> </small>
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
                                <img class="border rounded p-0"  src="{{$banner->image}}" onerror="this.src='{{$default}}'" alt="your image" style="height: 130px;width: 130px; object-fit: cover;" id="blah1"/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-right m-b-0">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">
                            Submit
                        </button>
                        <a href="{{ route('admin.banner.index') }}" class="btn btn-secondary waves-effect m-l-5">Cancel</a>
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
<script src="{{ URL::asset('assets/js/pages/bootstrap-datepicker.js')}}"></script>
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
$('.datepicker-autoclose').datepicker({
            format: 'mm-dd-yyyy',
            autoclose: true,
            startDate: new Date(),
           
        });
</script>
@endsection