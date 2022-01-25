@extends('store.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                {{ Breadcrumbs::render('productview')}}
                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
            </div>
		</div>
	</div>
	<div class="row">
		<div class="col-xl-12">
			<div class="card">
				<div class="card-body" >
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tbody>
                            <tr>
                                <th class="text-nowrap" scope="row">Item Code</th>
                                <td colspan="5">{{$product->product->item_code}}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap" scope="row">Name</th>
                                <td colspan="5">{{$product->product->name}}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap" scope="row">Category Name</th>
                                <td colspan="5">{{$product->product->category->name}}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap" scope="row">Stock</th>
                                <td colspan="5">{{$product->stock}}</td>
                            </tr>

                            <tr>
                                <th class="text-nowrap" scope="row">Quantity</th>
                                <td colspan="5">{{$product->product->quantity}} {{$product->product->measurement_name}}</td>
                            </tr>

                            <tr>
                                <th class="text-nowrap" scope="row">Age </th>
                                <td colspan="5">{{$product->product->age}}</td>
                            </tr>

                            <tr>
                                <th class="text-nowrap" scope="row">Proof </th>
                                <td colspan="5">{{$product->product->proof}}%</td>
                            </tr>

                            <tr>
                                <th class="text-nowrap" scope="row">Previous Price Retail </th>
                                <td colspan="5">${{number_format((float)$product->product->previous_price_retail, 2, '.', '')}}
                                </td>
                            </tr>
                                
                            <tr>
                                <th class="text-nowrap" scope="row">Current Price Retail </th>
                                <td colspan="5">${{number_format((float)$product->product->current_price_retail, 2, '.', '')}}</td>
                            </tr>

                            <tr>
                                <th class="text-nowrap" scope="row">Previous Price business </th>
                                <td colspan="5">${{number_format((float)$product->product->previous_price_business, 2, '.', '')}}</td>
                            </tr>

                            <tr>
                                <th class="text-nowrap" scope="row">Current Price business </th>
                                <td colspan="5">${{number_format((float)$product->product->current_price_business, 2, '.', '')}}</td>
                            </tr>

                            <tr>
                                <th class="text-nowrap" scope="row">Retail Discount </th>
                                <td colspan="5">{{number_format((float)$product->product->retail_discount, 2, '.', '')}}%</td>
                            </tr>

                             <tr>
                                <th class="text-nowrap" scope="row">Business Discount </th>
                                <td colspan="5">{{number_format((float)$product->product->business_discount, 2, '.', '')}}%</td>
                            </tr>

                            <tr>
                                <th class="text-nowrap" scope="row">Image</th>
                                <td colspan="5"><img class="card-img-top" style="    height: 100px;width: 100px;object-fit: scale-down;" src="{{$product->product->image}}" alt="Card image cap"></td>
                            </tr>
                           


                    
                            </tbody>
                        </table>
                    </div>
                </div>
			</div>	
		</div>
	</div>
   
</div>


@endsection
