$(document).on("click",".send_push",function() {
        var user_id = $(this).attr('data-user-id');
        var order_id = $(this).attr('data-order-id');

        $('#order_id').val(order_id);
        $('#user_id').val(user_id);

    });

$("#send").click(function(){

    $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
        });

    var title = $("input[name=title]").val();
    var message = $("#notification").val();
    var order_id = $("#order_id").val();
    var user_id = $("#user_id").val();
    
    var url = '/admin/order_notification_customer';

    console.log('title'+title);
    console.log('message'+message);
    console.log('user_id'+user_id);
    console.log('order_id'+order_id);

    // return false;   
    if(title.trim()=='')
    {
        Notiflix.Notify.Failure('Please Enter title');
        return false;
    }else if(message.trim()=='')
    {
        Notiflix.Notify.Failure('Please Enter Message');
        return false;
    }

    $('#loader').show();

    $.ajax({
       url:url,
       method:'POST',
       data:{
              title:title, 
              message:message,
              order_id:order_id,
              user_id:user_id,
            },
       success:function(returnData){
        console.log(returnData);

         if (typeof returnData != "undefined"){
            $('#loader').hide();
            returnData = $.parseJSON(returnData);
            console.log(returnData);
            $("input[name=title]").val('');
            $("#notification").val('');           
            if(returnData.status == true){
                Notiflix.Notify.Success(returnData.message);
                $('#exampleModal').modal('hide');
            }else{
                Notiflix.Notify.Failure(returnData.message);
            }
         }else{
            Notiflix.Notify.Failure("Something Went Wrong");
         }
       },
       error:function(error){
          Notiflix.Notify.Failure("Something Went Wrong");
       }
    });
});
 


$(document).on("click",".send_push",function() {
        var user_id = $(this).attr('data-user-id');
        var order_id = $(this).attr('data-order-id');

        $('#order_id').val(order_id);
        $('#user_id').val(user_id);

    });

$("#send_store").click(function(){

        $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
            });

        var title = $("input[name=title]").val();
        var message = $("#notification").val();
        var order_id = $("#order_id").val();
        var user_id = $("#user_id").val();
        
        var url = '/store/order_notification_customer';

        console.log('title'+title);
        console.log('message'+message);
        console.log('user_id'+user_id);
        console.log('order_id'+order_id);

        // return false;   
        if(title.trim()=='')
        {
            Notiflix.Notify.Failure('Please Enter title');
            return false;
        }else if(message.trim()=='')
        {
            Notiflix.Notify.Failure('Please Enter Message');
            return false;
        }

        $('#loader').show();

        $.ajax({
           url:url,
           method:'POST',
           data:{
                  title:title, 
                  message:message,
                  order_id:order_id,
                  user_id:user_id,
                },
           success:function(returnData){
            console.log(returnData);


             if (typeof returnData != "undefined"){
                $('#loader').hide();
                returnData = $.parseJSON(returnData);
                console.log(returnData);
               $("input[name=title]").val('');
                $("#notification").val('');
                if(returnData.status == true){
                    Notiflix.Notify.Success(returnData.message);
                    $('#exampleModal').modal('hide');
                }else{
                    Notiflix.Notify.Failure(returnData.message);
                }
             }else{
                Notiflix.Notify.Failure("Something Went Wrong");
             }
           },
           error:function(error){
              Notiflix.Notify.Failure("Something Went Wrong");
           }
        });
    });



$(document).on("click",".send_push",function() {
        var user_id = $(this).attr('data-user-id');
        var order_id = $(this).attr('data-order-id');

        $('#order_id').val(order_id);
        $('#user_id').val(user_id);

    });

$("#send_notifi").click(function(){

    $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
        });

    var title = $("input[name=title]").val();
    var message = $("#notification").val();
    var order_id = $("#order_id").val();
    var user_id = $("#user_id").val();
    
    var url = '/board/order_notification_customer';

    console.log('title'+title);
    console.log('message'+message);
    console.log('user_id'+user_id);
    console.log('order_id'+order_id);

    // return false;   
    if(title.trim()=='')
    {
        Notiflix.Notify.Failure('Please Enter title');
        return false;
    }else if(message.trim()=='')
    {
        Notiflix.Notify.Failure('Please Enter Message');
        return false;
    }

    $('#loader').show();

    $.ajax({
       url:url,
       method:'POST',
       data:{
              title:title, 
              message:message,
              order_id:order_id,
              user_id:user_id,
            },
       success:function(returnData){
        console.log(returnData);

         if (typeof returnData != "undefined"){
            $('#loader').hide();
            returnData = $.parseJSON(returnData);
            console.log(returnData);
            $("input[name=title]").val('');
            $("#notification").val('');
            if(returnData.status == true){
                Notiflix.Notify.Success(returnData.message);
                $('#exampleModal').modal('hide');
            }else{
                Notiflix.Notify.Failure(returnData.message);
            }
         }else{
            Notiflix.Notify.Failure("Something Went Wrong");
         }
       },
       error:function(error){
          Notiflix.Notify.Failure("Something Went Wrong");
       }
    });
});

function accept_reject(e)
{

     $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });

    var tr = $(e).parent().closest('tr');
    var id = $(e).data('id');
    var status = $(e).data('status');

    if(status == 'accept'){
        var url = '/store/order/accept';
        var confirmation_txt = 'Are you sure that you want to accept this order?';
    }else if(status == 'reject'){
        var url = '/store/order/reject';
        var confirmation_txt = 'Are you sure that you want to reject this order?';
    }else if(status == 'ready-to-pickup'){
        var url = '/store/order/ready-to-pickup';
        var confirmation_txt = 'Are you sure that you want to update this order?';
    }else if(status == 'delivered'){
        var url = '/store/order/delivered';
        var confirmation_txt = 'Are you sure that you want to update this order?';
    }else{

    }

  // console.log(url);

  Notiflix.Confirm.Show(
    'Confirm',
    confirmation_txt,
    'Yes',
    'No',
    function(){
      $('#loader').show();
      $.ajax({
        url: url,
        type: 'post',
        data: {
            "id": id
        },
        success: function (returnData) {
            $('#loader').hide();
            returnData = $.parseJSON(returnData);
            console.log(returnData);
            if(returnData.status == true){

                if(status == 'accept'){
                    var str = '<a href="javascript:void(0);" data-id="'+id+'" data-status="ready-to-pickup" data-popup="tooltip" onclick="accept_reject(this);return false;"><label for="" class="badge badge-success p-1 mr-1 cursor-pointer order-status">Ready To Pickup</label></a>';
                    tr.find('.status-btns').html(str);
                }else if(status == 'reject'){
                    tr.find('.status-btns').html('');
                }else if(status == 'ready-to-pickup'){
                    var str = '<a href="javascript:void(0);" data-id="'+id+'" data-status="delivered" data-popup="tooltip" onclick="accept_reject(this);return false;"><label for="" class="badge badge-success p-1 mr-1 cursor-pointer order-status">Delivered</label></a>';
                    tr.find('.status-btns').html(str);
                }else if(status == 'delivered'){
                    tr.find('.status-btns').html('');
                }else{

                }
                Notiflix.Notify.Success(returnData.message);
            } else{
                Notiflix.Notify.Failure(returnData.message);
            }
        }
      }); 
  });

}



