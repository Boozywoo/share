<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>

    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
@if(count($order->orderPlaces))
    @foreach($order->orderPlaces as $key=>$ticket)
        @if ($key !== 0)
            <div class="page-break"></div>
        @endif
        <div style="text-align: center">
            <img src="{{ public_path("assets/index/images/forpdf/signs.png") }}">
            <h3 style="color: red">{{ __('print_ticket.warning.title', [], $lang) }}:</h3>
        </div>
        <ul>
            <li class="list_warning">{{ __('print_ticket.warning.1', [], $lang) }}</li>
            <li class="list_warning">{{ __('print_ticket.warning.2', [], $lang) }}</li>
            <li class="list_warning">{{ __('print_ticket.warning.3', [], $lang) }}</li>
            <li class="list_warning">{{ __('print_ticket.warning.4', [], $lang) }}</li>
            <li class="list_warning">{{ __('print_ticket.warning.5', [], $lang) }}</li>
            <li class="list_warning">{{ __('print_ticket.warning.6', [], $lang) }}</li>
            <li class="list_warning">{{ __('print_ticket.warning.7', [], $lang) }}</li>
        </ul>
        <table width="100%" class="print">
            <tr>
                <td width="50%" bgcolor="#e2e2e2">
                    <h3>
                        {{ __('print_ticket.ticket_number', [], $lang) }}:
                        <span class="info big">{{ str_pad($ticket->id, 7, "0", STR_PAD_LEFT) }}</span>
                    </h3>
                </td>
                <td class="text-center" style="text-align: center; padding: 10px;">
                    @if($setting->mainImage)
                        <img src="{{ public_path($setting->mainImage->load()) }}">
                    @else
                        <h3>{{ $settings->company_name }}</h3>
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    {{ __('print_ticket.passenger', [], $lang) }}:
                    <div class="info big">
                        {{ ($ticket->name) ? $ticket->name : $order->client->fullname }}
                        {{ ($ticket->surname) ? $ticket->surname : '' }}
                    </div>
                    <div>({{ __('print_ticket.phone', [], $lang) }} {{ $order->client->phone }})</div>
                    <br>
                    <div>{{ __('print_ticket.date_start', [], $lang) }} <span
                                class="info big">
                            {{ $order->station_from_time ? date('H:i', strtotime($order->station_from_time)) :
                                $order->tour->prettyDateStart }}</span></div>
                    <div>{{ __('print_ticket.time_start', [], $lang) }} <span
                                class="info big">
                            {{ $order->from_date_time ? $order->from_date_time->format('d.m.Y') :
                                $order->tour->prettyTimeStart }}</span></div>

                </td>
            </tr>
        </table>
        <br>
        @if($order->addServices->count() && $key == 0)
            <table width="100%" class="print">
                <tr>
                    <td bgcolor="#e2e2e2" colspan="3" class="center">{{ __('print_ticket.old.add_services', [], $lang) }}:</td>
                </tr>
                <tr bgcolor="#e2e2e2" >
                    <td>{{ __('admin_labels.service', [], $lang) }}</td>
                    <td class="center">{{ __('admin_labels.cost', [], $lang) }}</td>
                    <td class="center">{{ __('admin_labels.quantity', [], $lang) }}</td>
                </tr>
                @foreach ($order->addServices as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td class="center">{{ $item->value.' '.__('admin_labels.currencies_short.'.$order->tour->route->currency->alfa, [], $lang) }}</td>
                        <td class="center">{{ $item->pivot->quantity }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3">{{ __('admin.orders.add_services_total', [], $lang) }}:
                        {{ $order->addServicesCost().' '.__('admin_labels.currencies_short.'.$order->tour->route->currency->alfa, [], $lang) }}
                    </td>
                </tr>
            </table>
            <br>
        @endif
        <table width="100%" class="print">
            <tr>
                <td>
                    {{ __('print_ticket.price', [], $lang) }}:
                    <span class="info">{{ $ticket->price.' '.__('admin_labels.currencies_short.'.$order->tour->route->currency->alfa, [], $lang) }}</span>
                    <div>
                        {{ __('print_ticket.tariff_plan', [], $lang) }}:
                        <span class="info">{{ __('print_ticket.temporary.plan', [], $lang) }}</span>
                    </div>
                    @if(\App\Models\Setting::first()->anyway_download_tickets)
                        <div>
                        Произведена оплата:  
                            <span class="info">{{$order->is_pay ? 'Да' : 'Нет'}}</span>
                        </div>
                    @endif
                </td>
                <td>
                    <div>
                        {{ __('print_ticket.date_of_sale', [], $lang) }}:
                        <span class="info">{{ $date_issue }}</span>
                    </div>
                    <div>
                        {{ __('print_ticket.payer', [], $lang) }}:
                        <span class="info">{{ $order->client->fullname }}</span>
                    </div>
                </td>
            </tr>
        </table>
        <br>
        <table width="100%" class="print">
            <tr>
                <td bgcolor="#e2e2e2">{{ __('print_ticket.ticket_cancellation_policy', [], $lang) }}</td>
            </tr>
            <tr>
                <td>
                    {{ __('print_ticket.phone', [], $lang) }} <span
                            class="info">{{ $settings->ticket_cancel_phone }}</span>
                    <div class="info">{{ $settings->ticket_cancel_info }}</div>
                </td>
            </tr>
        </table>
        <div class="separator">{{ __('print_ticket.dividing_line', [], $lang) }}</div>
        <table width="100%" class="print">
            <tr>
                <td colspan="3" width="15%">{{ __('print_ticket.fare_calculation', [], $lang) }}
                    <div class="info"></div>
                </td>
                <td width="50%" colspan="6" class="center">
                    <div class="title">{{ __('print_ticket.bus_ticket', [], $lang) }} #{{ $ticket->id }}</div>
                    <div>{{ __('print_ticket.passenger_ticket_and_baggage_check', [], $lang) }}</div>
                </td>
                <td width="20%" colspan="2">{{ __('print_ticket.audit_coupon', [], $lang) }}
                    <div class="info big">AB {{ str_pad($ticket->id, 7, "0", STR_PAD_LEFT) }}</div>
                </td>
            </tr>
            <tr>
                <td rowspan="6" width="5%">{{ __('print_ticket.component', [], $lang) }}
                    <div class="info"></div>
                </td>
                <td rowspan="6" width="5%">{{ __('print_ticket.curp', [], $lang) }}
                    <div class="info"></div>
                </td>
                <td rowspan="6" width="5%">{{ __('print_ticket.fare', [], $lang) }}
                    <div class="info"></div>
                </td>
                <td width="30%" colspan="6">{{ __('print_ticket.name_of_passenger', [], $lang) }}
                    ({{ __('print_ticket.not_transferable', [], $lang) }})
                    <div class="info big">
                        {{ ($ticket->name) ? $ticket->name : $order->client->fullname }}
                        {{ ($ticket->surname) ? $ticket->surname : '' }}
                        ({{ __('print_ticket.phone', [], $lang) }} {{ ($ticket->phone)?$ticket->phone:$order->client->phone }}
                        )
                    </div>
                </td>
                <td rowspan="2" colspan="2">{{ __('print_ticket.agent_stamp', [], $lang) }}
                    <div class="info"></div>
                </td>
            </tr>
            <tr>
                <td colspan="3">{{ __('print_ticket.carrier', [], $lang) }}
                    <div class="info">{{ $settings->company_name }}</div>
                </td>
                <td colspan="3">{{ __('print_ticket.insurance_company', [], $lang) }}
                </td>
            </tr>
            <tr>
                <td colspan="6">{{ __('print_ticket.type_of_insurance', [], $lang) }}</td>
                <td colspan="2">{{ __('print_ticket.issue_date', [], $lang) }}
                    <div class="info">
                        {{ $date_issue }}
                    </div>
                </td>
            </tr>
            <tr>
                <td class="center">{{ __('print_ticket.route', [], $lang) }}</td>
                <td class="center">{{ __('print_ticket.code', [], $lang) }}</td>
                <td class="center">{{ __('print_ticket.trip', [], $lang) }}</td>
                <td class="center">{{ __('print_ticket.date', [], $lang) }}</td>
                <td class="center">{{ __('print_ticket.time', [], $lang) }}</td>
                <td class="center">{{ __('print_ticket.place', [], $lang) }}</td>
                <td class="center">{{ __('print_ticket.fare', [], $lang) }}</td>
                <td class="center">{{ __('print_ticket.baggage', [], $lang) }}</td>
            </tr>
            <tr>
                <td>{{ __('print_ticket.from', [], $lang) }}
                    <div class="info">{{ $order->stationFrom->city->name }}, {{ $order->stationFrom->name }}</div>
                </td>
                <td>{{ $order->slug }}</td>
                <td></td>
                <th>
                    <div class="info">
                    {{ $order->from_date_time ? $order->from_date_time->format('d.m.Y') :
                                $order->tour->prettyDateStart }}
                    </div>
                </th>
                <th>
                    <div class="info">
                        {{ $order->from_date_time ? $order->from_date_time->format('H:i') :
                                $order->tour->prettyTimeStart }}
                    </div>
                </th>
                <th>{{ ($ticket->number) }}</th>
                <th>{{ ($ticket->price) }}</th>
                <td></td>
            </tr>
            <tr>
                <td>{{ __('print_ticket.to', [], $lang) }}
                    <div class="info">{{ $order->stationTo->city->name }}, {{ $order->stationTo->name }}</div>
                </td>
                <td colspan="7">{{ __('print_ticket.overhead_information', [], $lang) }}</td>
            </tr>
            <tr>
                <td width="15%" colspan="3">{{ __('print_ticket.form_of_payment', [], $lang) }}
                    <div class="info">&nbsp;</div>
                </td>
                <td>{{ __('print_ticket.curp', [], $lang) }}
                    <div class="info">{{ $order->tour->route->currency->name }}</div>
                </td>
                <td colspan="2">{{ __('print_ticket.insurance', [], $lang) }}
                    <div class="info">{{ $order->tour->route->currency->name }} {{ ($ticket->price*0.02) }}</div>
                </td>
                <td>{{ __('print_ticket.tax', [], $lang) }}
                    <div class="info">&nbsp;</div>
                </td>
                <td>{{ __('print_ticket.charge', [], $lang) }}
                    <div class="info">&nbsp;</div>
                </td>
                <th>{{ __('print_ticket.arrival', [], $lang) }}
                    <div class="info">
                        {{ $order->to_date_time ? $order->to_date_time->format('H:i d.m.Y') :
                               $order->tour->prettyTimeFinish }}
                    </div>
                </th>
                <td colspan="2">{{ __('print_ticket.check_number', [], $lang) }}
                    <div class="info"></div>
                </td>
            </tr>
            <tr>
                <td colspan="8">
                    {{ $settings->ticket_info }}
                </td>
                <td colspan="3">&nbsp;</td>
            </tr>
        </table>
    @endforeach
@endif
<style>
    .print, .print td, .print tr {
        vertical-align: top;
    }

    table tr td {
        padding: 0 0 0 3px;
    }

    .center {
        text-align: center;
    }

    table {
        border-collapse: collapse;
    }

    table, td, th {
        border: 1px solid black;
    }

    .title {
        font-weight: bold;
        font-size: 12pt;
        text-transform: uppercase;
    }

    .info {
        font-weight: bold;
    }

    .big {
        font-size: 8pt;
    }

    .page-break {
        page-break-after: always;
    }

    @page {
        size: 21cm 29.7cm;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 7pt;

    }

    .list_warning {
        color:#336699;
        font-size: 7pt;
    }

    .separator {
        margin: 20px 0;
        border-bottom: 1px dashed red;
        font-style: italic;
        color:red;
    }
</style>
</body>
</html>


