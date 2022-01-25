<style type="text/css">
    .select2-container--default{
        width: 100% !important;
    }
</style>
@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">

                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
            </div>
		</div>
	</div>
    @include('admin.include.flash-message')
	<div class="row">
		<div class="col-xl-6">
			<div class="card">
				<div class="card-body" >
                <form action="" method="POST"  enctype="multipart/form-data">
                @csrf

                    <div class="row">     
                        <div class="col-12" >
                           <div class="form-group">
                              <label for="product_name">Select {{$type}}</label>
                              <select class="form-control " id="user_type" name="user_type" required  data-parsley-errors-container="#select_type_error" >
                                 <!-- <option value="">Please Select {{$type}}</option> -->
                                    <option value="all">All {{$type}}</option>
                                    <option value="select">Select {{$type}}</option>
                                 </select>
                              <div id="select_type_error"></div>
                           </div>                           
                        </div>

                        <div class="col-12" >
                            <div class="form-group" style="display: none;" id='select_user'>
                                <label for="user_ids">{{$type}}<span class="text-danger">*</span></label><br>
                                <select class="form-control select2" name="user_ids[]" id="user_ids"  multiple="" data-parsley-errors-container="#type">
                                    <option value="" disabled=""> Select {{$type}} </option>
                                    @foreach($users as $value)
                                    <option value="{{$value->id}}">{{$value->first_name}} {{$value->last_name}}</option>
                                    @endforeach
                                </select>
                                <div id="type"></div>
                                @error('user_ids')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="title">Title<span class="text-danger">*</span></label>
                                <input type="text" name="title" parsley-trigger="change"  required placeholder="Enter Title" class="form-control" id="title">
                                @error('title')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="description">Description<span class="text-danger">*</span></label>
                                <!-- <textarea name="description" parsley-trigger="change" required placeholder="Enter Description" class="form-control" id="description"></textarea> -->
                                <textarea class="ckeditor form-control" name="description" id="description" placeholder="Content" required></textarea>
                                <!-- <input type="text" name="description" parsley-trigger="change" required placeholder="Enter Description" class="form-control" id="description"> -->
                                @error('description')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>                    
                    
                    </div>

                    <div class="form-group text-right m-b-0">
                        <button class="btn btn-primary waves-effect waves-light mr-2" type="submit">
                            Submit
                        </button><!-- 
                        <button type="reset" class="btn btn-secondary waves-effect m-l-5">
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
<script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    $('.ckeditor').ckeditor();
});
$('#user_type').on('change', function() {  
  if (this.value == 'all')
  {
      // $("#select").hide();
      // $("#all").hide();          

      $("#select_user").hide();
      $("#user_ids").removeAttr('required');
  } else
  {      
      $("#select_user").show();
      $("#user_ids").attr('required', 'required');
  }
});


</script>

@endsection
