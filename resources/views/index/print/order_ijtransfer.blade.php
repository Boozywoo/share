<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<table cellpadding="3" cellspacing="3" >
    <tr>
        <td width="50%" height="300px;">
            <table cellspacing="2" cellpadding="2" width="100%">
                <tr>
                    <td colspan="2"><h3>{{ __('print_ticket.old.route_list', [], $lang) }}:</h3></td>
                </tr>
                <tr>
                    <td class="table_title" width="40%">{{ __('print_ticket.old.agent', [], $lang) }}:</td>
                    <td class="info"></td>
                </tr>
                <tr>
                    <td colspan="2" class="table_separator">&nbsp;</td>
                </tr>
                <tr>
                    <td class="table_title">{{ __('print_ticket.old.route', [], $lang) }}:</td>
                    <td class="info">{{ $order->tour->route->name }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="table_separator">&nbsp;</td>
                </tr>
                <tr>
                    <td class="table_title">{{ __('print_ticket.old.charterer', [], $lang) }}:</td>
                    <td class="info">ООО «Ижтрансфер»</td>
                </tr>
                <tr>
                    <td colspan="2" class="table_separator">&nbsp;</td>
                </tr>
                <tr>
                    <td class="table_title">{{ __('print_ticket.old.ticket', [], $lang) }}:</td>
                    <td class="info" >{{ __('print_ticket.old.#', [], $lang) }}{{ $order->slug }}
                        , <br>{{ __('print_ticket.old.tariff', [], $lang) }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="table_separator">&nbsp;</td>
                </tr>
                <tr>
                    <td class="table_title">{{ __('print_ticket.old.passengers', [], $lang) }}:</td>
                    <td class="info" >
                        @foreach ($order->orderPlaces as $place)
                            @if ($place->surname == '' && $place->name == '')
                                 {{ $order->client->full_name }}<br>
                            @else
                                <i>{{ $place->surname." ".$place->name." ".$place->patronymic." ".$place->passport }}</i><br>
                            @endif
                        @endforeach
                    </td>
                </tr>
                @if($order->addServices->count())
                    <tr>
                        <td colspan="2" class="table_separator">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="table_title">Стоимость без доп. сервисов:</td>
                        <td class="info">
                            {{ $old_price }} {{ trans('admin_labels.currencies_short.'.$order->tour->route->currency->alfa) }}<br>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="table_separator">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="table_title">{{ __('print_ticket.old.add_services', [], $lang) }}:</td>
                        <td class="info">
                            @foreach ($order->addServices as $item)
                                {{ $item->name.' '.$item->value*$item->pivot->quantity.' '.trans('admin_labels.currencies_short.'.$order->tour->route->currency->alfa) }}
                                ({{ trans('admin_labels.for').' '.$item->pivot->quantity.' '.trans('admin_labels.units') }})<br>
                            @endforeach
                        </td>
                    </tr>
                @endif
                <tr>
                    <td colspan="2" class="table_separator">&nbsp;</td>
                </tr>
                <tr>
                    <td class="table_title">{{ __('print_ticket.old.price', [], $lang) }}:</td>
                    <td class="info">{{ $order->price }}</td>
                </tr>
                
                @if(\App\Models\Setting::first()->anyway_download_tickets)
                    <tr>
                        <td class="table_title">Произведена оплата: </td>
                        <td class="info">{{$order->is_pay ? 'Да' : 'Нет'}}</td>
                    </tr>
                @endif
                <tr>
                    <td colspan="2" class="table_separator">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <small>{{ __('print_ticket.old.note', [], $lang) }}</small>
                    </td>
                </tr>


            </table>


        </td>
        <td style="vertical-align: top">
            <div><h3>*{{ $order->slug }}*</h3></div>
            <div class="title">{{ __('print_ticket.old.ofpassengers', [], $lang) }}</div>
            @if ($order->tour->reservation_by_place)
                <div class="title">{{ __('print_ticket.old.places', [], $lang) }} {{ implode(', ', data_get($order->orderPlaces, '*.number')) }}</div>
            @endif
            <div class="direction">&bull; {{ __('print_ticket.old.departure', [], $lang) }}:</div>
            <div class="direction_block">
                <div class="depurture">
                    {{ $order->from_date_time ? $order->from_date_time->format('H:i d.m.Y') :
                       Carbon\Carbon::parse($order->tour->date_time_start)->format('H:i d.m.Y') }}
                </div>
                <div class="name">{{ $order->stationFrom->city->name }}</div>
                <div class="description">{{ $order->stationFrom->name }}</div>
            <!--<div class="description">{{ $order->tour->bus->name }}, номер: {{ $order->tour->bus->number }}-->
            </div>
            </div>
            <div class="direction">&bull; {{ __('print_ticket.old.arrival', [], $lang) }}:</div>
            <div class="direction_block">
                <div class="depurture">
                    {{ $order->to_date_time ? $order->to_date_time->format('H:i d.m.Y') :
                       Carbon\Carbon::parse($order->tour->date_time_finish)->format('H:i d.m.Y') }}
                </div>
                <div class="name">{{ $order->stationTo->city->name }}</div>
                <div class="description">{{ $order->stationTo->name }}</div>
            </div>

        </td>
    </tr>
    <tr>
        <td colspan="2" class="table_separator">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2">
            <small>{{ __('print_ticket.old.warning', [], $lang) }}
            </small>

        </td>
    </tr>
</table>
<div class="page-break"></div>

<table cellpadding="3" cellspacing="3" width="100%" style="vertical-align: top;padding-top:20px;">
    <tr>
        <td valign="top">1.
        </td>
        <td><b>Откуда отправление?</b><br>
            Из Ижевска отправление от ТЦ Европа (ул. В. Сивкова 150) с большой парковки со стороны ул. В. Сивкова. В аэропорту Казани посадка осуществляется на остановке у входа терминала 1А. В аэропорту Бегишево посадка осуществляется у входа в аэровокзал.
        </td>
    </tr>
    <tr>
        <td valign="top">2.
        </td>
        <td><b>Какое время указано?</b><br>
            Везде указано время того региона, в котором вы находитесь. Время в Ижевске-удмуртское, время в аэропорту-московское.
        </td>
    </tr>

    <tr>
        <td valign="top">3.
        </td>
        <td><b>Нас повезет микроавтобус?</b><br>
            Транспорт назначается накануне в зависимости от количества проданных билетов. Может быть назначен легковой автомобиль, Лада Ларгус (5-6 чел.), минивен (до 8 чел.), микроавтобус (до 20 чел.) или большой туристический автобус.
        </td>
    </tr>
    <tr>
        <td valign="top">4.
        </td>
        <td><b>Как я узнаю назначенный транспорт?</b><br>
            Накануне, за день до отправления, до 20:00 Вам придет смс сообщение  с информацией о транспорте и номером телефона водителя.
        </td>
    </tr>
    <tr>
        <td valign="top">5.
        </td>
        <td><b>Как связаться с диспетчером?</b><br>
            Вы всегда можете обратиться на круглосуточную линию +79128564242, либо написать нам в Viber или WhatsApp по номеру +79128564242.
        </td>
    </tr>
    <tr>
        <td valign="top">6.
        </td>
        <td><b>Что делать, если рейс задержится?</b><br>
            При покупке вы указываете номер рейса самолета. Мы отслеживаем вылеты и просим Вас по возможности
            предупреждать об изменениях, чтобы мы могли перенести Вас на следующий рейс или отложить выезд.
        </td>
    </tr>
    <tr>
        <td valign="top">7.
        </td>
        <td><b>Время выезда может измениться?</b><br>
            Да, время выезда может измениться из-за переносов рейсов самолетов. Мы учитываем Ваше время вылета и прилета. Именно поэтому транспорт мы назначаем за сутки до отправления с учетом всех изменений.<br>
            <b>Напоминаем!</b> Время выезда ориентировочное, может измениться.<br>
            <b>Наша рекомендация:</b>  При покупке билета, обращайте внимание на время вылета и прилета самолета. Предлагаем приобретать билеты в аэропорт, учитывая время начала регистрации (за 2 часа до вылета по России, 3 часа за границу). При покупке билетов на обратный путь, настоятельно советуем закладывать не менее полутора часов на пересадку с самолета в наш транспорт.
        </td>
    </tr>
    <tr>
        <td valign="top">8.
        </td>
        <td><b>Что, если транспорт сломается в пути?</b><br>
            Оперативно организуем альтернативный способ доставки пассажиров до места назначения за счёт нашей компании.
        </td>
    </tr>
    <tr>
        <td valign="top">9.
        </td>
        <td><b>Правила перевозки багажа.</b><br>
            Каждый пассажир имеет право бесплатно провозить один чемодан (сумма трёх сторон не превышающая 130см).  В случае если размер и количество багажа превышают допустимые нормы, то  необходимо  доплатить  в соответствии с багажным тарифом.
        </td>
    </tr>
    <tr>
        <td valign="top">10.
        </td>
        <td><b>Возврат билета.</b><br>
            При срыве рейса по техническим причинам, возврат денежных средств за организацию проезда производиться в полном объеме. Пассажир имеет право вернуть билет, до отправления назначенного  транспорта, при этом размер суммы возврата денежных средств  зависит от даты сдачи билета:
            <ul>
                <li>- за 1 сутки и более до отправления, возврат составит – 100%</li>
                <li>- менее  чем за 1 сутки до отправления, возврат  составит – 50%</li>
                <li>- если пассажир не явился на посадку, возврат составит – 0%</li>
            </ul>
            Возврат билетов осуществляется строго по заявлению, в течении 10 дней на рассмотрении, срок возврата денежных средств от 30 дней.
        </td>
    </tr>
    <tr>
        <td valign="top">11.
        </td>
        <td><b>Правила перевозки детей.</b><br>
            Ребенку от 0 до 14 лет предоставляется отдельное место, на него оформляется билет в соответствии с детским  тарифом. При перевозке в легковом автомобиле детей, в возрасте от 0 до 7 лет, предоставляется детское кресло (люлька). В микроавтобусе и автобусе предусмотрены поясные ремни безопасности.
        </td>
    </tr>
    <tr>
        <td valign="top">12.
        </td>
        <td><b>Сколько часов ориентировочно занимает дорога?</b><br>
            Расчетное время в дороге: Ижевск — Казань: 6 часов, Ижевск — Бегишево: 3.5 часа. В обратном направлении такое же время соответственно.
        </td>
    </tr>

</table>
</body>
</html>

<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        /*margin: 2px;*/
        font-size: 8.5pt;

    }

    table tr td {
        padding: 0 0 0 10px;
    }

    table {
        border-collapse: collapse;
    }

    .table_separator {
        height: 40px;
    }

    .title {
        font-weight: bold;
        font-size: 14pt;
        /*text-transform: uppercase;*/
        margin-bottom: 4px;
    }

    .table_title {
        font-weight: bold;
    }

    .info {
        /*font-weight: bold;*/
    }

    .big {
        font-size: 8pt;
    }

    .page-break {
        page-break-after: always;
    }

    .direction {
        margin: 12pt 0 10pt;
        font-size: 13pt;
        color: #336699;
    }

    .direction_block {
        margin-left: 4pt;
        border-left: 0.1pt solid #336699;
    }

    .depurture {
        font-size: 15pt;
        margin: 5pt;
    }

    .name {
        font-size: 14pt;
        margin: 5pt;
    }

    .description {
        font-size: 10pt;
        margin: 0 5pt;
        color: #336699;
    }
    .page-break {
        page-break-after: always;
    }
</style>
