<p>ФИО: {{(empty($order->client->first_name) ? 'Не введено имя' : $order->client->first_name) . " " . (empty($order->client->middle_name) ? ' ' : $order->client->middle_name)
    . " " . (empty($order->client->last_name) ? ' ' : $order->client->last_name) }}</p>
<p>Телефон: {{ empty($order->client->phone) ? 'Не введен телефон' : $order->client->phone }} </p>
<br>
<p>Рейс: {{ $order->tour->route->name }} </p>
<p>Остановка посадки: {{ $order->stationFrom->name . ", " . $order->stationFrom->city->name }} </p>
<p>Остановка высадки: {{ $order->stationTo->name . ", " . $order->stationTo->city->name }} </p>
<br>
<p>Дата: {{ $order->tour->date_start->format('d.m.Y') }} </p>
<p>Время: {{ $order->tour->time_start }} </p>
<br>
<p>Количество мест: {{ $order->count_places }} </p>
<p>Стоимость проезда: {{ $order->price . " " . ($order->tour->route->currency ? $order->tour->route->currency->alfa : 'BYN') }} </p>
<br>
<p>Номер брони: {{ $order->slug }} </p>