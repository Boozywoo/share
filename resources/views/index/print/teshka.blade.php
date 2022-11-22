<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bilet</title>
</head>
<body>
<div data-block="body" style="font-family: DejaVu Sans, sans-serif;">
    <div data-block="details" style="text-align: right; font-size: 0.75em; margin-bottom: 4em;">
        {{ $order->bus()->company->name }}<br />
        {!! nl2br($order->bus()->company->requisites) !!}<br />
        Телефон: {{ $order->bus()->company->phone }}
    </div>
    <table data-block="info-table" style="font-size: 1em; width: 100%; border-collapse: collapse;">
        <tbody>
        <tr>
            <td width="50%" style="border-bottom: 1px solid #000; padding: .4em;">Дата покупки</td>
            <td width="50%" style="border-bottom: 1px solid #000; padding: .4em;">{{ $order->created_at->format('d.m.Y H:i') }}</td>
        </tr>
        <tr>
            <td width="50%" style="border-bottom: 1px solid #000; padding: .4em;">Номер заказа</td>
            <td width="50%" style="border-bottom: 1px solid #000; padding: .4em;">{{ $order->slug }}</td>
        </tr>
        <tr>
            <td width="50%" style="border-bottom: 1px solid #000; padding: .4em;">Номер электронного билета</td>
            <td width="50%" style="border-bottom: 1px solid #000; padding: .4em;">33550</td>
        </tr>
        <tr>
            <td width="50%" style="border-bottom: 1px solid #000; padding: .4em;">Транспорт</td>
            <td width="50%" style="border-bottom: 1px solid #000; padding: .4em;">{{ $order->tour->bus->name }}</td>
        </tr>
        <tr>
            <td width="50%" style="border-bottom: 1px solid #000; padding: .4em;">Номер рейса</td>
            <td width="50%" style="border-bottom: 1px solid #000; padding: .4em;">{{ $order->tour->id }}</td>
        </tr>
        <tr>
            <td width="50%" style="border-bottom: 1px solid #000; padding: .4em;">Тип билета</td>
            <td width="50%" style="border-bottom: 1px solid #000; padding: .4em;">Взрослый</td>
        </tr>
        </tbody>
    </table>
    <h2 style="font-weight: 400;">Информация о пассажире и тарифе</h2>
    <table data-block="info-passenger-table" style="font-size: 0.875em; width: 100%; border-collapse: collapse; text-align: center;">
        <thead>
        <tr>
            <td style="border: 1px solid #000; padding: .8em .4em;">Пассажир</td>
            <td style="border: 1px solid #000; padding: .8em .4em;">Паспорт РФ</td>
            <td style="border: 1px solid #000; padding: .8em .4em;">Тариф <br />(руб)</td>
            <td style="border: 1px solid #000; padding: .8em .4em;">Мест</td>
            <td style="border: 1px solid #000; padding: .8em .4em;">Сборы <br />(руб)</td>
            <td style="border: 1px solid #000; padding: .8em .4em;">ИТОГО <br />(руб)</td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="border: 1px solid #000; padding: .8em .4em;">{{ $order->client->fullname }}<br />{{ $order->client->birth_day ? $order->client->birth_day->format('d.m.Y') : '' }}</td>
            <td style="border: 1px solid #000; padding: .8em .4em;">{{ $order->client->passport }}</td>
            <td style="border: 1px solid #000; padding: .8em .4em;">{{ $order->price }}</td>
            <td style="border: 1px solid #000; padding: .8em .4em;">{{ $order->orderPlaces->count() }}</td>
            <td style="border: 1px solid #000; padding: .8em .4em;">0.0</td>
            <td style="border: 1px solid #000; padding: .8em .4em;">{{ $order->price }}</td>
        </tr>
        </tbody>
    </table>
    <h2 style="font-weight: 400;">Информация о рейсе</h2>
    <table data-block="info-route-table" style="font-size: 0.875em; width: 100%; border-collapse: collapse; text-align: center;">
        <thead>
        <tr>
            <td width="50%" style="border: 1px solid #000; padding: .8em">Отправление </td>
            <td width="50%" style="border: 1px solid #000; padding: .8em">Прибытие</td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td width="50%" style="border: 1px solid #000; padding: .8em"><strong>{{ $order->stationFrom->city->name }}</strong> <br />{{ $order->from_date_time->format('d.m.Y H:i') }}</td>
            <td width="50%" style="border: 1px solid #000; padding: .8em"><strong>{{ $order->stationTo->city->name }}</strong> <br />{{ $order->to_date_time->format('d.m.Y H:i') }}</td>
        </tr>
        </tbody>
    </table>
    <h2 style="font-weight: 400;">Информация о платеже</h2>
    <table data-block="info-payment-table" style="font-size: 0.875em; width: 100%; border-collapse: collapse;">
        <tr>
            <td width="70%" style="border: 1px solid #000; padding: .8em;">Оплачено</td>
            <td width="30%" style="border: 1px solid #000; padding: .8em;">{{ $order->price }}</td>
        </tr>
        <tr>
            <td width="70%" style="border: 1px solid #000; padding: .8em;">Итого сумма платежа (руб)</td>
            <td width="30%" style="border: 1px solid #000; padding: .8em;">{{ $order->price }}</td>
        </tr>
    </table>
    <p style="font-size: 0.75em; margin-top: 2em;">Дополнительную информацию о заказе уточняйте по телефонам</p>
</div>
</body>
</html>
