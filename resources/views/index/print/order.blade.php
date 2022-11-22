<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<table cellpadding="3" cellspacing="3">
    <tr>
        <td width="50%">
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
                    <td class="info">{{ $order->tour->bus->company->name }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="table_separator">&nbsp;</td>
                </tr>
                <tr>
                    <td class="table_title">{{ __('print_ticket.old.ticket', [], $lang) }}:</td>
                    <td class="info">{{ __('print_ticket.old.#', [], $lang) }}{{ $order->slug }}, {{ __('print_ticket.old.tariff', [], $lang) }}</td>
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
                    <td class="table_title">{{ __('admin_labels.total_sum', [], $lang) }}:</td>
                    <td class="info">{{ $order->price }} {{ trans('admin_labels.currencies_short.'.$order->tour->route->currency->alfa) }}</td>
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
                    <td colspan="2"><small>{{ __('print_ticket.old.note', [], $lang) }}</small>
                    </td>
                </tr>


            </table>


        </td>
        <td style="vertical-align: top">
            <div><h3>*{{ $order->slug }}*</h3></div>
            <div class="title">{{ __('print_ticket.old.ofpassengers', [], $lang) }}</div>

            <div class="title">
                {{ __('print_ticket.old.'.($order->orderPlaces->count() > 1 ? 'places' : 'place'), [], $lang) }}
                {{ implode(', ', data_get($order->orderPlaces, '*.number')) }}
            </div>

            <div class="direction">&bull; {{ __('print_ticket.old.departure', [], $lang) }}:</div>
            <div class="direction_block">
                <div class="depurture">
                    {{ $order->from_date_time ? $order->from_date_time->format('H:i d.m.Y') :
                       Carbon\Carbon::parse($order->tour->date_time_start)->format('H:i d.m.Y') }}
                </div>
                <div class="name">{{ $order->stationFrom->city->name }}</div>
                <div class="description">{{ $order->addressFrom ?? $order->stationFrom->name}}</div>
                <div class="description">{{ $order->tour->bus->name }}, номер: {{ $order->tour->bus->number }}
                </div>
            </div>
            <div class="direction">&bull; {{ __('print_ticket.old.arrival', [], $lang) }}:</div>
            <div class="direction_block">
                <div class="depurture">
                    {{ $order->to_date_time ? $order->to_date_time->format('H:i d.m.Y') :
                       Carbon\Carbon::parse($order->tour->date_time_finish)->format('H:i d.m.Y') }}
                </div>
                <div class="name">{{ $order->stationTo->city->name }}</div>
                <div class="description">{{ $order->addressTo ?? $order->stationTo->name}}</div>
            </div>

        </td>
    </tr>
    <tr>
        <td colspan="2" class="table_separator">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2"><small>{{ __('print_ticket.old.warning', [], $lang) }}
            </small>

        </td>
    </tr>
</table>

</body>
</html>

<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        /*margin: 2px;*/
        font-size: 10pt;

    }

    table tr td {
        padding: 0 0 0 3px;
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
</style>
