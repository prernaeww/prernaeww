@extends('board.layouts.master')
@section('css')
<link href="{{ URL::asset('assets/css/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                {{ Breadcrumbs::render('addbanner')}}
                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
            </div>
		</div>
	</div>
	<div class="row">
		<div class="col-xl-6">
			<div class="card">
				<div class="card-body" >
                <form action="{{ route('admin.banner.store') }}" method="POST"  enctype="multipart/form-data">
                @csrf
                @method('POST')

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="parent_id">Select Board<span class="text-danger">*</span></label>
                                <select class="form-control select2" data-parsley-errors-container="#parent_error" name="user_id" id="user_id" data-placeholder="Select Board" required>
                                    <option selected disabled></option>
                                    @if(isset($data) && count($data) > 0)
                                        @foreach($data as $board)
                                        <option value="{{$board->id}}">{{$board->first_name}}</option>
                                        @endforeach
                                    @endif
                                    
                                </select>
                                <div id="parent_error"></div>
                                @error('user_id')
                                    <div class="error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group ">
                                <label for="image">Start Date<span class="text-danger">*</span></label>
                                <input type="text"  id="datepicker" name="start_date" parsley-trigger="change" value="{{old('start_date')}}" required placeholder="Enter name" class="form-control depature_datepicker" id="name">
                                @error('start_date')
                                    <div class="error">{{ $message }}</div>
                                @enderror        
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group ">
                                <label for="image">End Date<span class="text-danger">*</span></label>
                                <input type="text" name="end_date" parsley-trigger="change" value="{{old('end_date')}}" required placeholder="Enter name" class="form-control depature_datepicker" id="name">
                                @error('end_date')
                                    <div class="error">{{ $message }}</div>
                                @enderror        
                            </div>
                        </div>

                      
                        <div class="col-lg-12">
                            <div class="form-group ">
                                <label for="image">Image<span class="text-danger">*</span></label>
                                <input type="file"  data-parsley-max-file-size="5" required data-parsley-trigger="change" data-parsley-filemimetypes="image/jpeg, image/png" accept="image/*" data-parsley-file-mime-types-message="Only allowed jpeg & png files" onchange="readURL1(this);" id="image" name="image" class="form-control" />
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