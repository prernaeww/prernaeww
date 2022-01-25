@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{$pageTittle}}</li>
                    </ol>
                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
            </div>
            <div class="btn-group float-right mt-2 mb-2">
                    <a href="{{$backUrl}}"  class="btn btn-secondary waves-effect waves-light">
                    <span class="btn-label">
                        <i class="fa fa-arrow-left"></i>
                    </span>
                    Back
                </a>
            </div>
		</div>
	</div>
	<div class="row">
		<div class="col-xl-12">
			<div class="card">
				<div class="card-body" >
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tbody>
                            <tr>
                                <th class="text-nowrap" scope="row">Name</th>
                                <td colspan="5">{{$product->name}}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap" scope="row">Canteen Name</th>
                                <td colspan="5"><u><a href="{{ route('admin.canteen.show',$product->canteen_id) }}"> {{$product->canteen_name}}</a></u></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
			</div>	
		</div>
	</div>
</div>


@endsection