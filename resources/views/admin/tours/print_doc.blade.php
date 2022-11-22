<html>
<head>
    <meta content="text/html; charset=UTF-8" http-equiv="content-type">
</head>
<body class="c54">

<table class="c69">
    <tbody>
    <tr class="c17">
        <td class="c71" colspan="5" rowspan="4">
            <p class=""><span class="c0">{{trans('print_document.date')}}: {{\Carbon\Carbon::make($tour->date_start)->format('Y-m-d')}}</span></p>
            <p class=""><span class="c0">{{trans('print_document.from_city')}} {{$tour->route->stations->first()->city->name}}</span></p>
            <p class=""><span class="c0">{{trans('print_document.time_start')}} {{$tour->time_start}}</span></p>
        </td>
        <td class="c7" colspan="1" rowspan="1"><p class="c4"><span class="c0"></span></p></td>
        <td class="c43" colspan="6" rowspan="4">
            <p class=""><span class="c0">{{trans('print_document.title')}} № 1</span></p>
            <p class=""><span class="c0">{{trans('print_document.header_to_the_lease')}} № __________ ({{trans('print_document.header_time_chartering')}})</span></p>
            <p class=""><span class="c0">от «__» ______________ 2021 {{trans('dates.year.short')}}.     </span></p>
            <p class=""><span class="c0">{{trans('print_document.charterer')}}:  </span></p>
        </td>
    </tr>
    <tr class="c17">
        <td class="c7" colspan="1" rowspan="1"><p class="c4"><span class="c0"></span></p></td>
    </tr>
    <tr class="c17">
        <td class="c7" colspan="1" rowspan="1"><p class="c4"><span class="c0"></span></p></td>
    </tr>
    <tr class="c17">
        <td class="c7" colspan="1" rowspan="1"><p class="c4"><span class="c0"></span></p></td>
    </tr>
    <tr class="c17">
        <td class="c66" colspan="1" rowspan="1"><p class="c4"><span class="c0"></span></p></td>
        <td class="c57" colspan="1" rowspan="1"><p class="c4"><span class="c0"></span></p></td>
        <td class="c19" colspan="1" rowspan="1"><p class="c4"><span class="c0"></span></p></td>
        <td class="c37" colspan="1" rowspan="1"><p class="c4"><span class="c0"></span></p></td>
        <td class="c66" colspan="1" rowspan="1"><p class="c4"><span class="c0"></span></p></td>
        <td class="c15" colspan="1" rowspan="1"><p class="c4"><span class="c0"></span></p></td>
        <td class="c68" colspan="1" rowspan="1"><p class="c4"><span class="c0"></span></p></td>
        <td class="c61" colspan="1" rowspan="1"><p class="c4"><span class="c0"></span></p></td>
        <td class="c37" colspan="1" rowspan="1"><p class="c4"><span class="c0"></span></p></td>
        <td class="c37" colspan="1" rowspan="1"><p class="c4"><span class="c0"></span></p></td>
        <td class="c56" colspan="1" rowspan="1"><p class="c4"><span class="c0"></span></p></td>
        <td class="c37" colspan="1" rowspan="1"><p class="c4"><span class="c0"></span></p></td>
    </tr>
    <tr>
        <td class="c28" colspan="1" rowspan="1"><p class="c5"><span class="c0">№ </span></p></td>
        <td class="c25" colspan="1" rowspan="1"><p class="c5"><span class="c0">{{trans('print_document.ticket')}}</span></p></td>
        <td class="c11" colspan="1" rowspan="1"><p class="c5"><span class="c0">{{trans('admin.clients.surname')}}</span></p></td>
        <td class="c3" colspan="1" rowspan="1"><p class="c5"><span class="c0"></span>{{trans('print_document.birth_day')}}</p></td>
        <td class="c28" colspan="1" rowspan="1"><p class="c5"><span class="c0">{{trans('print_document.phone')}}</span></p></td>
        <td class="c9" colspan="1" rowspan="1"><p class="c5"><span class="c0">{{trans('print_document.station')}}</span></p></td>
        <td class="c9" colspan="1" rowspan="1"><p class="c5"><span class="c0">{{trans('admin_labels.date_start_time')}}</span></p></td>
        <td class="c9" colspan="1" rowspan="1"><p class="c5"><span class="c0">{{trans('print_document.station_to')}}</span></p></td>
        <td class="c30" colspan="1" rowspan="1"><p class="c5"><span class="c0">{{trans('print_document.document')}} №</span></p></td>
        <td class="c23" colspan="1" rowspan="1"><p class="c5"><span class="c0">{{trans('print_document.comment')}}.</span></p></td>
        <td class="c3" colspan="1" rowspan="1"><p class="c5"><span class="c0">{{trans('print_document.tariff')}}</span></p></td>
        <td class="c3" colspan="1" rowspan="1"><p class="c5"><span class="c0">{{trans('print_document.pay')}}.</span></p></td>
        <td class="c24" colspan="1" rowspan="1"><p class="c5"><span class="c0">{{trans('print_document.type_pay')}}.</span></p></td>
        <td class="c3" colspan="1" rowspan="1"><p class="c5"><span class="c0">{{trans('print_document.bonuses')}}</span>
            </p></td>
    </tr>
    @if(count($orders))

        @foreach($orders as $key => $order)
            @php

                $fullname = $order->client->last_name . $order->client->first_name. $order->client->middle_name;

                if(!$tour->route->is_transfer){
                    if ($order->tour->rent) {
                        $address = $order->tour->rent->address;
                        $addressTo = $order->tour->rent->address_to;
                    } else {
                        $address = $order->stationFrom->name;
                        $addressTo = $order->stationTo->name;
                    }
                } else {
                     $address = $order->addressFrom;
                     $addressTo = $order->addressTo;
                }

            @endphp
            <tr class="c17">
                <td class="c28" colspan="1" rowspan="1"><p class="c1"><span class="c0">{{$key + 1}}</span></p></td>
                <td class="c25" colspan="1" rowspan="1"><p class="c1"><span class="c0">{{$order->id}}</span></p></td>
                <td class="c11" colspan="1" rowspan="1"><p class="c5"><span class="c0">{{$order->client->last_name}}</span></p></td>
                <td class="c3" colspan="1" rowspan="1"><p class="c4"><span class="c0"></span></p></td>
                <td class="c28" colspan="1" rowspan="1"><p class="c1"><span class="c0">{{$order->client->phone}}</span></p></td>
                <td class="c9" colspan="1" rowspan="1"><p class="c5"><span class="c0">{{$address}}</span></p></td>
                <td class="c9" colspan="1" rowspan="1"><p class="c5"><span class="c0">{{ $order->station_from_time }}</span></p></td>
                <td class="c9" colspan="1" rowspan="1"><p class="c5"><span class="c0">{{$addressTo}}</span></p></td>
                <td class="c30" colspan="1" rowspan="1"><p class="c4"><span class="c0">{{$order->client->doc_number}}</span></p></td>
                <td class="c23" colspan="1" rowspan="1"><p class="c5"><span class="c0">{{$order->comment}}</span></p></td>
                <td class="c3" colspan="1" rowspan="1"><p class="c1"><span class="c0">{{(int)$order->price}}</span></p></td>
                <td class="c3" colspan="1" rowspan="1"><p class="c1"><span class="c0">{{$order->type_pay == 'success' ? (int)$order->price : 0}}</span></p></td>
                <td class="c24" colspan="1" rowspan="1">
                    <p class="c5"><span class="c0">
                    @if (env('ALT_PAYMENT'))
                        {{ $order->type_pay_alt ? trans('admin.orders.alt_pay_types.' . $order->type_pay_alt) : trans('admin.orders.pay_types.'.$order->type_pay) }}
                    @else
                        {{ $order->type_pay ? trans('admin.orders.pay_types.'.$order->type_pay) : '' }}
                    @endif
                    </span></p>
                </td>
                <td class="c3" colspan="1" rowspan="1"><p class="c4"><span class="c0"></span></p></td>
            </tr>
        @endforeach
    @endif

    <tr class="c16">
        <td class="c13" colspan="1" rowspan="1"><p class="c4"><span class="c0"></span></p></td>
        <td class="c53" colspan="4" rowspan="4">
            <p class=""><span class="c0">{{trans('print_document.charterer')}}: _________________________________________ </span></p>
            <p style="margin-bottom: 50px"></p>
            <p class="c5"><span class="c0">_________________________</span></p>
            <p class="c5"><span class="c0">{{trans('print_document.signature')}}</span></p>
        </td>
        <td class="c7" colspan="1" rowspan="1"><p class="c4"><span class="c0"></span></p></td>
        <td class="c32" colspan="6" rowspan="4">
            <p class=""><span class="c0">{{trans('print_document.freighter')}}: _________________________________________ </span></p>
            <p style="margin-bottom: 50px"></p>
            <p class="c5"><span class="c0">_________________________</span></p>
            <p class="c5"><span class="c0">{{trans('print_document.signature')}}</span>
        </td>
    </tr>
    <tr class="c16">
        <td class="c13" colspan="1" rowspan="1"><p class="c4"><span class="c0"></span></p></td>
        <td class="c7" colspan="1" rowspan="1"><p class="c4"><span class="c0"></span></p></td>
    </tr>
    <tr class="c16">
        <td class="c13" colspan="1" rowspan="1"><p class="c4"><span class="c0"></span></p></td>
        <td class="c7" colspan="1" rowspan="1"><p class="c4"><span class="c0"></span></p></td>
    </tr>
    <tr class="c16">
        <td class="c13" colspan="1" rowspan="1"><p class="c4"><span class="c0"></span></p></td>
        <td class="c7" colspan="1" rowspan="1"><p class="c4"><span class="c0"></span></p></td>
    </tr>
    </tbody>
</table>
</body>

<style>
    ol {
        margin: 0;
        padding: 0
    }

    table td, table th {
        padding: 0
    }

    .c25 {
        border-right-style: solid;
        padding: 0 2pt 0 2pt;
        border-bottom-color: #000;
        border-top-width: 1pt;
        border-right-width: 1pt;
        border-left-color: #000;
        vertical-align: bottom;
        border-right-color: #000;
        border-left-width: 1pt;
        border-top-style: solid;
        border-left-style: solid;
        border-bottom-width: 1pt;
        border-top-color: #000;
        border-bottom-style: solid
    }

    .c68 {
        border-right-style: solid;
        padding: 0 2pt 0 2pt;
        border-bottom-color: #000;
        border-top-width: 1pt;
        border-right-width: 1pt;
        border-left-color: #fff;
        vertical-align: bottom;
        border-right-color: #fff;
        border-left-width: 1pt;
        border-top-style: solid;
        border-left-style: solid;
        border-bottom-width: 1pt;
        border-top-color: #fff;
        border-bottom-style: solid
    }

    .c66 {
        border-right-style: solid;
        padding: 0 2pt 0 2pt;
        border-bottom-color: #000;
        border-top-width: 1pt;
        border-right-width: 1pt;
        border-left-color: #fff;
        vertical-align: bottom;
        border-right-color: #fff;
        border-left-width: 1pt;
        border-top-style: solid;
        border-left-style: solid;
        border-bottom-width: 1pt;
        border-top-color: #fff;
        border-bottom-style: solid
    }

    .c61 {
        border-right-style: solid;
        padding: 0 2pt 0 2pt;
        border-bottom-color: #000;
        border-top-width: 1pt;
        border-right-width: 1pt;
        border-left-color: #fff;
        vertical-align: bottom;
        border-right-color: #fff;
        border-left-width: 1pt;
        border-top-style: solid;
        border-left-style: solid;
        border-bottom-width: 1pt;
        border-top-color: #fff;
        border-bottom-style: solid
    }

    .c37 {
        border-right-style: solid;
        padding: 0 2pt 0 2pt;
        border-bottom-color: #000;
        border-top-width: 1pt;
        border-right-width: 1pt;
        border-left-color: #fff;
        vertical-align: bottom;
        border-right-color: #fff;
        border-left-width: 1pt;
        border-top-style: solid;
        border-left-style: solid;
        border-bottom-width: 1pt;
        border-top-color: #fff;
        border-bottom-style: solid
    }

    .c57 {
        border-right-style: solid;
        padding: 0 2pt 0 2pt;
        border-bottom-color: #000;
        border-top-width: 1pt;
        border-right-width: 1pt;
        border-left-color: #fff;
        vertical-align: bottom;
        border-right-color: #fff;
        border-left-width: 1pt;
        border-top-style: solid;
        border-left-style: solid;
        border-bottom-width: 1pt;
        border-top-color: #fff;
        border-bottom-style: solid
    }

    .c56 {
        border-right-style: solid;
        padding: 0 2pt 0 2pt;
        border-bottom-color: #000;
        border-top-width: 1pt;
        border-right-width: 1pt;
        border-left-color: #fff;
        vertical-align: bottom;
        border-right-color: #fff;
        border-left-width: 1pt;
        border-top-style: solid;
        border-left-style: solid;
        border-bottom-width: 1pt;
        border-top-color: #fff;
        border-bottom-style: solid
    }

    .c11 {
        border-right-style: solid;
        padding: 0 2pt 0 2pt;
        border-bottom-color: #000;
        border-top-width: 1pt;
        border-right-width: 1pt;
        border-left-color: #000;
        vertical-align: bottom;
        border-right-color: #000;
        border-left-width: 1pt;
        border-top-style: solid;
        border-left-style: solid;
        border-bottom-width: 1pt;
        border-top-color: #000;
        border-bottom-style: solid
    }

    .c53 {
        border-right-style: solid;
        padding: 0 2pt 0 2pt;
        border-bottom-color: #fff;
        border-top-width: 1pt;
        border-right-width: 1pt;
        border-left-color: #fff;
        vertical-align: bottom;
        border-right-color: #fff;
        border-left-width: 1pt;
        border-top-style: solid;
        border-left-style: solid;
        border-bottom-width: 1pt;
        border-top-color: #fff;
        border-bottom-style: solid
    }

    .c3 {
        border-right-style: solid;
        padding: 0 2pt 0 2pt;
        border-bottom-color: #000;
        border-top-width: 1pt;
        border-right-width: 1pt;
        border-left-color: #000;
        vertical-align: bottom;
        border-right-color: #000;
        border-left-width: 1pt;
        border-top-style: solid;
        border-left-style: solid;
        border-bottom-width: 1pt;
        border-top-color: #000;
        border-bottom-style: solid
    }

    .c9 {
        border-right-style: solid;
        padding: 0 2pt 0 2pt;
        border-bottom-color: #000;
        border-top-width: 1pt;
        border-right-width: 1pt;
        border-left-color: #000;
        vertical-align: bottom;
        border-right-color: #000;
        border-left-width: 1pt;
        border-top-style: solid;
        border-left-style: solid;
        border-bottom-width: 1pt;
        border-top-color: #000;
        border-bottom-style: solid
    }

    .c32 {
        border-right-style: solid;
        padding: 0 2pt 0 2pt;
        border-bottom-color: #fff;
        border-top-width: 1pt;
        border-right-width: 1pt;
        border-left-color: #fff;
        vertical-align: bottom;
        border-right-color: #fff;
        border-left-width: 1pt;
        border-top-style: solid;
        border-left-style: solid;
        border-bottom-width: 1pt;
        border-top-color: #fff;
        border-bottom-style: solid
    }

    .c13 {
        border-right-style: solid;
        padding: 0 2pt 0 2pt;
        border-bottom-color: #fff;
        border-top-width: 1pt;
        border-right-width: 1pt;
        border-left-color: #fff;
        vertical-align: bottom;
        border-right-color: #fff;
        border-left-width: 1pt;
        border-top-style: solid;
        border-left-style: solid;
        border-bottom-width: 1pt;
        border-top-color: #fff;
        border-bottom-style: solid
    }

    .c15 {
        border-right-style: solid;
        padding: 0 2pt 0 2pt;
        border-bottom-color: #000;
        border-top-width: 1pt;
        border-right-width: 1pt;
        border-left-color: #fff;
        vertical-align: bottom;
        border-right-color: #fff;
        border-left-width: 1pt;
        border-top-style: solid;
        border-left-style: solid;
        border-bottom-width: 1pt;
        border-top-color: #fff;
        border-bottom-style: solid
    }

    .c30 {
        border-right-style: solid;
        padding: 0 2pt 0 2pt;
        border-bottom-color: #000;
        border-top-width: 1pt;
        border-right-width: 1pt;
        border-left-color: #000;
        vertical-align: bottom;
        border-right-color: #000;
        border-left-width: 1pt;
        border-top-style: solid;
        border-left-style: solid;
        border-bottom-width: 1pt;
        border-top-color: #000;
        border-bottom-style: solid
    }

    .c23 {
        border-right-style: solid;
        padding: 0 2pt 0 2pt;
        border-bottom-color: #000;
        border-top-width: 1pt;
        border-right-width: 1pt;
        border-left-color: #000;
        vertical-align: bottom;
        border-right-color: #000;
        border-left-width: 1pt;
        border-top-style: solid;
        border-left-style: solid;
        border-bottom-width: 1pt;
        border-top-color: #000;
        border-bottom-style: solid
    }

    .c19 {
        border-right-style: solid;
        padding: 0 2pt 0 2pt;
        border-bottom-color: #000;
        border-top-width: 1pt;
        border-right-width: 1pt;
        border-left-color: #fff;
        vertical-align: bottom;
        border-right-color: #fff;
        border-left-width: 1pt;
        border-top-style: solid;
        border-left-style: solid;
        border-bottom-width: 1pt;
        border-top-color: #fff;
        border-bottom-style: solid
    }

    .c71 {
        border-right-style: solid;
        padding: 0 2pt 0 2pt;
        border-bottom-color: #fff;
        border-top-width: 1pt;
        border-right-width: 1pt;
        border-left-color: #fff;
        vertical-align: top;
        border-right-color: #fff;
        border-left-width: 1pt;
        border-top-style: solid;
        border-left-style: solid;
        border-bottom-width: 1pt;
        border-top-color: #fff;
        border-bottom-style: solid
    }

    .c24 {
        border-right-style: solid;
        padding: 0 2pt 0 2pt;
        border-bottom-color: #000;
        border-top-width: 1pt;
        border-right-width: 1pt;
        border-left-color: #000;
        vertical-align: bottom;
        border-right-color: #000;
        border-left-width: 1pt;
        border-top-style: solid;
        border-left-style: solid;
        border-bottom-width: 1pt;
        border-top-color: #000;
        border-bottom-style: solid
    }

    .c43 {
        border-right-style: solid;
        padding: 0 2pt 0 2pt;
        border-bottom-color: #fff;
        border-top-width: 1pt;
        border-right-width: 1pt;
        border-left-color: #fff;
        vertical-align: top;
        border-right-color: #fff;
        border-left-width: 1pt;
        border-top-style: solid;
        border-left-style: solid;
        border-bottom-width: 1pt;
        border-top-color: #fff;
        border-bottom-style: solid
    }

    .c28 {
        border-right-style: solid;
        padding: 0 2pt 0 2pt;
        border-bottom-color: #000;
        border-top-width: 1pt;
        border-right-width: 1pt;
        border-left-color: #000;
        vertical-align: bottom;
        border-right-color: #000;
        border-left-width: 1pt;
        border-top-style: solid;
        border-left-style: solid;
        border-bottom-width: 1pt;
        border-top-color: #000;
        border-bottom-style: solid
    }

    .c7 {
        border-right-style: solid;
        padding: 0 2pt 0 2pt;
        border-bottom-color: #fff;
        border-top-width: 1pt;
        border-right-width: 1pt;
        border-left-color: #fff;
        vertical-align: bottom;
        border-right-color: #fff;
        border-left-width: 1pt;
        border-top-style: solid;
        border-left-style: solid;
        border-bottom-width: 1pt;
        border-top-color: #fff;
        border-bottom-style: solid
    }

    .c0 {
        color: #000;
        font-weight: 400;
        text-decoration: none;
        vertical-align: baseline;
        font-size: 12pt;
        font-family: Calibri;
        font-style: normal
    }

    .c4 {
        padding-top: 0;
        padding-bottom: 0;
        line-height: 1.15;
        text-align: left;
        height: 11pt
    }

    .c69 {
        border-spacing: 0;
        border-collapse: collapse;
        margin-right: auto
    }

    .c5 {
        padding-top: 0;
        padding-bottom: 0;
        line-height: 1.15;
        text-align: center
    }

    .c1 {
        padding-top: 0;
        padding-bottom: 0;
        line-height: 1.15;
        text-align: right
    }

    .c54 {
        padding: 30px
    }

    .c17 {
        height: 15pt
    }

    .c16 {
        height: 15.8pt
    }

    li {
        color: #000;
        font-size: 11pt;
        font-family: Arial
    }

    p {
        margin: 0;
        color: #000;
        font-size: 11pt;
        font-family: Arial
    }

    h1 {
        padding-top: 20pt;
        color: #000;
        font-size: 20pt;
        padding-bottom: 6pt;
        font-family: Arial;
        line-height: 1.15;
        page-break-after: avoid;
        orphans: 2;
        widows: 2;
        text-align: left
    }

    h2 {
        padding-top: 18pt;
        color: #000;
        font-size: 16pt;
        padding-bottom: 6pt;
        font-family: Arial;
        line-height: 1.15;
        page-break-after: avoid;
        orphans: 2;
        widows: 2;
        text-align: left
    }

    h3 {
        padding-top: 16pt;
        color: #434343;
        font-size: 14pt;
        padding-bottom: 4pt;
        font-family: Arial;
        line-height: 1.15;
        page-break-after: avoid;
        orphans: 2;
        widows: 2;
        text-align: left
    }

    h4 {
        padding-top: 14pt;
        color: #666;
        font-size: 12pt;
        padding-bottom: 4pt;
        font-family: Arial;
        line-height: 1.15;
        page-break-after: avoid;
        orphans: 2;
        widows: 2;
        text-align: left
    }

    h5 {
        padding-top: 12pt;
        color: #666;
        font-size: 11pt;
        padding-bottom: 4pt;
        font-family: Arial;
        line-height: 1.15;
        page-break-after: avoid;
        orphans: 2;
        widows: 2;
        text-align: left
    }

    h6 {
        padding-top: 12pt;
        color: #666;
        font-size: 11pt;
        padding-bottom: 4pt;
        font-family: Arial;
        line-height: 1.15;
        page-break-after: avoid;
        font-style: italic;
        orphans: 2;
        widows: 2;
        text-align: left
    }
</style>

</html>
