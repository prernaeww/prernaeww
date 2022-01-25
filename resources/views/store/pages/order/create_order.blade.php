@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                {{ Breadcrumbs::render($breadcrumb_name)}}
                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
            </div>
		</div>
	</div>
    @include('admin.include.flash-message')
	<div class="row">
        
        <div class="col-12">
            <div class="card-box">
                <!-- Logo & title -->
                    <div class="form-group">
                        <label for="canteen_id">Type<span class="text-danger">*</span></label>
                        <select class="form-control select2" data-parsley-errors-container="#canteen_error" data-token="{{csrf_token()}}" required name="user_type" id="user_type" data-placeholder="Select Type">
                            <option value="">Select Type</option>
                            <option value="4">student</option>
                            <option value="3">Children</option>
                            <option value="5">Employee</option>
                        </select>
                        <div id="canteen_error"></div>
                        @error('user_type')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="canteen_id">User<span class="text-danger">*</span></label>
                        <select class="form-control select2" data-parsley-errors-container="#canteen_error" data-token="{{csrf_token()}}" required  name="select_user" id="select_user" data-placeholder="Select User">
                            <option selected disabled></option>
                            <option value="">Select User</option>
                        </select>
                        <div id="canteen_error"></div>
                        @error('canteen_id')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="canteen_id">Meal<span class="text-danger">*</span></label>
                        <select class="form-control select2" data-parsley-errors-container="#canteen_error" data-token="{{csrf_token()}}" required name="meal_type" id="meal_type" data-placeholder="Select Meals">
                            <input type="hidden" name="token" value="{{csrf_token()}}" id="token">
                            <option selected disabled></option>
                            <option></option>
                        </select>
                        <div id="canteen_error"></div>
                        @error('canteen_id')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                   <div class="form-group">
                        <label for="canteen_id">Payment Ref ID<span class="text-danger">*</span></label>
                        <input type="text" name="transaction_id" class="form-control" placeholder="Enter Bookey Transaction Ref ID" id="transaction_id">
                        <div id="canteen_error"></div>
                        @error('canteen_id')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="canteen_id">date<span class="text-danger">*</span></label>
                         <input type="text" name="calendar_constants" parsley-trigger="change" value="" required placeholder="Enter Date date" class="form-control input-daterange-datepicker" id="disable-datepicker">
                         <input type="hidden" name="dates" id="dates">
                        <div id="canteen_error"></div>
                        @error('canteen_id')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

             

                <div class="row">
                    
                    <div class="col-md-10 offset-md-2">
                        <div class="mt-3 float-right">                            
                            <p class="m-b-10"><strong>From Date : </strong> <span class="float-right" id="from_date">- </span></p>
                            <p class="m-b-10"><strong>To Date : </strong> <span class="float-right" id="to_date">-</span></p>                            
                            <p class="m-b-10"><strong>Price : </strong> <span class="float-right" id="price"> -  </span></p>
                            <p class="m-b-10"><strong>Days : </strong> <span class="float-right" id="days"> -  </span></p>
                            <p class="m-b-10"><strong>Tax  : </strong> <span class="float-right" id="tax">-  </span></p>
                            <p class="m-b-10"><strong>Total  : </strong> <span class="float-right" id="total">-  </span></p>
                            <p class="m-b-10"><strong>Transaction Id  : </strong> <span class="float-right">- </span></p>
                        </div>
                    </div><!-- end col -->
                </div>
               
                <!-- end row -->
            
                
                <!-- end row -->

               
                <div class="mt-4 mb-1">
                    <div class="text-right d-print-none">
                        <a href="javascript:void(0)" class="btn btn-info waves-effect waves-light" id="book_meal" >Submit</a>                        
                    </div>
                </div>
                
            </div> <!-- end card-box -->
        </div> <!-- end col -->
    </div>
    
</div>


@endsection
@section('script')
<script type="text/javascript">
$(function() {
    var dates=$('#dates').val();    
    range_datepicker = $("#disable-datepicker").flatpickr({
        mode:"range",
        minDate: 'today',
        disable: [
            function(date) {
                // return true to disable
                return (date.getDay() === 0 || date.getDay() === 6);

            }
        ],
        "locale": {
            "firstDayOfWeek": 1 // start week on Monday
        }
    });
});
$("#disable-datepicker").change(function(){
  var date = $('#disable-datepicker').val();
  var result = date.split(' to ');
  $('#from_date').text(result[0]);
  var from_date = result[0];
  if (result[1] != '') {
    $('#to_date').text(result[1]);
    var to_date = result[1];
  } else
  {
    $('#to_date').text(result[0]);
    var to_date = result[0];
  }  

  var start = new Date(from_date),
    end   = new Date(to_date),
    diff  = new Date(end - start),
    days  = diff/1000/60/60/24;
    $('#days').text(days);    
    var tax = $('#tax').text();
    console.log("tax: "+tax);    
    var price = $('#price').text();
    console.log("price: "+price);
    var sub_total = price*days;
    var total = (+sub_total) + (+tax);
    console.log("total: "+total);
    $('#total').text(total.toFixed(2));
});


$("#user_type").change(function(){
    var token = $('#user_type').data('token');
    var user_type = $('#user_type').val();   
    Notiflix.Loading.Standard();
    $.ajax({
        url: '/admin/order/get_user',
        type: "POST",
        dataType: "JSON",
        data:{
          "user_type":user_type,
          "_token": token
        },
        success: function (response) {
            Notiflix.Loading.Remove();
            var select_user =JSON.parse(JSON.stringify(response));
            if(select_user.length !== 0)
            {
                var user_data ='';
                $(select_user).each(function(mainkey,mainval) {                         
                    $(mainval).each(function(key,val) {
                        user_data += '<option value="">Select User</option>';
                        user_data += '<option value="'+val.id+'">'+val.full_name+'</option>';
                    })                                               
                });
                $('#select_user').empty();
                $('#select_user').append(user_data);                    
            }
            else
            {
                $('#select_user').empty();
                var user_data ='<option value="">';
                $('#select_user').append(user_data);
            }                
        }
    });
});

$("#select_user").change(function(){
    var token = $('#select_user').data('token');
    var user_type = $('#user_type').val();    
    var select_user = $('#select_user').val();   
    Notiflix.Loading.Standard();
    $.ajax({
        url: '/admin/order/get_meal',
        type: "POST",
        dataType: "JSON",
        data:{
          "select_user":select_user,
          "user_type":user_type,              
          "_token": token
        },
        success: function (response) {
           
            Notiflix.Loading.Remove();
           
            var meal_type =JSON.parse(JSON.stringify(response.meals));
             var dates =JSON.parse(JSON.stringify(response.dates));
             console.log("dates: "+dates);
             range_datepicker = $("#disable-datepicker").flatpickr({
                mode:"range",
                minDate: 'today',
                disable: dates
                // disable: [
                //     function(date) {
                //         // return true to disable
                //         return (date.getDay() === 0 || date.getDay() === 6);

                //     }
                // ],
                // "locale": {
                //     "firstDayOfWeek": 1 // start week on Monday
                // }                
            });
             console.log(dates);
            $('#dates').val(dates);

            if(meal_type.length !== 0)
            {   
                var user_data ='';
                $(meal_type).each(function(mainkey,mainval) {
                     
                    $(mainval).each(function(key,val) {                                                        
                        user_data += '<option value="">Select Meal</option>';
                        user_data += '<option value="'+val.id+'">'+val.name+'</option>';
                    })                                               
                });
                $('#meal_type').empty();
                $('#meal_type').append(user_data);
            }
            else
            {
                $('#meal_type').empty();
                var user_data ='<option value="">';
                $('#meal_type').append(user_data);
            }
        }
    });   
});

$("#meal_type").change(function(){    
   
    var token = $('#meal_type').data('token');
    var meal_type = $('#meal_type').val();           
    Notiflix.Loading.Standard();
    $.ajax({
        url: '/admin/order/get_meal_details',
        type: "POST",
        dataType: "JSON",
        data:{
         
          "meal_type":meal_type,              
          "_token": token
        },
        success: function (response) {
          
            Notiflix.Loading.Remove();
            var meal_type =JSON.parse(JSON.stringify(response.meals));            
            var tax =JSON.parse(JSON.stringify(response.tax));            
           $('#price').text(meal_type);                           
           $('#tax').text(tax);
        }
    });   
});

$("#book_meal").click (function(){       
    var token = $('#token').val();
    console.log("token: "+token);
    var user_id = $('#select_user').val();   
    console.log("user_id: "+user_id);
    var meal_id = $('#meal_type').val();
    console.log("meal_id: "+meal_id);
    var date = $('#disable-datepicker').val();
    console.log("date: "+date);
    var date_result = date.split(' to ');
      $('#from_date').text(date_result[0]);
      var from_date = date_result[0];
      if (date_result[1] != '') {        
        var to_date = date_result[1];
      } else
      {        
        var to_date = date_result[0];
      }  
    var transaction_id = $('#transaction_id').val();
    console.log("transaction_id: "+transaction_id);
    Notiflix.Loading.Standard();
    $.ajax({
        url: '/admin/order/book_meal',
        type: "POST",
        dataType: "JSON",
        data:{         
          "user_id":user_id,
          "meal_id":meal_id,
          "from_date":from_date,
          "to_date":to_date,
          "transaction_id":transaction_id,
          "_token": token
        },
        success: function (response) {
          console.log(response);
            Notiflix.Loading.Remove();
            location.reload();

            // $(selector for your message).slideDown(function() {
            // setTimeout(function() {
            //         $(selector for your message).slideUp();
            //     }, 5000);
            // });
           //  var meal_type =JSON.parse(JSON.stringify(response.meals));
           //  var tax =JSON.parse(JSON.stringify(response.tax));
           // $('#price').text(meal_type);
           // $('#tax').text(tax);
        }
    });   
});

</script>
@endsection
