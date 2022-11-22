<p>ФИО: {{ $data["name"] }}</p>
<p>Телефон: {{$data["phone"] }} </p>
<br>
<p>Рейс: {{ $data["tour"] }} </p>
<p>Остановка посадки: {{ $data["from"] }} </p>
<p>Остановка высадки: {{ $data["to"] }} </p>
<br>
<p>Дата: {{ $data["date"] }} </p>
<p>Время: {{ $data["time"] }} </p>
<br>
<p>Количество мест: {{ $data["count"] }} </p>
<p>Стоимость проезда: {{ $data["price"] . " " . $data["currency"]}} </p>
