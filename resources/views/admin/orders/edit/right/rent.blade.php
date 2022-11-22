<div class="row">
    @php($freePlacesCount = $tour->bus ? $tour->bus->places : 0)
    <div class="col-md-6">
        <b>Рейс {!! trans('pretty.statuses.'. $tour->status ) !!}</b> {!! $tour->prettyTime !!} <br>
        @if($tour->bus)
            <b>Автобус</b> {{ $tour->bus->name }} {{ $tour->bus->number }} <br>
        @else <b>Автобус не назначен</b><br>
        @endif
        @if($tour->driver)
            <b>Водитель</b> {{ $tour->driver->full_name}} <br>
        @else <b>Водитель не назначен</b><br>
        @endif
        {{-- <b class="text-success">Свободных мест</b> {{ $freePlacesCount }} <br>--}}
    </div>
    <div class="col-md-6">
        <br><span class="text-info">Стоимость:</span> @price($order->price) <br>
    </div>
</div>
@if ($order && $order->tour && $tour->id == $order->tour->id)
    @php($countPlaces = $freePlacesCount + $order->count_places)
@else
    @php($countPlaces = $freePlacesCount)
@endif
{!! Form::panelRange('count_places', 1, $countPlaces, isset($order) ? $order->count_places : '', null, ['class' => 'form-control js_orders-count_places'], false) !!}
<div class="js_admin_price_places">
    @include('admin.orders.edit.right.prices')
</div>