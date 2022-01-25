@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                {{ Breadcrumbs::render('viewcategory')}}
                </div>
                <h4 class="page-title">{{$pageTittle}}</h4>
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
                                <td colspan="5">{{$category->name}}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap" scope="row">Canteen Name</th>
                                <td colspan="5"><img src="{{$category->image}}" style="height:50px;width: 50px;"></td>
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