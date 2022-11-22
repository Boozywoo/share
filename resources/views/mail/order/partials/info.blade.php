<h3>Информация о вашем рейсе</h3>
<table>
    <tbody>
    <tr>
        <td>Направление</td>
        <td>{{ $order->tour->route->name }}</td>
    </tr>
    @if($order->station_from_id)
        <tr>
            <td>Отправление</td>
            <td>
                {{ $order->stationFrom->name }}
                @date($order->tour->date_start)
                @time($order->station_from_time, 0)
            </td>
        </tr>
    @endif
    @if($order->station_to_id)
        <tr>
            <td>Прибытие*</td>
            <td>
                {{ $order->stationTo->name }}
                @time($order->station_to_time, 0)
            </td>
        </tr>
    @endif
    {{--@if($order->station_from_time && $order->station_to_time)--}}
    {{--<tr>--}}
    {{--<td>В пути*</td>--}}
    {{--<td>{{ $password }}</td>--}}
    {{--</tr>--}}
    {{--@endif--}}
    <tr>
        <td>Автобус</td>
        <td>{{ $order->tour->bus->name }}</td>
    </tr>
    <tr>
        <td>Количество мест</td>
        <td>{{ $order->count_places }}</td>
    </tr>
    </tbody>
</table>
<p>
    *При идеальных дорожных условиях. Снег, дождь, пробки и освещенность существенно влияет на время в пути.
    Безопасность является безусловным приоритетом при выполнении маршрута.
</p>

<h3>Стоимость</h3>

<table>
    <thead>
    <tr>
        <th >Билет</th>
        <th>Стоимость</th>
    </tr>
    </thead>
    <tbody>
    @foreach($order->orderPlaces as $place)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>@price($place->price)</td>
        </tr>
    @endforeach
    </tbody>
</table>

@if($order->coupon)
    <b>Промокод: {{ $order->coupon->name }} </b>
@endif
<hr>
<b>Итого: @price($order->totalPrice)</b>

<p></p>

<h3>Ваша контактная информация</h3>
<p>
    Номер телефона: @phone($order->client->phone)
    Email: @phone($order->client->email)
</p>