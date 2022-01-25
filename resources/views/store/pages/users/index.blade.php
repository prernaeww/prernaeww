@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<div class="page-title-box">
				<div class="page-title-right">
					{{ Breadcrumbs::render('users') }}
					
				</div>
				<h4 class="page-title">{{$dateTableTitle}}</h4>
			</div>
		</div>
	</div>
	@include('admin.include.flash-message')
	<div class="row">
		<div class="col-xl-12">
			<div class="card">
				<div class="card-body" >
				<form class="form-inline mb-2" method="post" action="{{route('admin.users.index')}}" id="filter-form">
                        <div class="form-group">
                            <select class="form-control" name="groups" id="groups">
                                <option value="all">All</option>
                                @foreach($groups as $value)
								@if($value->id != 1)
                                <option value="{{$value->id}}">{{$value->description}}</option>
								@endif
                                @endforeach
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary waves-effect waves-light ml-1" ><i class="fa fa-search"></i></button>
                    </form>
					@include('admin.include.table')
				</div>
			</div>	
		</div>
	</div>
</div>


@endsection
@section('script')
@include('admin.include.table_script')
@endsection		