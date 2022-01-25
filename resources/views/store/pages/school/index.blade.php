@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                {{ Breadcrumbs::render('school')}}
                </div>
                <h4 class="page-title">{{$dateTableTitle}}</h4>
            </div>
            <div class="btn-group float-right mt-2 mb-2">
                    <a href="{{$addUrl}}"  class="btn btn-sm btn-primary waves-effect waves-light">
                    <span class="btn-label">
                        <i class="fa fa-plus"></i>
                    </span>
                    Add
                </a>
            </div>
        </div>
    </div>
    @include('admin.include.flash-message')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body" >
                    <form class="form-inline mb-2" method="post" action="{{route('admin.school.index')}}" id="filter-form">
                        <div class="form-group">
                            <select class="form-control" name="canteen" id="canteen">
                                <option value="all">All</option>
                                @foreach($canteen as $value)
                                <option value="{{$value->id}}">{{$value->first_name." ".$value->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary waves-effect waves-light ml-1" id="booking-filter"><i class="fa fa-search"></i></button>
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
