@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                {{ Breadcrumbs::render('editgrade')}}
                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
            </div>
		</div>
	</div>
	<div class="row">
		<div class="col-xl-6">
			<div class="card">
				<div class="card-body" >
                <form action="{{ route('admin.grade.update',$grade->id) }}" method="POST">
                @csrf
                @method('PUT')

                    <div class="row">
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name">Name<span class="text-danger">*</span></label>
                                <input type="text" name="name" parsley-trigger="change" value="{{$grade->name}}" required placeholder="Enter name" class="form-control" id="name">
                                @error('name')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="name">Slug<span class="text-danger">*</span></label>
                                <input type="text" name="slug" parsley-trigger="change" value="{{$grade->slug}}" required placeholder="Enter slug" class="form-control" id="slug">
                                @error('slug')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-right m-b-0">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">
                            Submit
                        </button>
                        <a href="{{ route('admin.grade.index') }}" class="btn btn-secondary waves-effect m-l-5">Cancel</a>
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