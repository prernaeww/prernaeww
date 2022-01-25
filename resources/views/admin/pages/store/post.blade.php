@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    {{ Breadcrumbs::render('addstore')}}
                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
            </div>
		</div>
	</div>
	<div class="row">
		<div class="col-xl-12">
			<div class="card">
				<div class="card-body" >
                <form action="{{ route('admin.store.store') }}" method="POST"  enctype="multipart/form-data">
                @csrf
                @method('POST')

                    <div class="row">

                        <div class="col-6">
                            <div class="form-group">
                                <label for="parent_id">Select Board<span class="text-danger">*</span></label>
                                <select class="form-control select2" data-parsley-errors-container="#parent_error" required name="parent_id" id="parent_id" data-placeholder="Select Board">
                                    <option selected disabled></option>
                                    @if(isset($data) && count($data) > 0)
                                    @foreach($data as $board)
                                        @php
                                            $selected = "";
                                        @endphp
                                        @if(old('parent_id') == $board->id)
                                            @php
                                                $selected = "selected";
                                            @endphp
                                        @endif
                                    <option value="{{$board->id}}" {{$selected }}>{{$board->first_name}}</option>
                                    @endforeach
                                    @endif
                                    
                                </select>
                                <div id="parent_error"></div>
                                @error('parent_id')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="first_name">Store Name<span class="text-danger">*</span></label>
                                <input type="text" name="first_name" parsley-trigger="change" value="{{old('first_name')}}" required placeholder="Enter Store Name" class="form-control" id="first_name">
                                @error('first_name')
                                    <div class="error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        

                        <div class="col-6">
                            <div class="form-group">
                                <label for="email">Start Time<span class="text-danger">*</span></label>
                                <input type="text" placeholder="Enter Start Time" name="start_time" parsley-trigger="change" value="{{old('start_time')}}" required class="form-control flatpickr-input" id="basic-timepicker" autocomplete="off" style="background-color:white;" >
                                <!-- <input type="text" id="basic-timepicker" class="form-control flatpickr-input " placeholder="Basic timepicker" readonly="readonly"> -->
                               
                                @error('start_time')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="email">End Time<span class="text-danger">*</span></label>
                                <input type="text" placeholder="Enter End Time"  name="end_time" parsley-trigger="change" value="{{old('end_time')}}" required class="form-control flatpickr-input" id="basic-timepicker" autocomplete="off" style="background-color:white;" >
                                <!-- <input type="text" id="basic-timepicker" class="form-control flatpickr-input" placeholder="Basic timepicker" readonly="readonly"> -->
                                @error('end_time')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                         <div class="col-6">
                            <div class="form-group">
                                <label for="email">Email Address<span class="text-danger">*</span></label>
                                <input type="email" name="email" parsley-trigger="change" value="{{old('email')}}" required placeholder="Enter Email Address" class="form-control" id="email">
                                @error('email')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="city">Address<span class="text-danger">*</span></label>
                                <input type="text" id="autocomplete" onFocus="geolocate()" name="address" parsley-trigger="change" value="{{old('address')}}" required placeholder="Enter Address" class="form-control" >
                                <input type="hidden" id="administrative_area_level_2" name="zipcode">
                                <input type="hidden" id="administrative_area_level_1" name="state">
                                @error('address')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="zipcode">Zipcode<span class="text-danger">*</span></label>
                                <input type="text" name="zipcode" id="zipcode" parsley-trigger="change" value="{{old('zipcode')}}" required placeholder="Enter Zipcode" class="form-control zipcode">
                                @error('zipcode')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group" id="latitudeArea">
                                <label for="latitude">Latitude<span class="text-danger">*</span></label>
                                <input type="text" name="latitude" id="latitude" parsley-trigger="change" value="{{old('latitude')}}" required placeholder="Enter Latitude" class="form-control latitude" >
                                @error('latitude')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group" id="longtitudeArea">
                                <label for="longitude">Longitude<span class="text-danger">*</span></label>
                                <input type="text" name="longitude" id="longitude" parsley-trigger="change" value="{{old('longitude')}}" required placeholder="Enter Longitude" class="form-control longitude">
                                @error('longitude')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group ">
                                <label for="grades">Available Service<span class="text-danger">*</span></label>
                                <div class="row ml-2 d-flex flex-row">

                                    <div class="pl-2 pr-3 custom-control custom-checkbox">
                                            <input type="checkbox" required  data-parsley-errors-container="#service"  name="delivery_type[]"  value="1" @if(is_array(old('delivery_type')) && in_array(1, old('delivery_type'))) checked @endif class="custom-control-input" id="customCheck1">
                                            <label class="custom-control-label" for="customCheck1">Instore</label>
                                    </div>
                                    <div class="pl-2 pr-3 custom-control custom-checkbox">
                                            <input type="checkbox" required  data-parsley-errors-container="#service" name="delivery_type[]"  value="2" @if(is_array(old('delivery_type')) && in_array(2, old('delivery_type'))) checked @endif class="custom-control-input" id="customCheck2">
                                            <label class="custom-control-label" for="customCheck2">Curbside</label>
                                    </div>            
                                </div>
                                <div id="service"></div>
                            </div>
                        </div>
                    </div>

                    
                     <div class="row">

                        <div class="col-6">
                             <div class="form-group">
                                <label for="phone">Phone<span class="text-danger">*</span></label>
                               <!--  <input type="text" name="phone" parsley-trigger="change"  required  class="form-control" id="phone" minlength="10"> -->
                               <input type="text" name="phone" value="{{old('phone')}}" parsley-trigger="change" placeholder="1(XXX)XXX-XXXX" data-mask="1(999)999-9999" required  class="form-control number" data-parsley-maxlength="14"  data-parsley-minlength="14" data-parsley-minlength-message="This value must be at least 14 characters" data-parsley-maxlength="14" data-parsley-maxlength-message="This value must be at least 14 characters">
                                @error('phone')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-6">
                           
                            <div class="form-group ">
                                <label for="image">Image<span class="text-danger">*</span></label>
                                <input type="file" required data-parsley-trigger="change"  data-parsley-max-file-size="5" data-parsley-filemimetypes="image/jpeg, image/png" accept="image/*" data-parsley-file-mime-types-message="Only allowed jpeg & png files" onchange="readURL1(this);" id="image" name="image" class="form-control" />
                                @error('image')
                                    <div class="error">{{$message}}</div>
                                @enderror
                                <br>
                                @php
                                    $default = '/images/default.png';
                                @endphp
                                <img class="border rounded p-0"  src="" onerror="this.src='{{$default}}'" alt="your image" style="height: 130px;width: 130px; object-fit: cover;" id="blah1"/>
                            </div>
                        </div>   
                    </div>
                    
                    <div class="form-group text-right m-b-0">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">
                            Submit
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
</div>
@endsection
@section('script')
<script type="text/javascript">
function readURL1(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#blah1').attr('src', e.target.result);
            $('.blah1').attr('href', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// var map_key= <?php echo "fdff"; ?>
</script>

<script src='https://maps.googleapis.com/maps/api/js?key={{$mapkey}}&libraries=places&callback=initAutocomplete' async defer></script>

<script>

  var placeSearch, autocomplete;

  var componentForm = {

        administrative_area_level_2: 'long_name',
        administrative_area_level_1: 'long_name',
     
      };



  function initAutocomplete() {
    autocomplete = new google.maps.places.Autocomplete(
       (document.getElementById('autocomplete')),
        {types: ['geocode'] , componentRestrictions: {country: "US"} });
    autocomplete.addListener('place_changed', fillInAddress);
  }

  function fillInAddress() {
    var place = autocomplete.getPlace();

     for (var component in componentForm) {
      document.getElementById(component).value = '';
      document.getElementById(component).disabled = false;
    }

   if (typeof place.address_components != "undefined" || place.address_components != null){

    $('#latitude').val(place.geometry.location.lat());
    $('#longitude').val(place.geometry.location.lng());
    $(".zipcode").val("");
    // console.log(place.address_components);
        for (var i = 0; i < place.address_components.length; i++) {
            for (var j = 0; j < place.address_components[i].types.length; j++){
                console.log(place.address_components[i]);
                if (place.address_components[i].types[j] == "postal_code") {
                    $('.zipcode').val(place.address_components[i].long_name);
                }
                if (place.address_components[i].types[j] == "latitude") {
                    $('.latitude').val(place.address_components[i].long_name);
                }
                if (place.address_components[i].types[j] == "longitude") {
                    $('.longitude').val(place.address_components[i].long_name);
                }
                
            }
            var addressType = place.address_components[i].types[0];
            if (componentForm[addressType]) {
                var val = place.address_components[i][componentForm[addressType]];
                document.getElementById(addressType).value = val;
            }
        }
    }
  }

  function geolocate() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var geolocation = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
          zip: position.coords.zipcode,
        };

        // console.log(geolocation);
        var circle = new google.maps.Circle({
          center: geolocation,
          radius: position.coords.accuracy
        });
        autocomplete.setBounds(circle.getBounds());
      });
    }
  }
</script>
<script type="text/javascript">
    $(document).ready(function(){
       $('#autocomplete').on("cut copy paste",function(e) {
          e.preventDefault();
       });
    });

</script>
@endsection