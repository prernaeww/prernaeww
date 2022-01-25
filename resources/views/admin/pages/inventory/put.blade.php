@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    {{ Breadcrumbs::render('addinventory')}}
                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body" >
                <form action="{{ route('admin.inventory.update',$storeproduct->id) }}" method="POST"  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="board_id">Select Board<span class="text-danger">*</span></label>
                                <select class="form-control select2" data-parsley-errors-container="#parent_error" name="board_id" id="board_id" data-placeholder="Select Board" disabled>
                                    <option selected disabled></option>
                                    @if(isset($board) && count($board) > 0)
                                    @foreach($board as $row)
                                        @php
                                            $selected = "";
                                        @endphp
                                        @if(old('user_id') == $row->id)
                                            @php
                                                $selected = "selected";
                                            @endphp
                                        @endif
                                    <option value="{{$row->id}}" 
                                        @if($board_id == $row->id) selected @endif  
                                        >{{$row->first_name}}</option>
                                    @endforeach
                                @endif
                                </select>
                                <div id="board_id"></div>
                                @error('board_id')
                                    <div class="error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="user_id">Select Store<span class="text-danger">*</span></label>
                                <select class="form-control select2" data-parsley-errors-container="#user_id" name="user_id" id="user_id_data" data-placeholder="Select Store" disabled>
                                  @foreach($store as $row)
                                  <option value="{{$row->id}}" @if($storeproduct->user_id == $row['id']) selected @endif >{{$row->first_name}}</option>
                                  @endforeach
                                </select>
                                <div id="user_id"></div>
                                @error('user_id')
                                    <div class="error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="first_name">Item Code<span class="text-danger">*</span></label>
                                <select class="form-control select2" data-parsley-errors-container="#item_code" name="product_id" id="item_code" data-placeholder="Select Item Code" disabled>
                                    <option selected disabled></option>
                                    @foreach($product as $product_data)          
                                    <option value="{{$product_data->id}}" @if($storeproduct->product_id == $product_data['id']) selected @endif>{{$product_data->item_code}}</option>
                                    @endforeach
                                    
                               
                                </select>
                                <div id="parent_error"></div>
                                @error('user_id')
                                    <div class="error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                         <div class="col-12">
                            <div class="form-group">
                                <label for="first_name">Stock<span class="text-danger">*</span></label>
                                <input type="text" name="stock" parsley-trigger="change" value="{{$storeproduct->stock}}" data-parsley-errors-container="#stock_error" required placeholder="Enter Stock" class="form-control stock" id="stock">
                                <div id="stock_error"></div>
                                @error('stock')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                    </div>
                   

                    <div class="form-group text-right m-b-0">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">
                            Submit
                        </button>
                       <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary waves-effect m-l-5">Cancel</a>
                    </div>

                </form>

                </div>
            </div>  
        </div>
    </div>
</div>
@endsection
@section('script')
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<script type="text/javascript">
 
    $('#board_id').on('change', function () {
         
      var board_id = $(this).val();
      $.ajax({
            
            type: "post",
            url:  '/admin/data-active-store',
            dataType : 'json',
            data: {
                'parent_id':board_id,
                _token: "{{csrf_token()}}",
            },
            success: function(response) {
              var html = '<option value="">Select Store</option>';   
              $.each(response, function(i, item) {

                    html += '<option value="'+response[i].id+'">'+response[i].first_name+'</option>';
                });
               $('#user_id_data').html(html);
            },
            error: function(error) {
            }
        });
    
});

$(document).ready(function(){
    $(".stock").TouchSpin({
        min: 0,
        max: 1000000000
    });
  
});

</script>
@endsection
