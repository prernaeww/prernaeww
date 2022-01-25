@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                {{ Breadcrumbs::render('reportedissue')}}
                </div>
                <h4 class="page-title">{{$dateTableTitle}}</h4>
            </div>
		</div>
	</div>
    @include('admin.include.flash-message')
	<div class="row">
		<div class="col-xl-12">
			<div class="card">
				<div class="card-body" >
					@include('admin.include.table')
				</div>
			</div>	
		</div>
	</div>

	<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="myCenterModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myCenterModalLabel">Report Issue</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover"> 
                      <tbody>
                          <tr>
                              <td>Issue</td>
                              <td id="modal-issue"></td>
                          </tr>
                          <tr>
                              <td>Description</td>
                              <td id="modal-description">--</td>
                          </tr>
                      </tbody>
                  </table>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>


@endsection
@section('script')
@include('admin.include.table_script')
<script type="text/javascript">
$(document).on('click',"#view", function(){
    $('#modal-issue').html($(this).data("issue"));
    if ($(this).data("description") != ''){
        $('#modal-description').html(($(this).data("description")));
    }
});
</script>
@endsection		