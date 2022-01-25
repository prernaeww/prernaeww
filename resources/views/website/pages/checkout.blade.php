@extends('website.layouts.master')
@section('content')
<style type="text/css">
    .parsley-errors-list > li:before
    {
        display: none;
    }
</style>
@include('website.layouts.nav')
<section class="cart-page my-5 pb-lg-5">
	<div class="container">
        @include('website.include.flash-message')
        <div class="mb-5 pb-3 border-bottom d-flex justify-content-between align-items-center">
            <h3 class="t-blue font-700">Place Order</h3>
        </div>
        <form method="GET" action="{{route('save_card')}}" >
     
        <div class="row">
            <div class="col-md-12 col-xl-12 px-3 mx-auto">
                <input type="hidden"  name="cart_id" value="{{$data['id']}}">
                <input type="text" value="{{Auth::user()->first_name}} {{Auth::user()->last_name}}" name="name" placeholder="Name" class="w-100 modal-input-field" required>

               <!--  <input type="number" value="{{Auth::user()->phone}}" name="number" placeholder="Number" class="w-100 modal-input-field" required> -->

                <input type="text" name="number"  value="{{Auth::user()->phone_formatted}}" parsley-trigger="change" placeholder="1(XXX)XXX-XXXX" data-mask="1(999)999-9999" class="w-100 modal-input-field number" data-parsley-maxlength="14"  data-parsley-minlength="14" data-parsley-minlength-message="This value must be at least 14 characters" data-parsley-maxlength="14" data-parsley-maxlength-message="This value must be at least 14 characters" data-parsley-errors-container="#phone_error" required>

                <textarea placeholder="Pickup Notes"  name="pickup_notes" rows="4" class="w-100 modal-input-field"></textarea>


                <div class="form-group card-header border border-rounded bg-white p-3 border-r10">
                    <label>Choose Pickup Method<span class="text-danger">*</span></label>
                        <div class="pick-up-method">
                            @if($data['store']['delivery_type']==1)
                            <input type="radio" name="pickup_method" id="pick-up-method1" onclick="pickupMethod(this.value)" value="1" checked>
                            <label for="pick-up-method1" class="mb-1 d-flex align-items-center relative">
                                <div class="pick-up-method1">
                                    <p class="t-grey2 mb-0">In-Store Pick-Up</p>
                                    <p class="t-grey font-16 mb-0">Order will be waiting inside the store.</p>
                                </div>
                            </label>
                            @elseif($data['store']['delivery_type']==2)
                            <input type="radio" name="pickup_method" id="pick-up-method2" onclick="pickupMethod(this.value)" value="2" checked>
                            <label for="pick-up-method2" class="d-flex align-items-center relative">
                                <div class="pick-up-method2">
                                    <p class="t-grey2 mb-0">Curbside Pick-Up</p>
                                    <p class="t-grey font-16 mb-0">Order will be brought to your car.</p>
                                </div>
                            </label>
                            @else
                            <input type="radio" name="pickup_method" id="pick-up-method1" onclick="pickupMethod(this.value)" value="1" checked>
                            <label for="pick-up-method1" class="mb-1 d-flex align-items-center relative">
                                <div class="pick-up-method1">
                                    <p class="t-grey2 mb-0">In-Store Pick-Up</p>
                                    <p class="t-grey font-16 mb-0">Order will be waiting inside the store.</p>
                                </div>
                            </label>
                            <input type="radio" name="pickup_method" id="pick-up-method2" onclick="pickupMethod(this.value)" value="2">
                            <label for="pick-up-method2" class="d-flex align-items-center relative">
                                <div class="pick-up-method2">
                                    <p class="t-grey2 mb-0">Curbside Pick-Up</p>
                                    <p class="t-grey font-16 mb-0">Order will be brought to your car.</p>
                                </div>
                            </label>
                            @endif
                        </div>
                </div>
               
                <div id="vehicle_description_div" class="mt-3">
                    <input type="text" style="display:none;" id="vehicle_description"  name="vehicle_description" placeholder="Add Vehicle Description" class="w-100 modal-input-field">
                    @error('vehicle_description')
                        <div class="error">{{ $message }}</div>
                    @enderror  
                 </div>

                <div class="mb-3 px-1 my-4">
                        <p class="t-grey font-28 text-center">Pickup at the {{$data['store']['first_name']}} location:</p>
                </div>
                <div class="form-group card-header border border-rounded bg-white p-3 border-r10">
                    <div class="">
                        <p class="mb-0 t-blue font-700"> {{$data['store']['first_name']}}</p>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body mt-2 p-0">
                                {{$data['store']['address']}}    
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-1 my-4">
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <p class="mb-0 t-grey2">Sub total:</p>
                        <p class="mb-0 t-grey2">${{$data['sub_total']}}</p>
                    </div>
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <p class="mb-0 t-grey2">Tax:</p>
                        <p class="mb-0 t-grey2">${{$data['tax']}}</p>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <p class="mb-0 t-grey2">Total:</p>
                        <p class="mb-0 t-grey2">${{$data['total']}}</p>
                    </div>
                    <p class="font-16 mt-4 text-center col-xl-8 mx-auto px-0">By placing my order I agree to the <a href="{{url('term-of-service')}}" target="_blank" title="" class="text-underline blue-link">Terms and Conditions</a> and the <a href="{{url('privacy-notice')}}" title="" target="_blank" class="text-underline blue-link">Privacy Policy</a></p>
                </div>
            </div>
            <div class="col-md-12 col-xl-12 text-center">
                <button class="btn btn-lg btn-blue2" type="submit">Place Order</button>
            </div>
        </div>
        </form>
	</div>
</section>
@endsection
@section('script')
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script type="text/javascript">

pickupMethod({{$data['store']['delivery_type']}}); 
    
function pickupMethod(val)
{
    if(val == 2)
    {
        $('#vehicle_description').show();
        $('#vehicle_description_div').show();
        $("#vehicle_description").attr("required","required");
    }else
    {
        $('#vehicle_description').hide();
        $('#vehicle_description_div').hide();
        $("#vehicle_description").removeAttr("required","required");
    }
}
 
                //========================NUMBER==========================================
$(document).ready(function() {
    //called when key is pressed in textbox
    $(".number").keypress(function(e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57))

            return false;
        return true;

    });
});
//========================NUMBER==========================================
//========================NUMBER ( . )==========================================
$(document).ready(function() {
    //called when key is pressed in textbox
    $(".number").keypress(function(evt) {
        //if the letter is not digit then display error and don't type anything
        var iKeyCode = (evt.which) ? evt.which : evt.keyCode
        if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
            return false;
        return true;
    });
});
//========================NUMBER ( . )==========================================  
</script>
@endsection