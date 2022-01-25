@extends('store.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                {{ Breadcrumbs::render('store-product')}}
                </div>
                <h4 class="page-title">{{$dateTableTitle}}</h4>
            </div>
            <div class="btn-group float-right mt-2 mb-2">
                    <!-- <a href="{{$addUrl}}"  class="btn btn-sm btn-secondary waves-effect waves-light">
                    <span class="btn-label">
                        <i class="fa fa-plus"></i>
                    </span>
                    Add
                </a> -->
            </div>
		</div>
	</div>
    @include('store.include.flash-message')
	<div class="row">
		<div class="col-xl-12">
			<div class="card">
				<div class="card-body" >
					@include('store.include.table')
				</div>
			</div>	
		</div>
	</div>
</div>


@endsection
@section('script')
@include('admin.include.table_script')
@endsection		