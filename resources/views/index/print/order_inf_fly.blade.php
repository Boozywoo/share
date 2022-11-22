<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>

    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body class="c1">
@if(count($order->orderPlaces))
    @foreach($order->orderPlaces as $key=>$ticket)
        <div style="">
            <p class="c14">
                <span class="c16 c17">{{ trans('print_order.title-top') }} minsk2.by</span>
                <img alt="" src="{{ public_path("assets/index/images/forpdf/logo.png") }}" style="position: absolute;top:
        0px;right: -40px;width: 114.47px; height: 63.47px;" title="">
            </p>
            <p class="c14">
                <span class="c12">{{ __('print_order.title-name', [], $lang) }}, {{ ($ticket->name) ? $ticket->name : $order->client->fullname }} {{ ($ticket->surname) ? $ticket->surname : '' }}<br></span>
                <span class="c16"><br></span>
                <span class="c11">{{ __('print_order.your-order', [], $lang) }} № {{ str_pad($ticket->id, 7, "0", STR_PAD_LEFT) }} {{ __('print_order.from', [], $lang) }} {{ $date_issue }}
                    @if($order->statusPay == 'success')
                        <span style="color: green;font-size: 14pt;">{{ __('print_order.payment', [], $lang) }}</span>
                    @else
                        {{ __('print_order.not_payment', [], $lang) }}<br>
                        @if ($order->type_pay !== \App\Models\Order::TYPE_PAY_CASH_PAYMENT && $order->type_pay !== \App\Models\Order::TYPE_PAY_SUCCESS) 
                            <span class="payment">{{ __('print_order.not_payment_inf1', [], $lang) }}
                                {{\Carbon\Carbon::make($order->created_at)->addMinutes('20')}}
                                {{ __('print_order.not_payment_inf2', [], $lang) }}
                            </span>
                        @endif
                    @endif
                    <br>
                    </span>
                {{-- <span class="c13">{{ __('print_order.dear', [], $lang) }} {{ ($ticket->name) ? $ticket->name.' ' : $order->client->fullname }}{{ ($ticket->surname) ? $ticket->surname : '' }},</span>
                <span class="c11">&nbsp;<br></span> --}}
                <span class="c17 c13">{{ __('print_order.thanks', [], $lang) }}!<br></span>
                <span class="map">{{ __('print_order.map', [], $lang) }}!</span>
            </p>

            <p class="c14">
                <span class="c3">{{ __('print_order.details', [], $lang) }}:</span>
            </p>

            <table class="c20" style="margin-bottom: 0px;">
                <tbody>
                <tr class="c5">
                    <td class="c4" colspan="1" rowspan="1">
                        <p class="c0">
                            <span class="c3">{{ __('print_order.dispatcher', [], $lang) }}:</span>
                        </p>
                    </td>
                    <td class="c6" colspan="1" rowspan="1">
                        <p class="c2">
                            <span class="c3">{{$dispatcher->name ?? ''}}</span>
                        </p>
                    </td>
                </tr>
                <tr class="c5">
                    <td class="c4" colspan="1" rowspan="1">
                        <p class="c0">
                            <span class="c3">{{ __('print_order.UNP', [], $lang) }}</span>
                        </p>
                    </td>
                    <td class="c6" colspan="1" rowspan="1">
                        <p class="c2">
                            <span class="c3">{{$dispatcher->requisites ?? ''}}</span>
                        </p>
                    </td>
                </tr>
                <tr class="c5">
                    <td class="c4" colspan="1" rowspan="1">
                        <p class="c0">
                            <span class="c3">{{ __('print_order.carrier', [], $lang) }}:</span>
                        </p>
                    </td>
                    <td class="c6" colspan="1" rowspan="1">
                        <p class="c2">
                            <span class="c3">{{$order->tour->bus->company->name}}</span>
                        </p>
                    </td>
                </tr>
                <tr class="c5">
                    <td class="c4" colspan="1" rowspan="1">
                        <p class="c0">
                            <span class="c3">{{ __('print_order.UNP', [], $lang) }}:</span>
                        </p>
                    </td>
                    <td class="c6" colspan="1" rowspan="1">
                        <p class="c2">
                            <span class="c3">{{$order->tour->bus->company->requisites}}</span>
                        </p>
                    </td>
                </tr>
                <tr class="c5">
                    <td class="c4 c7" colspan="1" rowspan="1">
                        <p class="c0 c10">
                            <span class="c3"></span>
                        </p>
                    </td>
                    <td class="c6 c7" colspan="1" rowspan="1">
                        <p class="c2 c10">
                            <span class="c3"></span>
                        </p>
                    </td>
                </tr>
                <tr class="c5">
                    <td class="c4" colspan="1" rowspan="1">
                        <p class="c0">
                            <span class="c3">{{ __('print_order.order-number', [], $lang) }}:</span>
                        </p>
                    </td>
                    <td class="c6" colspan="1" rowspan="1">
                        <p class="c2">
                            <span class="c3">{{$order->slug}}</span>
                        </p>
                    </td>
                </tr>
                <tr class="c5">
                    <td class="c4" colspan="1" rowspan="1">
                        <p class="c0">
                            <span class="c3">{{ __('print_order.order-date', [], $lang) }}:</span>
                        </p>
                    </td>
                    <td class="c6" colspan="1" rowspan="1">
                        <p class="c2">
                            <span class="c3">{{ $date_issue }}</span>
                        </p>
                    </td>
                </tr>
                <tr class="c5">
                    <td class="c4" colspan="1" rowspan="1">
                        <p class="c0">
                            <span class="c3">{{ __('print_order.customer', [], $lang) }}:</span>
                        </p>
                    </td>
                    <td class="c6" colspan="1" rowspan="1">
                        <p class="c2">
                            <span class="c3">{{ ($ticket->name) ? $ticket->name : $order->client->fullname }} {{ ($ticket->surname) ? $ticket->surname : '' }}</span>
                        </p>
                    </td>
                </tr>
                <tr class="c5">
                    <td class="c4" colspan="1" rowspan="1">
                        <p class="c0">
                            <span class="c3">{{ __('print_order.passengers', [], $lang) }}:</span>
                        </p>
                    </td>
                    <td class="c6" colspan="1" rowspan="1">
                        <p class="c2">
                            <span class="c3">{{$order->count_places}}</span>
                        </p>
                    </td>
                </tr>
                <tr class="c5">
                    <td class="c4" colspan="1" rowspan="1">
                        <p class="c0">
                            <span class="c3">{{ __('print_order.starting-point', [], $lang) }}:</span>
                        </p>
                    </td>
                    <td class="c6" colspan="1" rowspan="1">
                        <p class="c2">
                            <span class="c3">{{ $order->stationFrom->city->name }},
                                {{ $address_from }}</span>
                        </p>
                    </td>
                </tr>
                <tr class="c5">
                    <td class="c4" colspan="1" rowspan="1">
                        <p class="c0">
                            <span class="c3">{{ __('print_order.destination-point', [], $lang) }}:</span>
                        </p>
                    </td>
                    <td class="c6" colspan="1" rowspan="1">
                        <p class="c2">
                            <span class="c3">{{ $order->stationTo->city->name }}, {{ $order->address_to }}</span>
                        </p>
                    </td>
                </tr>
                <tr class="c5">
                    <td class="c4" colspan="1" rowspan="1">
                        <p class="c0">
                            <span class="c3">{{ __('print_order.distance', [], $lang) }}:</span>
                        </p>
                    </td>
                    <td class="c6" colspan="1" rowspan="1">
                        <p class="c2">
                            <span class="c3">{{$order->tour->route->mileage}} {{ __('print_order.km', [], $lang) }}</span>
                        </p>
                    </td>
                </tr>
                <tr class="c5">
                    <td class="c4" colspan="1" rowspan="1">
                        <p class="c0">
                            <span class="c3">{{ __('print_order.estimated-start', [], $lang) }}:</span>
                        </p>
                    </td>
                    <td class="c6" colspan="1" rowspan="1">
                        <p class="c2">
                    <span class="c3">
                        {{ $order->from_date_time ? $order->from_date_time->format('H:i') :
                                                            $order->tour->prettyTimeStart }},
                        {{ $order->from_date_time ? $order->from_date_time->format('d.m.Y') :
                                        $order->tour->prettyDateStart }}

                    </span>
                        </p>
                    </td>
                </tr>
                <tr class="c5">
                    <td class="c4" colspan="1" rowspan="1">
                        <p class="c0">
                            <span class="c3">{{ __('print_order.estimated-end', [], $lang) }}:</span>
                        </p>
                    </td>
                    <td class="c6" colspan="1" rowspan="1">
                        <p class="c2">
                    <span class="c3">
                        {{ $order->to_date_time ? $order->to_date_time->format('H:i, d.m.Y') :
                                   $order->tour->prettyTimeFinish }}
                    </span>
                        </p>
                    </td>
                </tr>

                <tr class="c5">
                    <td class="c4" colspan="1" rowspan="1">
                        <p class="c0">
                            <span class="c3">{{ __('print_order.travel-time', [], $lang) }}:</span>
                        </p>
                    </td>

                    <td class="c6" colspan="1" rowspan="1">
                        <p class="c2">
                            <span class="c3">{{\Carbon\Carbon::create($tour->time_start)->diffInMinutes($tour->time_finish)}} {{ __('print_order.minutes', [], $lang) }}</span>
                        </p>
                    </td>
                </tr>

                <tr class="c5">
                    <td class="c4" colspan="1" rowspan="1">
                        <p class="c0">
                            <span class="c3">{{ __('print_order.name-driver', [], $lang) }}:</span>
                        </p>
                    </td>
                    <td class="c6" colspan="1" rowspan="1">
                        <p class="c2">
                            <span class="c3">{{$tour->driver->last_name .' '.$tour->driver->full_name. ' '. $tour->driver->middle_name}}</span>
                        </p>
                    </td>
                </tr>
                <tr class="c5">
                    <td class="c4" colspan="1" rowspan="1">
                        <p class="c0">
                            <span class="c3">{{ __('print_order.phone-driver', [], $lang) }}</span>
                        </p>
                    </td>
                    <td class="c6" colspan="1" rowspan="1">
                        <p class="c2">
                            <span class="c11 c18"><a href="tel:{{ $tour->driver->phone }}">{{ $tour->driver->phone }}</a></span>
                        </p>
                    </td>
                </tr>
                <tr class="c5">
                    <td class="c4 c7" colspan="1" rowspan="1">
                        <p class="c0 c10">
                            <span class="c3"></span>
                        </p>
                    </td>
                    <td class="c6 c7" colspan="1" rowspan="1">
                        <p class="c2 c10">
                            <span class="c3"></span>
                        </p>
                    </td>
                </tr>
                <tr class="c5">
                    <td class="c4" colspan="1" rowspan="1">
                        <p class="c0">
                            <span class="c3">{{ __('print_order.car', [], $lang) }}:</span>
                        </p>
                    </td>
                    <td class="c6" colspan="1" rowspan="1">
                        <p class="c2">
                            <span class="c11">{{$bus->name}}</span>
                        </p>
                    </td>
                </tr>
                <tr class="c5">
                    <td class="c4" colspan="1" rowspan="1">
                        <p class="c0">
                            <span class="c3">{{ __('print_order.car-registration', [], $lang) }}:</span>
                        </p>
                    </td>
                    <td class="c6" colspan="1" rowspan="1">
                        <p class="c2">
                            <span class="c11">{{$bus->number}}</span>
                        </p>
                    </td>
                </tr>
                <tr class="c5">
                    <td class="c4" colspan="1" rowspan="1">
                        <p class="c0">
                            <span class="c3">{{ __('print_order.price', [], $lang) }}:</span>
                        </p>
                    </td>
                    <td class="c6" colspan="1" rowspan="1">
                        <p class="c2">
                            <span class="c11">{{ $ticket->price.' '.__('admin_labels.currencies_short.'.$order->tour->route->currency->alfa, [], $lang) }}</span>
                        </p>
                    </td>
                </tr>
                <tr class="c19">
                    <td class="c4" colspan="1" rowspan="1">
                        <p class="c0">
                            <span class="c3">{{ __('print_order.transaction-number', [], $lang) }}:</span>
                        </p>
                    </td>
                    <td class="c6" colspan="1" rowspan="1">
                        <p class="c2">
                            <span class="c3">{{$order->pay_id}}</span>
                        </p>
                    </td>
                </tr>
                <tr class="c5">
                    <td class="c4" colspan="1" rowspan="1">
                        <p class="c0">
                            <span class="c3">{{ __('print_order.transaction-date', [], $lang) }}:</span>
                        </p>
                    </td>
                    <td class="c6" colspan="1" rowspan="1">
                        <p class="c2">
                            <span class="c3">{{''}}</span>
                        </p>
                    </td>
                </tr>
                <tr class="c5">
                    <td class="c4" colspan="1" rowspan="1">
                        <p class="c0">
                            <span class="c3">{{ __('print_order.payment-method', [], $lang) }}: </span>
                        </p>
                    </td>
                    <td class="c6" colspan="1" rowspan="1">
                        <p class="c2">
                            <span class="c3">
                                @if (in_array($order->type_pay, [\App\Models\Order::TYPE_PAY_SUCCESS, \App\Models\Order::TYPE_PAY_WAIT])) Онлайн @endif
                                @if ($order->type_pay == \App\Models\Order::TYPE_PAY_CASH_PAYMENT) {{ trans('admin.orders.pay_types.cash-payment') }} @endif
                            </span>
                        </p>
                    </td>
                </tr>
                <tr class="c5">
                    <td class="c4" colspan="2" rowspan="2" style="border: none;">
                        <p class="c14" style="padding-top: 20px">
                            <span class="c11">{{ __('print_order.total-cost', [], $lang) }}: </span>
                            <span class="c13">{{ $ticket->price.' '.__('admin_labels.currencies_short.'.$order->tour->route->currency->alfa, [], $lang) }}</span>
                            <span class="c11"><br><br>{{ __('print_order.regards', [], $lang) }}</span>
                            <span class="c13">{{$dispatcher->name ?? ''}}<br></span>
                            <span class="c11">E-mail: </span>
                            <span class="c18 c11"><a class="c8" href="mailto:flyshuttleby@gmail.com">flyshuttleby@gmail.com</a></span>
                        </p>
                    </td>

                </tr>
                </tbody>
            </table>
        </div>

    @endforeach

    {{--<div>
        <img alt="" src="{{ public_path("assets/index/images/forpdf/map.jpg") }}" style="width: 650px; height: 900px;"
             title="">
    </div>--}} 
@endif
<style type="text/css">
    ol {
        margin: 0;
        padding: 0
    }

    table td, table th {
        padding: 0
    }

    .c4 {
        border-right-style: solid;
        padding: 0pt 5.4pt 0pt 5.4pt;
        border-bottom-color: #000000;
        border-top-width: 1pt;
        border-right-width: 1pt;
        border-left-color: #000000;
        vertical-align: top;
        border-right-color: #000000;
        border-left-width: 1pt;
        border-top-style: solid;
        border-left-style: solid;
        border-bottom-width: 1pt;
        width: 233.6pt;
        border-top-color: #000000;
        border-bottom-style: solid
    }

    .c6 {
        border-right-style: solid;
        padding: 0pt 5.4pt 0pt 5.4pt;
        border-bottom-color: #000000;
        border-top-width: 1pt;
        border-right-width: 1pt;
        border-left-color: #000000;
        vertical-align: top;
        border-right-color: #000000;
        border-left-width: 1pt;
        border-top-style: solid;
        border-left-style: solid;
        border-bottom-width: 1pt;
        width: 233.7pt;
        border-top-color: #000000;
        border-bottom-style: solid
    }

    .c0 {
        padding-top: 0pt;
        padding-bottom: 0pt;
        line-height: 1.0;
        orphans: 2;
        widows: 2;
        text-align: right;
        margin-right: 7.9pt
    }

    .c9 {
        color: #000000;
        font-weight: 400;
        text-decoration: none;
        vertical-align: baseline;
        font-size: 10pt;
        font-style: normal
    }

    .c3 {
        color: #000000;
        font-weight: 400;
        text-decoration: none;
        vertical-align: baseline;
        font-size: 9pt;
        font-style: normal
    }

    .c2 {
        padding-top: 0pt;
        padding-bottom: 0pt;
        line-height: 1.0;
        orphans: 2;
        widows: 2;
        text-align: left
    }

    .c14 {
        padding-top: 0pt;
        padding-bottom: 10pt;
        line-height: 1.1500000000000001;
        orphans: 2;
        widows: 2;
        position: relative;
        text-align: left
    }

    .c20 {

    }

    .c18 {
        -webkit-text-decoration-skip: none;
        color: #0000ff;
        text-decoration: underline;
        text-decoration-skip-ink: none
    }

    .c17 {
        color: #000000;
        text-decoration: none;
        vertical-align: baseline;
        font-style: normal
    }

    .c11 {
        font-size: 9pt;
        font-weight: 400
    }

    .map {
        font-size: 7pt;
        font-weight: 290;
        color: #808080
    }

    .payment {
        font-size: 9pt;
        font-weight: 290;
        color: red
    }

    .c13 {
        font-size: 9pt;
        font-weight: 700
    }

    .c16 {
        font-size: 17pt;
        font-weight: 400
    }

    .c1 {
        background-color: #ffffff;
        max-width: 467.7pt;
        margin: auto;
        padding-top: 50px;
    }

    .c12 {
        font-size: 15pt;
        font-weight: 700
    }

    .c8 {
        color: inherit;
        text-decoration: inherit
    }

    .c5 {
        height: 0pt
    }

    .c10 {
        height: 11pt
    }

    .c19 {
        height: 9.8pt
    }

    .c7 {
        background-color: #f2f2f2
    }

    .title {
        padding-top: 24pt;
        color: #000000;
        font-weight: 700;
        font-size: 35pt;
        padding-bottom: 6pt;
        line-height: 1.1500000000000001;
        page-break-after: avoid;
        orphans: 2;
        widows: 2;
        text-align: left
    }

    .subtitle {
        padding-top: 18pt;
        color: #666666;
        font-size: 23pt;
        padding-bottom: 4pt;
        line-height: 1.1500000000000001;
        page-break-after: avoid;
        font-style: italic;
        orphans: 2;
        widows: 2;
        text-align: left
    }

    li {
        color: #000000;
        font-size: 11pt;
    }

    p {
        margin: 0;
        color: #000000;
        font-size: 11pt;
    }

    h1 {
        padding-top: 24pt;
        color: #000000;
        font-weight: 700;
        font-size: 23pt;
        padding-bottom: 6pt;
        line-height: 1.1500000000000001;
        page-break-after: avoid;
        orphans: 2;
        widows: 2;
        text-align: left
    }

    h2 {
        padding-top: 18pt;
        color: #000000;
        font-weight: 700;
        font-size: 17pt;
        padding-bottom: 4pt;
        line-height: 1.1500000000000001;
        page-break-after: avoid;
        orphans: 2;
        widows: 2;
        text-align: left
    }

    h3 {
        padding-top: 14pt;
        color: #000000;
        font-weight: 700;
        font-size: 13pt;
        padding-bottom: 4pt;
        line-height: 1.1500000000000001;
        page-break-after: avoid;
        orphans: 2;
        widows: 2;
        text-align: left
    }

    h4 {
        padding-top: 12pt;
        color: #000000;
        font-weight: 700;
        font-size: 11pt;
        padding-bottom: 2pt;
        line-height: 1.1500000000000001;
        page-break-after: avoid;
        orphans: 2;
        widows: 2;
        text-align: left
    }

    h5 {
        padding-top: 11pt;
        color: #000000;
        font-weight: 700;
        font-size: 10pt;
        padding-bottom: 2pt;
        line-height: 1.1500000000000001;
        page-break-after: avoid;
        orphans: 2;
        widows: 2;
        text-align: left
    }

    h6 {
        padding-top: 10pt;
        color: #000000;
        font-weight: 700;
        font-size: 9pt;
        padding-bottom: 2pt;
        line-height: 1.1500000000000001;
        page-break-after: avoid;
        orphans: 2;
        widows: 2;
        text-align: left
    }

    body {
        font-family: DejaVu Sans, sans-serif;
    }
</style>
</body>
</html>


