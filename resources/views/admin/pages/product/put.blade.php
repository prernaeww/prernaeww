@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                {{ Breadcrumbs::render('admin-add-product')}}
                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
            </div>
        </div>
    </div>
    <?php
    $default_img_url = asset('images/logo.png');
    ?>
    <style type="text/css">
        .item-img{
            object-fit: cover;
            width: 150px;
        }
    </style>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body" >
                <form action="{{ route('admin.product.update',$product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                    <div class="row">

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="item_code">Item Code<span class="text-danger">*</span></label>
                                <input type="text" name="item_code" parsley-trigger="change" value="{{value($product->item_code)}}" required placeholder="Enter Item Code" class="form-control" id="item_code">
                                @error('item_code')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Name<span class="text-danger">*</span></label>
                                <input type="text" name="name" parsley-trigger="change" value="{{value($product->name)}}" required placeholder="Enter Name" class="form-control" id="name">
                                @error('name')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="quantity">Quantity<span class="text-danger">*</span></label>
                                <input type="text" name="quantity" parsley-trigger="change" value="{{value($product->quantity)}}" required placeholder="Enter Quantity Ex. 750" class="form-control quantity" id="quantity" data-parsley-errors-container="#quantity_error">
                                <div id="quantity_error"></div>
                                @error('quantity')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="measurement_id">Select Measurement<span class="text-danger">*</span></label>
                                <select class="form-control select2" data-parsley-errors-container="#measurement_error" required name="measurement_id" id="measurement_id" data-placeholder="Select Measurement">
                                    <option selected disabled></option>
                                    @if(isset($measurement) && count($measurement) > 0)
                                        @foreach($measurement as $data)
                                            @php
                                            $selected = '';
                                            if(old('measurement_id') !== NULL){
                                                $selected = 'selected';
                                            }
                                            @endphp
                                            <option @if($product->measurement_id == $data['id']) selected @endif value="{{$data['id']}}" {{ $selected }}>{{$data['name']}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div id="measurement_error"></div>
                                @error('measurement_id')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="category_id">Select Category<span class="text-danger">*</span></label>
                                <select class="form-control select2" data-parsley-errors-container="#category_error" required name="category_id" id="category_id" data-placeholder="Select Category">
                                    <option selected disabled></option>
                                    @if(isset($category) && count($category) > 0)
                                        @foreach($category as $data)
                                            @php
                                            $selected = '';
                                            if(old('measurement_id') !== NULL){
                                                $selected = 'selected';
                                            }
                                            @endphp
                                            <option value="{{$data['id']}}"  @if($product->category_id == $data['id']) selected @endif>{{$data['name']}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div id="category_error"></div>
                                @error('category_id')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="family_id">Select Size Family</label>
                                <select class="form-control select2" data-parsley-errors-container="#family_error" name="family_id" id="family_id" data-placeholder="Select Size Family">
                                    
                                    <option value="0">Select Size Family</option>
                                    @if(isset($family) && count($family) > 0)
                                        @foreach($family as $data)
                                            @php
                                            $selected = '';
                                            if(old('family_id') !== NULL){
                                                $selected = 'selected';
                                            }
                                            @endphp
                                            <option value="{{$data['id']}}" @if($product->family_id == $data['id']) selected @endif>{{$data['name']}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div id="family_error"></div>
                                @error('family_id')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="age">Age</label>
                                <input type="text" name="age" parsley-trigger="change" value="{{value($product->age)}}" placeholder="Enter Age" class="form-control age" id="age" data-parsley-errors-container="#age_error">
                                <div id="age_error"></div>
                                @error('age')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="proof">Proof</label>
                                <input type="text" name="proof" parsley-trigger="change" value="{{value($product->proof)}}" placeholder="Enter Proof" class="form-control perc" id="proof" data-parsley-errors-container="#proof_error">
                                <div id="proof_error"></div>
                                @error('proof')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label for="previous_price_retail">Previous Price Retail<span class="text-danger">*</span></label>
                                <input type="text" name="previous_price_retail" parsley-trigger="change" value="{{value($product->previous_price_retail)}}" required placeholder="Enter Price" class="form-control price-touchspin" id="previous_price_retail" data-parsley-errors-container="#previous_price_retail_error" min="0">
                                <div id="previous_price_retail_error"></div>
                                @error('previous_price_retail')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label for="current_price_retail">Current Price Retail<span class="text-danger">*</span></label>
                                <input type="text" name="current_price_retail" parsley-trigger="change" value="{{value($product->current_price_retail)}}" required placeholder="Enter Price" class="form-control price-touchspin" id="current_price_retail" data-parsley-errors-container="#current_price_retail_error" min="0">
                                <div id="current_price_retail_error"></div>
                                @error('current_price_retail')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="retail_discount">Retail Discount (%)</label>
                                <input type="text" name="retail_discount" value="{{value($product->retail_discount)}}" placeholder="0" class="form-control" id="retail_discount" readonly>
                                @error('retail_discount')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label for="previous_price_retail">Previous Price Business<span class="text-danger">*</span></label>
                                <input type="text" name="previous_price_business" parsley-trigger="change" value="{{value($product->previous_price_business)}}" required placeholder="Enter Price" class="form-control price-touchspin" id="previous_price_business" data-parsley-errors-container="#previous_price_business_error" min="0">
                                <div id="previous_price_business_error"></div>
                                @error('previous_price_business')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label for="current_price_business">Current Price Business<span class="text-danger">*</span></label>
                                <input type="text" name="current_price_business" parsley-trigger="change" value="{{value($product->current_price_business)}}" required placeholder="Enter Price" class="form-control price-touchspin" id="current_price_business" data-parsley-errors-container="#current_price_business_error" min="0">
                                <div id="current_price_business_error"></div>
                                @error('current_price_retail')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="business_discount">Business Discount (%)</label>
                                <input type="text" value="{{value($product->business_discount)}}" placeholder="0" name="business_discount" class="form-control"  id="business_discount" readonly>
                                @error('business_discount')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file"  data-parsley-max-file-size="5" data-parsley-trigger="change" data-parsley-filemimetypes="image/jpeg, image/png" accept="image/*" data-parsley-file-mime-types-message="Only allowed jpeg & png files" onchange="readURL(this);" id="image" name="image" class="form-control" />
                                @error('image')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                            @php
                                $default = '/images/default.svg';
                            @endphp
                                <img class="border rounded p-0"  src="{{$product->image}}" onerror="this.src='{{$default}}'" alt="your image" style="height: 130px;width: 130px;object-fit: contain;" id="blah1"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-right m-b-0">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">
                            Submit
                        </button>
                        <a href="{{ route('admin.product.index') }}" class="btn btn-secondary waves-effect m-l-5">Cancel</a>
                    </div>
                </form>
                </div>
            </div>  
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript">


function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#blah1').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

$(document).ready(function(){
    $(".age").TouchSpin({
        min: 0,
        max: 1000000000
    });
    $(".quantity").TouchSpin({
        min: 0,
        max: 1000000000,
        step: 0.1,
        decimals: 2,
        boostat: 5,
        maxboostedstep: 10,
        forcestepdivisibility: 'none'
    });
    $(".perc").TouchSpin({
        min: 0,
        max: 100,
        step: 0.1,
        decimals: 2,
        boostat: 5,
        maxboostedstep: 10,
        postfix: '%'
    });
    $(".price-touchspin").TouchSpin({
        min: 0,
        max: 1000000000,
        step: 0.1,
        stepinterval: 50,
        decimals: 2,
        maxboostedstep: 10000000,
        forcestepdivisibility: 'none',
        prefix: '$'
    });

    calc_retail_perc($('#previous_price_retail').val(), $('#current_price_retail').val(), $('#retail_discount'));
    calc_retail_perc($('#previous_price_business').val(), $('#current_price_business').val(), $('#business_discount'));
});

$(document).on('input propertychange paste','#previous_price_retail',function(){
    var previous_price_retail =  $(this).val();
    var current_price_retail =  $('#current_price_retail').val();

    calc_retail_perc(previous_price_retail, current_price_retail, $('#retail_discount'));
});

$(document).on('input propertychange paste','#current_price_retail',function(){
    var current_price_retail =  $(this).val();
    var previous_price_retail =  $('#previous_price_retail').val();

    calc_retail_perc(previous_price_retail, current_price_retail, $('#retail_discount') );
});

$(document).on('input propertychange paste','#previous_price_business',function(){
    var previous_price_business =  $(this).val();
    var current_price_business =  $('#current_price_business').val();

    calc_retail_perc(previous_price_business, current_price_business, $('#business_discount'));
});

$(document).on('input propertychange paste','#current_price_business',function(){
    var current_price_business =  $(this).val();
    var previous_price_business =  $('#previous_price_business').val();

    calc_retail_perc(previous_price_business, current_price_business, $('#business_discount'));
});

function calc_retail_perc(previous_price_retail, current_price_retail, discount_field) {
    var previous_price_retail = parseFloat(previous_price_retail);
    var current_price_retail = parseFloat(current_price_retail);
    discount_field.val('0');
    console.log('previous_price_retail '+previous_price_retail);
    console.log('current_price_retail '+current_price_retail);

    if(previous_price_retail != 0 && current_price_retail != 0 && !isNaN(previous_price_retail && !isNaN(current_price_retail)))
    {
        if(current_price_retail < previous_price_retail){
            var perc = (current_price_retail / previous_price_retail) * 100;
            var total_perc = 100 - perc;
            //total_perc = total_perc.toFixed(2);
            total_perc = Math.round(total_perc);
            console.log(total_perc);
            discount_field.val(total_perc);
        }
        
        console.log('total_perc '+total_perc);
    }
    
}

</script>
@endsection
