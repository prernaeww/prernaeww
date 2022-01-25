@extends('store.layouts.master')
@section('css')
<link href="{{url('assets/libs/datatables/dataTables.bootstrap4.css')}}" rel="stylesheet" type="text/css" />
<link href="{{url('assets/libs/datatables/responsive.bootstrap4.css')}}" rel="stylesheet" type="text/css" />
<link href="{{url('assets/libs/datatables/buttons.bootstrap4.css')}}" rel="stylesheet" type="text/css" />
<link href="{{url('assets/libs/datatables/select.bootstrap4.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')

                    <!-- Start Content-->
    <div class="container-fluid">
        
        <!-- start page title -->
        @include('admin.include.flash-message')
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        
                    </div>
                    <h4 class="page-title">Dashboard</h4>
                </div>
            </div>
        </div>     
        <!-- end page title --> 
        
        <div class="row">


            <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card-box">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle  border-success border">
                            <i class="fe-dollar-sign font-22 avatar-title text-success"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-right">
                            <h3 class="text-primary mt-1"><span data-plugin="counterup">{{$earnings}}</span></h3>
                            <p class="text-muted mb-1 text-truncate">Total Earnings</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->
       

        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card-box">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle  border-secondary border">
                            <i class="fe-shopping-cart font-22 avatar-title text-secondary"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-right">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup">{{$orders_total}}</span></h3>
                            <p class="text-muted mb-1 text-truncate">Total Orders</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->



            <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card-box">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle border-danger  border">
                            <i class="fas fa-glass font-22 avatar-title text-danger "></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-right">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup">{{$products}}</span></h3>
                            <p class="text-muted mb-1 text-truncate">Total Products</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->




        

        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card-box">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle border-warning border">
                            <i class="fe-shopping-cart font-22 avatar-title text-warning"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-right">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup">{{$orders}}</span></h3>
                            <p class="text-muted mb-1 text-truncate">Total Completed Orders</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->
    </div>

     <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Top Selling Products</h4>
                            <!-- <p class="text-muted font-13 mb-4">
                                DataTables has the ability to show tables with horizontal scrolling, which is very useful for when you have a wide
                                table, but want to constrain it to a limited horizontal display area.
                            </p> -->
                            <br>
                            <table id="scroll-horizontal-datatable" class="table w-100 nowrap">
                                <thead>
                                    <tr>
                                        <th>Number</th>
                                        <th>Name</th>
                                        <th>Category Name</th>
                                        <th>Retail Discount</th>
                                        <th>Business Discount</th>
                                        <th>Total Orders</th>
                                        <th>Action</th>
                                       
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    @php $number = 1; @endphp
                                    @if(isset($top_seilling) && count($top_seilling) > 0)
                                    @foreach($top_seilling as $data)
                                    @if($data->product)
                                   <tr>
                                       <td>{{$number}}</td>
                                       <td>@if(isset($data->product)) {{$data->product->name}} @else - @endif</td>
                                       <td>@if(isset($data->product)) {{$data->product->category_name}} @else - @endif</td>
                                       <td>@if(isset($data->product)) {{$data->product->retail_discount}} @else - @endif%</td>
                                       <td>@if(isset($data->product)) {{$data->product->business_discount}} @else - @endif%</td>
                                       <td>{{$data->total_product_id}}</td>
                                       <td><a href="{{route('store.product.show',$data->product->id)}}">View</a></td>
                                       @php
                                       $number++
                                       @endphp
                                   </tr>
                                   @endif
                                   @endforeach
                                   @endif
                                </tbody>
                            </table>

                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
    </div>
        
        <!-- end row -->
        
    </div> <!-- container -->

@endsection
@section('script')
<script src="{{url('assets/libs/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('assets/libs/datatables/dataTables.bootstrap4.js')}}"></script>
<script src="{{url('assets/libs/datatables/dataTables.responsive.min.js')}}"></script>
<script src="{{url('assets/libs/datatables/responsive.bootstrap4.min.js')}}"></script>
<script src="{{url('assets/libs/datatables/dataTables.buttons.min.js')}}"></script>
<script src="{{url('assets/libs/datatables/buttons.bootstrap4.min.js')}}"></script>
<script src="{{url('assets/libs/datatables/buttons.html5.min.js')}}"></script>
<script src="{{url('assets/libs/datatables/buttons.flash.min.js')}}"></script>
<script src="{{url('assets/libs/datatables/buttons.print.min.js')}}"></script>
<script src="{{url('assets/libs/datatables/dataTables.keyTable.min.js')}}"></script>
<script src="{{url('assets/libs/datatables/dataTables.select.min.js')}}"></script>
<script src="{{url('assets/libs/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{url('assets/libs/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{url('assets/js/pages/datatables.init.js')}}"></script>
@endsection
