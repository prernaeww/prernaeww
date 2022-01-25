@component('mail::message')
# {{ $title }}

{{ $description }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent