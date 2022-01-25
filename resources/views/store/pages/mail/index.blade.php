@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <!-- {{ Breadcrumbs::render('editcanteen')}} -->
                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
            </div>
		</div>
	</div>
    @include('admin.include.flash-message')
	<div class="row">
		<div class="col-xl-6">
			<div class="card">
				<div class="card-body" >
                <form action="" method="POST"  enctype="multipart/form-data">
                @csrf

                    <div class="row">

                        <div class="col-12">
                            <div class="form-group">
                                <label for="user_type">Select Type<span class="text-danger">*</span></label>
                                <select class="form-control" id="user_type" name="user_type" required  >
                                    <option value="">Please Select Type</option>
                                    <!-- <option value="2">Canteen</option> -->
                                    <option value="3">Parent</option>
                                    <option value="4">Student</option>
                                    <option value="5">Employee</option>
                                </select>
                                @error('user_type')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group" >
                                <label for="school_id">School<span class="text-danger">*</span></label>
                                <select class="form-control select2" name="school_id[]" id="school_id" required multiple="">
                                    <option value="" disabled=""> Select School </option>
                                    @foreach($school as $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </select>
                                @error('school_id')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="title">Title<span class="text-danger">*</span></label>
                                <input type="text" name="title" parsley-trigger="change"  required placeholder="Enter Title" class="form-control" id="title">
                                @error('title')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="description">Description<span class="text-danger">*</span></label>
                                <textarea name="description" parsley-trigger="change" required placeholder="Enter Description" class="form-control" id="description"></textarea>
                                <!-- <input type="text" name="description" parsley-trigger="change" required placeholder="Enter Description" class="form-control" id="description"> -->
                                @error('description')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
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