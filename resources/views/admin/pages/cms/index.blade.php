@extends('admin.layouts.master')
@section('content')
<style type="text/css">
    #cke_1_contents{height: 600px !important;}
</style>
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
                            <div class="col-12">
                                <div class="form-group">
                                    <!-- <label for="description">Description<span class="text-danger">*</span></label> -->
                                    <textarea class="ckeditor form-control" name="description" id="description" placeholder="Content" >{{$data}}</textarea>
                                    <input type="hidden" name="type" value="{{$type_val}}" style="height: 5000px;">
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
</script>

@endsection
