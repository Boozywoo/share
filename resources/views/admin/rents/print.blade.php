<div>
    <b>Маршрут: </b> {{ $tour->route->name }}<br>
    <b>Рейс: {!! trans('pretty.statuses.'. $tour->status ) !!}</b> {!! $tour->prettyTime !!}<br>
    <b>Автобус: </b> {{ $tour->bus->number }}<br>
    @if($tour->bus->driver)
        <b>Водитель: </b> {{ $tour->driver->full_name}}<br>
    @endif
    @if($tour->comment)
        <b>Комментариий: </b> {{ $tour->comment }}<br>
    @endif
    <b>Общее кол-во пассажиров: </b> {{ count($places) }}<br>
</div>
@if($orders->count())
    <div class="table-responsive">
        <table class="table table-condensed">
            <thead>
            <tr>
                @foreach(array_keys($places[0]) as $key)
                    <th> {{$key}} </th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($places as $place)
                <tr>
                    @foreach(array_keys($places[0]) as $key)
                        <td style="text-align: center;"> {{$place[$key]}} </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif