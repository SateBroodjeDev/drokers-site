@props(['text'])
@foreach(preg_split('/\R/u', $text) as $line)@if($loop->last)<em>{{ $line }}</em>@else{{ $line }}<br>@endif @endforeach
