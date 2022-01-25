@extends('store.layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                {{ Breadcrumbs::render('store-order')}}
                </div>
                <h4 class="page-title">{{$dateTableTitle}}</h4>
            </div>
            <div class="btn-group float-right mt-2 mb-2">
                   <!--  -->
            </div>
        </div>
    </div>
    @include('store.include.flash-message')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body" >
                    <form class="form-inline mb-2" method="post" action="{{route('store.orders.index')}}" id="filter-form">


                        <div class="form-group">
                            <select class="form-control select2" name="pickup_method" id="pickup_method">
                                <option value="All">All</option>
                                <option value="InStore">InStore</option>
                                <option value="CurbSide">CurbSide</option>
                            </select>
                        </div>

                        <div class="form-group mx-2">
                            <select class="form-control select2" name="status" id="order_status">
                                <option value="All">All</option>
                                @foreach(config('app.order_status') as $order)
                                <option value="{{$order}}">{{$order}}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light ml-1" ><i class="fa fa-search" name="search"></i></button>
                    </form>
                    @include('store.include.table')
                </div>
            </div>  
        </div>
    </div>
</div>

<div id="exampleModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Push Notification</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-2">
                <input type="hidden" id="user_id" value="">
                <input type="hidden" id="order_id" value="">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="field-3" class="control-label">Title</label>
                            <input type="text" parsley-trigger="change" value="{{old('title')}}" required class="form-control" name="title" id="field-3" placeholder="Enter Title">
                             <input type="hidden" parsley-trigger="change" value="{{old('order_id')}}" required class="form-control" name="order_id" id="order_id" placeholder="Enter order_id" >
                        </div>
                    </div>
                </div>
              
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group no-margin">
                            <label for="notification" class="control-label">Message</label>
                            <textarea class="form-control" id="notification" placeholder="Enter Message" name="notification" parsley-trigger="change" value="{{old('message')}}" required></textarea>
                        </div>
                    </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info waves-effect waves-light" id="send_store" data-user-id="" data-order-id="">Send </button>
            </div>
            </div>
        </div>
    </div>
</div><!-- /.modal -->



@endsection
@section('script')

@include('store.include.table_script')
<script src="{{ URL::asset('assets/js/order.js')}}"></script>

@endsection     