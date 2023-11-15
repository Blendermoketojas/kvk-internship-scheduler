@extends('layout')
@section('Calendar')


<div class="calendar">
<div class="calendarContent">
    <p>Date: {{ $date->format('Y-m-d H:i:s') }}</p>

</div>

    
</div>


@endsection