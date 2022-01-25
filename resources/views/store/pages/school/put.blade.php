@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                {{ Breadcrumbs::render('editschool')}}
                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
            </div>
		</div>
	</div>
	<div class="row">
		<div class="col-xl-6">
			<div class="card">
				<div class="card-body" >
                <form action="{{ route('admin.school.update',$school['id']) }}" method="POST">
                @csrf
                @method('PUT')

                    <div class="row">
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label for="canteen_id">Select Canteen<span class="text-danger">*</span></label>
                               
                                <select class="form-control select2" data-parsley-errors-container="#canteen_error" required name="canteen_id" id="canteen_id" data-placeholder="Select Canteen">
                                    <option selected disabled></option>
                                    @foreach($canteen as $data)
                                    @if ($school['canteen_id'] == $data['id'])
                                        <option value="{{$data['id']}}" selected>{{$data['first_name'].' '.$data['last_name']}}</option>
                                    @else
                                        <option value="{{$data['id']}}" >{{$data['first_name'].' '.$data['last_name']}}</option>
                                    @endif
                                    @endforeach
                                </select>
                                <div id="canteen_error"></div>
                                @error('canteen_id')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group ">
                                <label for="grades">Select Grades<span class="text-danger">*</span></label>
                                <div class="row ml-2 d-flex flex-row">
                                    @foreach($grade as $grades)
                                        <div class="pl-2 pr-3 custom-control custom-checkbox">
                                            <input type="checkbox" required  data-parsley-errors-container="#grades_error"  name="school_grade[]"  value="{{$grades['id']}}" class="custom-control-input" id="customCheck{{$grades['id']}}" 
                                            @if( in_array($grades["id"], $grades_ids) ) checked @endif >
                                            <label class="custom-control-label" for="customCheck{{$grades['id']}}">{{$grades['name']}}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <div id="grades_error"></div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="name">Name<span class="text-danger">*</span></label>
                                <input type="text" name="name" parsley-trigger="change" value="{{$school['name']}}" required placeholder="Enter name" class="form-control" id="name">
                                @error('name')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name">Address<span class="text-danger">*</span></label>
                                <textarea name="address" class="form-control" placeholder="Enter address" required>{{$school['address']}}</textarea>
                                @error('address')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name">Area<span class="text-danger">*</span></label>
                                 <input type="text" name="area" parsley-trigger="change" value="{{$school['area']}}" required placeholder="Enter area" class="form-control" id="area">
                                @error('area')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name">Block<span class="text-danger">*</span></label>
                                 <input type="text" name="block" parsley-trigger="change" value="{{$school['block']}}" required placeholder="Enter block" class="form-control" id="block">
                                @error('block')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                         <div class="col-12">
                            <div class="form-group">
                                <label for="name">Street<span class="text-danger">*</span></label>
                                 <input type="text" name="street" parsley-trigger="change" value="{{$school['street']}}" required placeholder="Enter street" class="form-control" id="street">
                                @error('street')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name">Semester date<span class="text-danger">*</span></label>
                                 <input type="text" name="calendar_constants" parsley-trigger="change" value="{{$school['calendar_constants']}}" required placeholder="Enter Semester date" class="form-control input-daterange-datepicker" id="disable-datepicker">
                                @error('street')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-1">
                                @if (isset($school['holiday']) && !empty($school['holiday']))
                                <label>Add Holiday</label>
                                @foreach ($school['holiday'] as $key => $holiday )

                                <div class="row mb-2">
                                    <div class="col-lg-10">
                                    @if($holiday['from_date'] == $holiday['to_date'])
                                        @php
                                            $date = $holiday['from_date'];
                                        @endphp
                                    @else
                                        @php
                                            $date = $holiday['from_date'].' to '.$holiday['to_date'];
                                        @endphp
                                    @endif
                                    <input type="text"  name="range_datepicker[]"  class="form-control dates range_datepicker" value="{{$date}}">
                                        
                                    </div>
                                    @if ($key != 0)
                                    <div class="col-lg-2">
                                        <label class="btn btn-danger waves-effect waves-light remove-btn remove" title="Remove"><i class="fa fa-times"></i></label>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                                @endif
                                <div class="" id="holidays">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mt-1">
                                <button type="button" name="holiday_button" class="btn btn-primary waves-effect waves-light" id="holiday_button" title="Add Holiday">
                                    Add Holiday
                                </button>
                            </div>
                        </div>
                        <input type="hidden" id="dates" name="dates" >
                    </div>
                    <div class="form-group text-right m-b-0">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">
                            Submit
                        </button>
                        <a href="{{ route('admin.school.index') }}" class="btn btn-secondary waves-effect m-l-5">Cancel</a>
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
<script src="{{ URL::asset('assets/libs/moment/moment.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/school.js')}}"></script>
<script type="text/javascript">
$(function() {
    var fromDate = new Date();
    var rangemindate = new Date(fromDate.getFullYear(), fromDate.getMonth(),fromDate.getDate() + 32);
    console.log(rangemindate);
    $('#disable-datepicker').flatpickr({
        dateFormat: "Y-m-d",
        minDate: rangemindate,
    });
});
</script>
@endsection	