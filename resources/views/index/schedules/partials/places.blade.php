@if($tour->reservation_by_place)
    @include('index.schedules.partials.bus')
@else
    @include('index.schedules.partials.select')
@endif