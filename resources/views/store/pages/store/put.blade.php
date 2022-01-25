@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    {{ Breadcrumbs::render('editstore')}}
                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body" >
                <form action="{{ route('admin.store.update',$user->id) }}" method="POST"  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                    <div class="row">

                        <div class="col-6">
                            <div class="form-group">
                                <label for="parent_id">Select Board<span class="text-danger">*</span></label>
                                <select class="form-control select2" data-parsley-errors-container="#parent_error" required name="parent_id" id="parent_id" data-placeholder="Select Board">
                                    <option selected disabled></option>
                                    @foreach($data as $board)
                                    <option value="{{$board->id}}" {{$user->parent_id == $board->id  ? 'selected' : ''}}>{{$board->first_name}}</option>
                                    @endforeach
                                    
                                </select>
                                <div id="parent_error"></div>
                                @error('parent_id')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-6">
                            <div class="form-group">
                                <label for="first_name">First Name<span class="text-danger">*</span></label>
                                <input type="text" name="first_name" parsley-trigger="change" value="{{$user->first_name}}" required placeholder="Enter First Name" class="form-control" id="first_name">
                                @error('first_name')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="last_name">Last Name<span class="text-danger">*</span></label>
                                <input type="text" name="last_name" parsley-trigger="change" value="{{$user->last_name}}" required placeholder="Enter Last Name" class="form-control" id="last_name">
                                @error('last_name')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                      
                        <div class="col-6">
                            <div class="form-group">
                                <label for="email">Email address<span class="text-danger">*</span></label>
                                <input type="email" name="email" parsley-trigger="change" value="{{$user->email}}" required placeholder="Enter email" class="form-control" id="email" readonly>
                                @error('email')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="email">Start Time<span class="text-danger">*</span></label>
                                <input type="time" name="start_time" parsley-trigger="change" value="{{$user->start_time}}" required class="form-control" id="start_time">
                                @error('start_time')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="email">End Time<span class="text-danger">*</span></label>
                                <input type="time" name="end_time" parsley-trigger="change" value="{{$user->end_time}}" required class="form-control" id="end_time">
                                @error('end_time')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        
                        <div class="col-6">
                            <div class="form-group">
                                <label for="city">Address<span class="text-danger">*</span></label>
                                <input type="text" name="address" parsley-trigger="change" value="{{$user->address}}" required placeholder="Enter Address" class="form-control" id="searchTextField">
                                
                                @error('address')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="city">Zipcode<span class="text-danger">*</span></label>
                                <input type="text" name="zipcode" parsley-trigger="change" value="{{$user->zipcode}}" required placeholder="Enter Zipcode" class="form-control" >
                                
                                @error('zipcode')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                         <div class="col-6">
                            <div class="form-group">
                                <label for="city">Latitude<span class="text-danger">*</span></label>
                                <input type="text" name="latitude" parsley-trigger="change" value="{{$user->latitude}}" required placeholder="Enter Latitude" class="form-control">
                                
                                @error('latitude')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                         <div class="col-6">
                            <div class="form-group">
                                <label for="city">Longitude<span class="text-danger">*</span></label>
                                <input type="text" name="longitude" parsley-trigger="change" value="{{$user->longitude}}" required placeholder="Enter Longitude" class="form-control">
                                
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
                                            <input type="checkbox" required data-parsley-errors-container="#service"  name="delivery_type[]"  value="1" class="custom-control-input" id="customCheck1" <?php echo ($user->delivery_type == '1' || $user->delivery_type == '3')?"checked":''; ?>>
                                            <label class="custom-control-label" for="customCheck1">Instore</label>
                                    </div>
                                    <div class="pl-2 pr-3 custom-control custom-checkbox">
                                            <input type="checkbox" required  data-parsley-errors-container="#service" name="delivery_type[]"  value="2" class="custom-control-input" id="customCheck2" <?php echo ($user->delivery_type == '2' || $user->delivery_type == '3')?"checked":''; ?>>
                                            <label class="custom-control-label" for="customCheck2">Curbside</label>
                                    </div>              
                                </div>
                                <div id="service"></div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="phone">Phone<span class="text-danger">*</span></label>
                                <input type="text" name="phone" parsley-trigger="change" value="{{$user->phone}}" required class="form-control" id="phone"  readonly>
                                @error('phone')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                       
                        <div class="col-6">
                            <div class="form-group">
                                <label for="image">Image<span class="text-danger">*</span></label>
                                <input type="file" data-parsley-trigger="change" data-parsley-max-file-size="5" data-parsley-filemimetypes="image/jpeg, image/png" accept="image/*" data-parsley-file-mime-types-message="Only allowed jpeg & png files" onchange="readURL1(this);" id="image" name="image" class="form-control" />
                                @error('image')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                                <br>
                                 @php
                                $default = '/images/default.png';
                            @endphp
                                <img class="border rounded p-0"  src="{{env('AWS_S3_URL').$user->profile_picture}}" onerror="this.src='{{$default}}'" alt="your image" style="height: 130px;width: 130px; object-fit: cover;" id="blah1"/>
                            </div>
                        </div>
                        
                    </div>

                    <div class="form-group text-right m-b-0">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">
                            Submit
                        </button>
                        <a href="{{ route('admin.store.index') }}" class="btn btn-secondary waves-effect m-l-5">Cancel</a>
                        <!-- <button type="reset" class="btn btn-secondary waves-effect m-l-5">
                            Cancel
                        </button> -->
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
</script>
@endsection