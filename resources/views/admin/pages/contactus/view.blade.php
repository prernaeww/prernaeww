@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        
                        <li class="breadcrumb-item active">{{ Breadcrumbs::render('viewcontactus')}}</li>
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
                                <th class="text-nowrap" scope="row">Email</th>
                                <td colspan="5">{{$contactus->email}}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap" scope="row">Message</th>
                                <td colspan="5">{{$contactus->message}}</td>
                            </tr>
                           @if($contactus->user_id!=NULL)
                            <tr>
                                <th class="text-nowrap" scope="row">First Name</th>
                                <td colspan="5">{{$contactus->user_name->first_name}}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap" scope="row">Last Name</th>
                                <td colspan="5">{{$contactus->user_name->last_name}}</td>
                            </tr>
                            @else
                             <tr>
                                <th class="text-nowrap" scope="row">First Name</th>
                                <td colspan="5">-</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap" scope="row">Last Name</th>
                                <td colspan="5">-</td>
                            </tr>
                            @endif
                             <tr>
                                <th class="text-nowrap" scope="row">Document</th>
                                @if($contactus->document != Null)

                                <td colspan="5"><a href="{{url($contactus->document)}}" target="_blank">Click Here To Downlaod</a></td>
                                @else
                                <td colspan="5">-</td>
                                @endif
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