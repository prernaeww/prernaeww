@extends('website.layouts.master')
@section('content')
@include('website.layouts.nav')

<section class="cart-page my-5 pb-lg-5">
<div class="container">
@include('website.include.flash-message')
<div class="pb-3 border-bottom d-flex justify-content-between align-items-center" id="list-div-main">
<h3 class="t-blue font-700">Saved Cards</h3>
</div>
<div class="row">
<div class="container py-5">

<div class="row d-flex justify-content-center pb-5">
<div class="col-md-7 col-xl-7 mb-4 mb-md-0">

<div class="">
    <form class="pb-3">
        <div class="col-lg-12 text-center d-none" style="margin-top: 80px;" id="no-address">
            <img src="{{ URL::asset('assets/images/website/ic_no_cards.png') }}" alt="">
            <div class="mt-3 text-danger">No Card Added</div>
            <p style="color: #5E97BE; margin-top:10px;">You have not added any card
                yet.</p>
        </div>
        @if ($status)
            @if (count($data) > 0)
                @foreach ($data as $key => $carddata)
                    <div class="d-flex flex-row pb-3 total-card-count"
                        id="card_{{ $carddata['id'] }}">
                        <div class="rounded border d-flex w-100 p-3 align-items-center">
                            <div class="bg-white d-flex align-items-center float-right" style="right: 10px;">
                                <button type="button"
                                    onclick='delete_card({{ $carddata['id'] }})' ;><i
                                        class="fa fa-trash text-danger"
                                        style="top: 8px;right: 16px;" aria-hidden="true"></i>
                                </button>
                            </div>
                            <p class="pl-3 mb-0 mr-2">
                                <img src="{{ $carddata['image'] }}"></img>
                            </p>
                            <div class="ms-auto">
                                <span
                                    class="t-blue font-700">{{ $carddata['card_type'] }}</span></br>
                                <span
                                    class="text-muted">{{ $carddata['card_number'] }}</span>
                            </div>
                        </div>
                        
                    </div>


                @endforeach
            @else
                <div class="col-lg-12 text-center " style="margin-top: 80px;">
                    <img src="{{ URL::asset('assets/images/website/ic_no_cards.png') }}"
                        alt="">
                    <div class="mt-3 text-danger">No Card Added</div>
                    <p style="color: #5E97BE; margin-top:10px;">You have not added any card
                        yet.</p>
                </div>
            @endif
        @else
            <div class="col-lg-12 text-center " style="margin-top: 80px;">
                <img src="{{ URL::asset('assets/images/website/ic_no_cards.png') }}" alt="">
                <div class="mt-3 text-danger">No Card Added</div>
                <p style="color: #5E97BE; margin-top:10px;">You have not added any card
                    yet.</p>
            </div>
        @endif
    </form>

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
function delete_card(card_id) {
console.log(`card_id: `, card_id);
Notiflix.Confirm.Show(
'Confirm',
'Are you sure that you want to delete this record?',
'Yes',
'No',
function() {
$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});
$('#loader').show();
$.ajax({
type: "POST",
url: '/card-delete',
dataType: 'json',
data: {
'card_id': card_id
}, // serializes the form's elements.
success: function(data) {
$('#loader').hide();
console.log(data);
if (data.status) {
    Notiflix.Notify.Success(data.message);
    $('#card_' + card_id).removeClass('d-flex');
    $('#card_' + card_id).remove();

    var total_card_count = $(".total-card-count").length;
    console.log(`total_card_count: `, total_card_count);
    if (total_card_count == 0) {
        $('#no-address').removeClass('d-none');
    }
} else {
    Notiflix.Notify.Failure(data.message);
}
}
});
});
}
</script>
@endsection
