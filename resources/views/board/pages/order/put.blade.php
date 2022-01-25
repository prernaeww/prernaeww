@extends('board.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                {{ Breadcrumbs::render('editinprocess')}}
                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
            </div>
            
		</div>
	</div>
    @include('board.include.flash-message')
	<div class="row">
		<div class="col-xl-6">
			<div class="card">
				<div class="card-body" >
                <form method="POST" enctype="multipart/form-data" action="{{route('board.order.date.update')}} ">
                @csrf
                @method('POST')
                <input type="hidden" name="order_dates_id" id ="order_dates_id" value="{{$order_dates['id']}}">
                <input type="hidden" name="order_id" id ="order_id" value="{{$order_dates['order_id']}}">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="date">Date<span class="text-danger">*</span></label>
                                <input type="text" name="date" id="disable-datepicker" parsley-trigger="change" value="{{$order_dates['date']}}" required placeholder="Enter Date" class="form-control">
                                @error('date')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="day">Day<span class="text-danger">*</span></label>
                                <input type="text" name="day" parsley-trigger="change" value="{{$order_dates['day']}}" readonly placeholder="Enter day" class="form-control" id="day">
                                @error('day')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="oproduct">Products<span class="text-danger">*</span></label>
                                <div class="row"> 
                                    @foreach ($order_dates['order_products'] as $key => $oproduct)
                                    <div class="col-9"> 
                                        <input type="text" name="oproduct[]" parsley-trigger="change" value="{{$oproduct['product_name']}}" readonly placeholder="Enter oproduct" class="form-control" id="oproduct{{$key}}">
                                    </div>
                                    <div class="col-3"> 
                                        <button  type="button" class="btn btn-primary btn-xs waves-effect waves-light mt-1 edit" data-id="{{$oproduct['category_id']}}" data-product-id = "{{$oproduct['id']}}" data-user_id="{{$order['customer_id']}}"data-toggle="modal" data-target=".bs-example-modal-center" >Edit</button>
                                    </div>
                                    @endforeach
                                </div>
                                @error('oproduct')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-right m-b-0">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">
                            Change Date
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

    <div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="myCenterModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myCenterModalLabel">Edit</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="POST" action="{{route('board.order.item.update')}}">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <select name="product" class="form-control select2" id="products" data-width="100%" data-style="btn-light" required data-parsley-errors-container="#product_error0" data-placeholder="Select Item">
                                    <option selected disabled></option>
                                </select>
                                <div id="product_error0"></div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="product_id" id ="product_id" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-xs btn-primary">Change</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
@endsection
@section('script')
<script type="text/javascript">
function rmydays(date) {
    // console.log(date);
    return (date.getDay() === 5 || date.getDay() === 6);
}
function convert(str) {
  var date = new Date(str),
    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
    day = ("0" + date.getDate()).slice(-2);
  return [date.getFullYear(), mnth, day].join("-");
}
function rmySpecificdays(date) {
    var rdatedData = <?php echo json_encode($hide_dates);?>;
    rdatedData = rdatedData.includes(convert(date));
        return rdatedData;
}
$(function() {
    $('#disable-datepicker').flatpickr({
        
        disable:[rmySpecificdays,rmydays],
        dateFormat: "Y-m-d",
        minDate: "today",
    });
});
var weekday = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
$( "#disable-datepicker" ).change(function() {
    var data = $( "#disable-datepicker" ).val();
    data = new Date(data);
    $("#day").val(weekday[data.getDay()]);
});

var products = `<?php echo (!empty($products))?json_encode($products):''; ?>`;
products = $.parseJSON(products);
console.log(products);

$(document).on('click',".edit", function(){
    $('#products').find('option').remove().end();
    if(products != ''){

        var id = $(this).data("id");
        var productid = $(this).data("product-id");
        $("#product_id").val(productid);

        console.log(productid);
        $.each(products, function (key, val){
            if(val.category_id == id){
                var newOption = new Option(val.name, val.id, false, false);
                console.log(newOption);
                $('#products').append(newOption).trigger('change');
            }
        });

        $('#products').val('').trigger('change');
    }
    $(".select2").select2();
});
</script>
@endsection