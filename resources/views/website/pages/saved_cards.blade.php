@extends('website.layouts.master')
@section('content')
@include('website.layouts.nav')

<section class="cart-page my-5 pb-lg-5">
	<div class="container">
        @include('website.include.flash-message')
        <div class="pb-3 border-bottom d-flex justify-content-between align-items-center" id="list-div-main">
            <h3 class="t-blue font-700">Choose Payment</h3>
        </div>
        <div class="row"> 
          <div class="container py-5">
          
            <div class="row d-flex justify-content-center pb-5">
              <div class="col-md-7 col-xl-7 mb-4 mb-md-0 p-4">
               
                <div class="">
                  <form class="pb-3">
              
                    @foreach($data as $key => $carddata)
                        <div class="d-flex flex-row pb-3">
                          <div class="d-flex align-items-center pe-2">
                          <input class="form-check-input" type="radio" name="card_id" value="{{ Crypt::encrypt($carddata['id']) }}" aria-label="..." {{ ($key == 0)?"checked":''; }}/>
                          </div>
                          <div class="rounded border d-flex w-100 p-3 align-items-center">
                            <p class="mb-0 mr-2">
                              <img src="{{$carddata['image']}}" ></img> 
                            </p>
                            <div class="ms-auto">
                              <span class="t-blue font-700">{{$carddata['card_type']}}</span></br>
                              <span class="text-muted">{{$carddata['card_number']}}</span></div>
                          </div>
                        </div>
                    @endforeach
                  </form>
            
            @php
             $user_id = Auth::user()->id;
            @endphp
                  <input onclick="proceed_to_payment();"
                    type="button"
                    value="Proceed to payment"
                    class="btn btn-primary btn-block btn-lg" style="background:#5E97BE!important;border:1px solid #5E97BE;"
                  />
            <a href="{{url('/generate_token/')}}/{{$user_id}}?payment_type=website"><label class="mb-0 mt-5 page-label mb-5 font-700" style="border-radius: 0px;cursor: pointer;"><span class="font-700">+</span> Add Card</label></a>
            
                </div>
              </div>

            </div>

          </div>
        </div>
  
	</div>
</section>
@endsection
@section('script')
<script type="text/javascript">

</script>
@endsection