@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel' || trim($slot) === 'Academia Library')
<img src="{{ asset('storage/background/logo.png') }}" class="logo" alt="Academia Library Logo">
<div style="margin-top: 10px; color: #2563eb; font-size: 18px; font-weight: 600;">Academia Library</div>
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
