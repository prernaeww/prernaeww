<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Canteeny')
<img src="{{asset('images/logo.png')}}" height="100" width="100" style="height: 150px;width: 150px;object-fit: scale-down;" class="logo" alt="Canteeny">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
