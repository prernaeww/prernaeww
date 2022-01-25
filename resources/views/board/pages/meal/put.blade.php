@extends('canteen.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                {{ Breadcrumbs::render('editmeal')}}
                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
            </div>
            
		</div>
	</div>
	<div class="row">
		<div class="col-xl-6">
			<div class="card">
				<div class="card-body" >
                <form action="{{ route('canteen.meal.update',$meal['id']) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name">Name<span class="text-danger">*</span></label>
                                <input type="text" name="name" parsley-trigger="change" value="{{$meal['name']}}" required placeholder="Enter name" class="form-control" id="name">
                                @error('name')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="description">Description<span class="text-danger">*</span></label>
                                <textarea required class="form-control" required placeholder="Enter description" maxlength="250" name="description" id="description">{{$meal['description']}}</textarea>
                                @error('description')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="price">Price<span class="text-danger">*</span></label>
                                <input type="text" name="price" value="{{$meal['price']}}" min="1" required placeholder="Enter price" class="form-control" id="price">
                                @error('price')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="kitchen_id">Kitchen Id<span class="text-danger">*</span></label>
                                <input type="text" name="kitchen_id" parsley-trigger="change" value="{{$meal['kitchen_id']}}" required placeholder="Enter Kitchen Id" class="form-control" id="kitchen_id">
                                @error('kitchen_id')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="image">Image<span class="text-danger">*</span></label>
                                <input type="file" data-parsley-max-file-size="5" data-parsley-trigger="change" data-parsley-filemimetypes="image/jpeg, image/png" accept="image/*" data-parsley-file-mime-types-message="Only allowed jpeg & png files" data-parsley-max-file-size="JPG|PNG" onchange="readURL1(this);" id="image" name="image" class="form-control" />
                                @error('image')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                            @php
                                $default = '/images/default.png';
                            @endphp
                                <img class="border rounded p-0"  src="{{env('AWS_S3_URL').$meal['image']}}" onerror="this.src='{{$default}}'" alt="your image" style="height: 130px;width: 130px; object-fit: cover;" id="blah1"/>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                @if (isset($meal['category']) && !empty($meal['category']))
                                <label for="name">Categories<span class="text-danger">*</span></label>
                                @foreach ($meal['category'] as $key => $meal_category)
                                <div class="row mb-2">
                                    <div class="col-lg-6">
                                        
                                        <select name="category[]" class="form-control select2" data-style="btn-light" required data-parsley-errors-container="#category_error0{{$key}}" data-placeholder="Select category">
                                            <option selected disabled></option>
                                            @if(isset($category) && !empty($category))
                                                @foreach($category as $value)
                                                @if ($value->id == $meal_category['category_id'])
                                                    <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                                                @else
                                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                @endif
                                                @endforeach
                                            @endif
                                        </select>
                                        <div id="category_error0{{$key}}"></div>
                                    </div>
                                    <div class="col-lg-4">
                                        <input name="items_number[]" type="number" class="form-control" value="{{$meal_category['items_number']}}" min="1"   required placeholder="Enter number">
                                        
                                    </div>
                                    @if ($key != 0)
                                    <div class="col-lg-2">
                                        <label class="btn btn-danger waves-effect waves-light remove" title="Remove"><i class="fas fa-times"></i></label>
                                    </div>
                                    @endif
                                    @error('name')
                                        <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="categories">
                            </div>
                            <label class="btn btn-primary waves-effect waves-light mt-2" id="add" title="Add"><i class="fas fa-plus"></i></label>
                        </div>

                    </div>

                    <div class="form-group text-right m-b-0">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">
                            Submit
                        </button>
                        <a href="{{ route('canteen.meal.index') }}" class="btn btn-secondary waves-effect m-l-5">Cancel</a>
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
        var _validFileExtensions = [".jpg", ".jpeg", ".bmp", ".gif", ".png"];
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#blah1').attr('src', e.target.result);
            $('.blah1').attr('href', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        var arrInputs = document.getElementsByTagName("input");
        for (var i = 0; i < arrInputs.length; i++) {
            var oInput = arrInputs[i];
            if (oInput.type == "file") {
                var sFileName = oInput.value;
                if (sFileName.length > 0) {
                    var blnValid = false;
                    for (var j = 0; j < _validFileExtensions.length; j++) {
                        var sCurExtension = _validFileExtensions[j];
                        if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                            blnValid = true;
                            break;
                        }
                    }
                    
                    if (!blnValid) {
                        alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                        return false;
                    }
                }
            }
        }
      
        return true;
        
    }
}

var categories_json = '<?php echo (isset($category) > 0)?json_encode($category):""; ?>';
        if(categories_json != ''){
            categories = $.parseJSON(categories_json);
            console.log(categories);
        }
        var count = parseInt('1');
        
        $(document).on('click','#add',function(){

            if(categories_json == ''){
                Notiflix.Notify.Warning('Firstly, setup category managent to create meal.');
            }else{

                
                var row = 
                        '<div class="row mb-1">'+
                                '<div class="col-lg-6">'+
                                    '<select name="category[]" class="form-control select2" data-style="btn-light" required data-parsley-errors-container="#category_error'+count+'" data-parsley-errors-container="#category_error0" data-placeholder="Select category" id="category'+count+'">'+
                                        '<option selected disabled></option>'+
                                    '</select>'+
                                    '<div id="category_error'+count+'"></div>'+
                                '</div>'+
                                '<div class="col-lg-4">'+
                                    '<input name="items_number[]" type="number" class="form-control" value="1" min="1" required placeholder="Enter number">'+
                                '</div>'+
                                '<div class="col-lg-2">'+
                                    '<label class="btn btn-danger waves-effect waves-light remove" title="Remove"><i class="fas fa-times"></i></label>'+
                                '</div>'+
                            '</div>';

                
                $('.categories').append(row);
                $(".select2").select2();

                $.each(categories, function (key, val){
                    
                    var newOption = new Option(val.name, val.id, false, false);
                    $('#category'+count).append(newOption);
                });
                count = count + 1;

                
            }
            
        });


        $(document).on('click','.remove',function(){
            $(this).closest(".row").remove();
        });
</script>
@endsection
@section('script-bottom')
<script type="text/javascript">
window.Parsley.addValidator('allowImage', {
  validateString: function(_value, ext, parsleyInstance) {
    if (!window.FormData) {
      alert('You are making all developpers in the world cringe. Upgrade your browser!');
      return true;
    }
    var files = parsleyInstance.$element[0].files;
    return false;
  },
  requirementType: 'string', 
  messages: {
    en: 'This file should not be larger than %s Kb',
    fr: 'Ce fichier est plus grand que %s Kb.'
  }
});
</script>
@endsection