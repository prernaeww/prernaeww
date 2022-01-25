@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    {{ Breadcrumbs::render('viewschool')}}
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
                        <table class="table ">
                            <tbody>
                            <tr>
                                <th class="text-nowrap" scope="row">Name</th>
                                <td colspan="5">{{$school['name']}}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap" scope="row">Canteen Name</th>
                                <td colspan="5"><u><a href="{{ route('admin.canteen.show',$school['canteen_id']) }}"> {{$school['canteen_name']}}</a></u></td>
                            </tr>
                            @if (isset($school['holiday']) && !empty($school['holiday']))
                            <tr>
                                <th class="text-nowrap" scope="row">List of Holidays</th>
                                <td colspan="5">
                                @foreach ($school['holiday'] as $key => $holiday )
                                    @if($holiday['from_date'] == $holiday['to_date'])
                                        {{$holiday['from_date']}}<br>
                                    @else
                                        {{$holiday['from_date'] .' to '. $holiday['to_date']}}<br>
                                    @endif
                                @endforeach
                                </td>
                            </tr>
                            @endif
                            @if (isset($school['grades']) && !empty($school['grades']))
                            <tr>
                                <th class="text-nowrap" scope="row">Grades</th>
                                <td colspan="5">
                                @foreach ($school['grades'] as $key => $grades )
                                    {{$grades['grade']}}<br>
                                @endforeach
                                </td>
                            </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
			</div>	
		</div>
	</div>
</div>


@endsection