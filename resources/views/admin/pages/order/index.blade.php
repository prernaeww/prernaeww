@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                {{ Breadcrumbs::render('order')}}
                </div>
                <h4 class="page-title">{{$dateTableTitle}}</h4>
            </div>
            <div class="btn-group float-right mt-2 mb-2">
            </div>
        </div>
    </div>
    @include('admin.include.flash-message')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body" >
                     <form class="form-inline mb-2" method="post" action="{{route('admin.orders.index')}}" id="filter-form">

                        <div class="form-group">
                            <select class="form-control select2" name="store_id" id="store_id">
                                 <option value="All">All Store</option>
                                @foreach($store_data as $store)
                                    <option value="{{$store->id}}">{{$store->first_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mx-2">
                            <select class="form-control select2" name="pickup_method" id="pickup_method">
                                <option value="All">All Pickup Method</option>
                                <option value="InStore">InStore</option>
                                <option value="CurbSide">CurbSide</option>
                            </select>
                        </div>

                        <div class="form-group ">
                            <select class="form-control select2" name="status" id="order_status">
                                <option value="All">All Order Status</option>
                             
                                @foreach(config('app.order_status') as $order)
                                <option value="{{$order}}">{{$order}}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light ml-1" ><i class="fa fa-search" name="search"></i></button>
                    </form>
                    @include('admin.include.table')
                </div>
            </div>  
        </div>
    </div>
</div>
<!-- Modal -->
<div id="exampleModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Send Push Notification</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-2">
                <form id="form-inline" action="">
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
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info waves-effect waves-light" id="send" data-user-id="" data-order-id="" >Send </button>
            </div>
            </div>
        </div>
    </div>
</div><!-- /.modal -->


@endsection
@section('script')

@include('admin.include.table_script')
<script src="{{ URL::asset('assets/js/order.js')}}"></script>

@endsection     