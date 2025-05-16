@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'CBN')
<img src="{{ asset('images/logo.png') }}" class="logo" alt="CBN Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
 