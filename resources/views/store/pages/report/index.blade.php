@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                {{Breadcrumbs::render($breadcrumb_name)}}
                </div>
                <h4 class="page-title">{{$dateTableTitle}}</h4>
            </div>
        </div>
    </div>
    @include('admin.include.flash-message')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-responsive" >
                    <form class="form-inline mb-2" method="post" action="{{route('admin.order.all')}}" id="filter-form">
                        <div class="form-group">
                            <input type="text" name="date" id="range-datepicker" parsley-trigger="change" id="date" placeholder="Enter Date" class="form-control input-daterange-datepicker">
                        </div>
                        <div class="ml-2 form-group" >
                            <select class="form-control select2" name="school" id="school" multiple>
                                <option value="" > Select School </option>
                                @foreach($school as $value)                                
                                <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="ml-2 form-group" >
                            <select class="form-control select2" name="meal" id="meal" multiple>
                                <option value="" > Select Meal </option>
                                @foreach($meal as $value)
                                <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="ml-2 form-group" >
                            <select class="form-control " name="grade" id="grade" >
                                <option value="" selected> Select Grade </option>
                                @foreach($grade as $value)
                                <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary waves-effect waves-light ml-1" id="booking-filter"><i class="fa fa-search"></i></button>
                        <button type="button" class="btn btn-secondary waves-effect waves-light ml-1" id="refresh"><i class="fa fa-refresh"></i></button>
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
<script type="text/javascript">
$( document ).ready(function() {
        range_datepicker = $("#range-datepicker").flatpickr({mode:"range"});
    });
// $(function() {
//     $('#disable-datepicker').flatpickr({
//         dateFormat: "Y-m-d",
//     });
// });
$('#refresh').click(function() {
    location.reload();
});
</script>
@endsection