<table id="{{$dataTableId}}" class="table table-hover mb-0">
	<thead>
		<tr>
			@foreach ($dateTableFields as $field)
			<th>
				{{ $field['title'] }}
			</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>