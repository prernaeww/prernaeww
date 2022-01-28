function delete_notiflix(e)
{
  var tr = $(e).parent().closest('tr');
  var id = $(e).data('id');
  var token = $(e).data('token');
  var url = $(e).data('url');
  // console.log(url);

  Notiflix.Confirm.Show(
    'Confirm',
    'Are you sure that you want to delete this record?',
    'Yes',
    'No',
    function(){
      $('#loader').show();
      $.ajax({
        url: url+'/'+id,
        type: 'post',
        dataType: "JSON",
        data: {
            "id": id,
            "_method": 'DELETE',
            "_token": token,
        },
        success: function (returnData) {
          $('#loader').hide();
            console.log(returnData.status);
          if (url == 'category' || url == 'measurement' || url == 'family') {
            if (returnData.status == false) {
              Notiflix.Notify.Failure(returnData.message);
            } else
            {
              Notiflix.Notify.Success('Deleted');   
              tr.remove();
            }
          } else
          {
            Notiflix.Notify.Success('Deleted');
            tr.remove();
          }
        }
      }); 
  });

}

function pending_order(e)
{
  var tr = $(e).parent().closest('tr');
  var id = $(e).data('id');
  var status = $(e).data('status');
  var token = $(e).data('token');
  // console.log(status);
  // return false;

  Notiflix.Confirm.Show(
    'Confirm',
    'Are you sure that you delivered this order?',
    'Yes',
    'No',
    function(){
      $('#loader').show();
      $.ajax({
        url: '/board/pending_order',
        type: 'post',
        dataType: "JSON",
        data: {
            "id": id,
            "status": status,
            "_method": 'POST',
            "_token": token,
        },
        success: function (returnData) {
            console.log(returnData);
            $('#loader').hide();
            Notiflix.Notify.Success('Delivered');
            var replace_str = '<span class="badge badge-success">Delivered</span>';
            $(e).replaceWith(replace_str);
        }
      }); 
  });

} 
function active_deactive(e)
{
    var table = $(e).data('table');
    var id = $(e).data('id');
    var status = $(e).data('status');
	var token = $(e).data('token');
  // var table_user = $(e).data('table_user');
    Notiflix.Confirm.Show(
      'Confirm',
      'Are you sure that you want to change status of this record?',
      'Yes',
      'No',
      function(){
        $('#loader').show();
        $.ajax({
              url: '/admin/active_deactive',
              type: "POST",
			        dataType: "JSON",
              data:{
                "table":table,
                "id":id,
				        "_token": token,
                "status":status
                // "table_user":table_user
              },
              success: function (returnData) {
                  returnData = $.parseJSON(returnData);
                  console.log(returnData);
                  $('#loader').hide();
                  if (typeof returnData != "undefined")
                  {
                      if(returnData.is_success == false){
                          Notiflix.Notify.Failure('Something went wrong');
                      }else{
                        
                        Notiflix.Notify.Success('Updated');
                          // tr.remove();
                          if(status == 1){
                            var replace_str = '<button onclick="active_deactive(this);" data-table="'+table+'" data-id="'+id+'" data-token= "'+token+'"  class="btn btn-danger btn-xs waves-effect waves-light"  data-status="0">Inactive</button>';
                          }else{
                            var replace_str = '<button onclick="active_deactive(this);" data-table="'+table+'" data-id="'+id+'" data-token= "'+token+'"  class="btn btn-success btn-xs waves-effect waves-light" data-status="1">Active</button>';
                          }
                          $(e).replaceWith(replace_str);
                      } 
                  } 
              }
          });  
    });
}

function active_deactive_store_board(e)
{
    var table = $(e).data('table');
    var id = $(e).data('id');
    var status = $(e).data('status');
  var token = $(e).data('token');
  var table_user = $(e).data('table_user');
    Notiflix.Confirm.Show(
      'Confirm',
      'Are you sure that you want to change status of this record?',
      'Yes',
      'No',
      function(){
        $('#loader').show();
        $.ajax({
              url: '/admin/active_deactive_store_board',
              type: "POST",
              dataType: "JSON",
              data:{
                "table":table,
                "id":id,
                "_token": token,
                "status":status,
                "table_user":table_user
              },
              success: function (returnData) {
                  // returnData = $.parseJSON(returnData);
                  console.log(returnData);
                  $('#loader').hide();
                  // if (typeof returnData != "undefined")
                  // {
                      // if(returnData.is_success == false){
                      //     Notiflix.Notify.Failure('Something went wrong');
                      // }else{
                        if (returnData.status == false) {
                          Notiflix.Notify.Failure(returnData.message);
                        } else
                        {
                        
                          Notiflix.Notify.Success('Updated');
                          // tr.remove();
                          if(status == 1){
                            var replace_str = '<button onclick="active_deactive_store_board(this);"  data-table_user="'+table_user+'" data-table="'+table+'" data-id="'+id+'" data-token= "'+token+'"  class="btn btn-danger btn-xs waves-effect waves-light"  data-status="0">Inactive</button>';
                          }else{
                            var replace_str = '<button onclick="active_deactive_store_board(this);"  data-table_user="'+table_user+'" data-table="'+table+'" data-id="'+id+'" data-token= "'+token+'"  class="btn btn-success btn-xs waves-effect waves-light" data-status="1">Active</button>';
                          }
                          $(e).replaceWith(replace_str);
                        } 
                      // }                      
                  // } 
              }
          });  
    });
}


function active_deactive_category(e)
{
    var table = $(e).data('table');
    var id = $(e).data('id');
    var status = $(e).data('status');
	  var token = $(e).data('token');
    Notiflix.Confirm.Show(
      'Confirm',
      'Are you sure that you want to change status of this record?',
      'Yes',
      'No',
      function(){
        $('#loader').show();
        $.ajax({
              url: '/board/active_deactive_category',
              type: "POST",
			        dataType: "JSON",
              data:{
                "table":table,
                "id":id,
				        "_token": token,
                "status":status
              },
              success: function (returnData) {
                  returnData = $.parseJSON(returnData);
                  console.log(returnData);
                  $('#loader').hide();
                  if (typeof returnData != "undefined")
                  {
                      if(returnData.is_success == false){
                          Notiflix.Notify.Failure('Something went wrong');
                      }else{
                        
                        Notiflix.Notify.Success('Updated');
                          // tr.remove();
                          if(status == 1){
                            var replace_str = '<button onclick="active_deactive_category(this);" data-table="'+table+'" data-id="'+id+'" data-token= "'+token+'"  class="btn btn-danger btn-xs waves-effect waves-light"  data-status="0">Inactive</button>';
                          }else{
                            var replace_str = '<button onclick="active_deactive_category(this);" data-table="'+table+'" data-id="'+id+'" data-token= "'+token+'"  class="btn btn-success btn-xs waves-effect waves-light" data-status="1">Active</button>';
                          }
                          $(e).replaceWith(replace_str);
                      } 
                  } 
              }
          });  
    });
}

function active_deactive_family(e)
{
    var table = $(e).data('table');
    var id = $(e).data('id');
    var status = $(e).data('status');
    var token = $(e).data('token');
    Notiflix.Confirm.Show(
      'Confirm',
      'Are you sure that you want to change status of this record?',
      'Yes',
      'No',
      function(){
        $('#loader').show();
        $.ajax({
              url: '/admin/active_deactive_family',
              type: "POST",
              dataType: "JSON",
              data:{
                "table":table,
                "id":id,
                "_token": token,
                "status":status
              },
              success: function (returnData) {
                  returnData = $.parseJSON(returnData);
                  console.log(returnData);
                  $('#loader').hide();
                  if (typeof returnData != "undefined")
                  {
                      if(returnData.is_success == false){
                          Notiflix.Notify.Failure('Something went wrong');
                      }else{
                        
                        Notiflix.Notify.Success('Updated');
                          // tr.remove();
                          if(status == 1){
                            var replace_str = '<button onclick="active_deactive_category(this);" data-table="'+table+'" data-id="'+id+'" data-token= "'+token+'"  class="btn btn-danger btn-xs waves-effect waves-light"  data-status="0">Inactive</button>';
                          }else{
                            var replace_str = '<button onclick="active_deactive_category(this);" data-table="'+table+'" data-id="'+id+'" data-token= "'+token+'"  class="btn btn-success btn-xs waves-effect waves-light" data-status="1">Active</button>';
                          }
                          $(e).replaceWith(replace_str);
                      } 
                  } 
              }
          });  
    });
}

function active_deactive_category(e)
{
    var table = $(e).data('table');
    var id = $(e).data('id');
    var status = $(e).data('status');
    var token = $(e).data('token');
    Notiflix.Confirm.Show(
      'Confirm',
      'Are you sure that you want to change status of this record?',
      'Yes',
      'No',
      function(){
        $('#loader').show();
        $.ajax({
              url: '/admin/active_deactive_category',
              type: "POST",
              dataType: "JSON",
              data:{
                "table":table,
                "id":id,
                "_token": token,
                "status":status
              },
              success: function (returnData) {
                  returnData = $.parseJSON(returnData);
                  console.log(returnData);
                  $('#loader').hide();
                  if (typeof returnData != "undefined")
                  {
                      if(returnData.is_success == false){
                          Notiflix.Notify.Failure('Something went wrong');
                      }else{
                        
                        Notiflix.Notify.Success('Updated');
                          // tr.remove();
                          if(status == 1){
                            var replace_str = '<button onclick="active_deactive_category(this);" data-table="'+table+'" data-id="'+id+'" data-token= "'+token+'"  class="btn btn-danger btn-xs waves-effect waves-light"  data-status="0">Inactive</button>';
                          }else{
                            var replace_str = '<button onclick="active_deactive_category(this);" data-table="'+table+'" data-id="'+id+'" data-token= "'+token+'"  class="btn btn-success btn-xs waves-effect waves-light" data-status="1">Active</button>';
                          }
                          $(e).replaceWith(replace_str);
                      } 
                  } 
              }
          });  
    });
}

function active_deactive_product(e)
{
    var table = $(e).data('table');
    var id = $(e).data('id');
    var status = $(e).data('status');
    var token = $(e).data('token');
    Notiflix.Confirm.Show(
      'Confirm',
      'Are you sure that you want to change status of this record?',
      'Yes',
      'No',
      function(){
        $('#loader').show();
        $.ajax({
              url: '/board/active_deactive_product',
              type: "POST",
              dataType: "JSON",
              data:{
                "table":table,
                "id":id,
                "_token": token,
                "status":status
              },
              success: function (returnData) {
                  returnData = $.parseJSON(returnData);
                  console.log(returnData);
                  $('#loader').hide();
                  if (typeof returnData != "undefined")
                  {
                      if(returnData.is_success == false){
                          Notiflix.Notify.Failure('Something went wrong');
                      }else{
                        Notiflix.Notify.Success('Updated');
                          // tr.remove();
                          if(status == 1){
                            var replace_str = '<button onclick="active_deactive_product(this);" data-table="'+table+'" data-id="'+id+'" data-token= "'+token+'"  class="btn btn-danger btn-xs waves-effect waves-light"  data-status="0">Inactive</button>';
                          }else{
                            var replace_str = '<button onclick="active_deactive_product(this);" data-table="'+table+'" data-id="'+id+'" data-token= "'+token+'"  class="btn btn-success btn-xs waves-effect waves-light" data-status="1">Active</button>';
                          }
                          $(e).replaceWith(replace_str);
                      } 
                  } 
              }
          });  
    });
}

function active_deactive_product(e)
{
    var table = $(e).data('table');
    var id = $(e).data('id');
    var status = $(e).data('status');
    var token = $(e).data('token');
    Notiflix.Confirm.Show(
      'Confirm',
      'Are you sure that you want to change status of this record?',
      'Yes',
      'No',
      function(){
        $('#loader').show();
        $.ajax({
              url: '/admin/active_deactive_product',
              type: "POST",
              dataType: "JSON",
              data:{
                "table":table,
                "id":id,
                "_token": token,
                "status":status
              },
              success: function (returnData) {
                  returnData = $.parseJSON(returnData);
                  console.log(returnData);
                  $('#loader').hide();
                  if (typeof returnData != "undefined")
                  {
                      if(returnData.is_success == false){
                          Notiflix.Notify.Failure('Something went wrong');
                      }else{
                        Notiflix.Notify.Success('Updated');
                          // tr.remove();
                          if(status == 1){
                            var replace_str = '<button onclick="active_deactive_product(this);" data-table="'+table+'" data-id="'+id+'" data-token= "'+token+'"  class="btn btn-danger btn-xs waves-effect waves-light"  data-status="0">Inactive</button>';
                          }else{
                            var replace_str = '<button onclick="active_deactive_product(this);" data-table="'+table+'" data-id="'+id+'" data-token= "'+token+'"  class="btn btn-success btn-xs waves-effect waves-light" data-status="1">Active</button>';
                          }
                          $(e).replaceWith(replace_str);
                      } 
                  } 
              }
          });  
    });
}


function active_deactive_meal(e)
{
    var table = $(e).data('table');
    var id = $(e).data('id');
    var status = $(e).data('status');
    var token = $(e).data('token');
    Notiflix.Confirm.Show(
      'Confirm',
      'Are you sure that you want to change status of this record?',
      'Yes',
      'No',
      function(){
        $('#loader').show();
        $.ajax({
              url: '/board/active_deactive_meal',
              type: "POST",
              dataType: "JSON",
              data:{
                "table":table,
                "id":id,
                "_token": token,
                "status":status
              },
              success: function (returnData) {
                  returnData = $.parseJSON(returnData);
                  console.log(returnData);
                  $('#loader').hide();
                  if (typeof returnData != "undefined")
                  {
                      if(returnData.is_success == false){
                          Notiflix.Notify.Failure('Something went wrong');
                      }else{
                        
                        Notiflix.Notify.Success('Updated');
                          // tr.remove();
                          if(status == 1){
                            var replace_str = '<button onclick="active_deactive_meal(this);" data-table="'+table+'" data-id="'+id+'" data-token= "'+token+'"  class="btn btn-danger btn-xs waves-effect waves-light"  data-status="0">Inactive</button>';
                          }else{
                            var replace_str = '<button onclick="active_deactive_meal(this);" data-table="'+table+'" data-id="'+id+'" data-token= "'+token+'"  class="btn btn-success btn-xs waves-effect waves-light" data-status="1">Active</button>';
                          }
                          $(e).replaceWith(replace_str);
                      } 
                  } 
              }
          });  
    });
}
