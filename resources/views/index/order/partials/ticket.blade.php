<div class="ticket ticketWidth" style="padding: 20px;">
    @if($setting->mainImage)
        <img id="logo-result" style="width: 200px; position: absolute; padding-left: 25%" src="{{ $setting->mainImage->load() }}"><br>
    @endif
    <b>{{ trans('index.order.order_number') }}</b> {{ $order->slug }} <br>

    <h3 style="margin-top: 20px;">{{ trans('index.schedules.departure') }}</h3>
    @if($order->tour->route->is_transfer)
        @if($order->tour->route->flight_type == 'departure')
            {{ $order->stationFrom->city->name }}, {{ $order->addressFrom }}<br>
        @else
            {{ $order->stationFrom->city->name }}, {{ $order->stationFrom->name }}<br>
        @endif
        {{ \App\Services\Prettifier::prettifyDateTimeFull($order->from_date_time) }} {{ $order->stationFrom->city->FullTimezone }}<br>
    @else
        @if (empty($order->addressFrom))
            <b>{{ trans('index.order.city') }}:</b> {{ $order->stationFrom->city->name }}<br>
            <b>{{ trans('admin_labels.street_id') }}:</b> {{ $order->stationFrom->street->name }}<br>
            <b>{{ trans('index.order.station') }}:</b> {{ $order->stationFrom->name }}<br>
        @else
            <b>{{ trans('index.partials.address') }}:</b> {{ $order->addressFrom }}<br>
        @endif    
        <b>{{ trans('index.home.time') }}:</b> {{ \App\Services\Prettifier::prettifyDateTimeFull($order->from_date_time) }} {{ $order->stationFrom->city->FullTimezone }}<br>
    @endif

    @if ($order->tour->reservation_by_place)
        <b>{{ trans('index.order.seats') }}:</b> {{ $order->orderPlaces->implode('number', ', ') }}
    @else <b>{{ trans('index.order.seats_quantity') }}: </b> {{ count($order->orderPlaces) }}
    @endif


    <h3 style="margin-top: 20px;">{{ trans('index.schedules.arrival') }}</h3>
    @if($order->tour->route->is_transfer)
        @if($order->tour->route->flight_type == 'departure')
            {{ $order->stationTo->city->name }}<br>
        @else
            {{ $order->stationTo->city->name }}, {{ $order->addressTo }}<br>
        @endif
        {{ \App\Services\Prettifier::prettifyDateTimeFull($order->to_date_time) }} {{ $order->stationTo->city->FullTimezone }}<br>
    @else
        @if (empty($order->addressTo))
            <b>{{ trans('index.order.city') }}:</b> {{ $order->stationTo->city->name }}<br>
            <b>{{ trans('admin_labels.street_id') }}:</b> {{ $order->stationTo->street->name }}<br>
            <b>{{ trans('index.order.station') }}:</b> {{ $order->stationTo->name }}<br>
        @else
            <b>{{ trans('index.partials.address') }}:</b> {{ $order->addressTo }}<br>
        @endif
        <b>{{ trans('index.home.time') }}:</b> {{ \App\Services\Prettifier::prettifyDateTimeFull($order->to_date_time) }} {{ $order->stationTo->city->FullTimezone }}<br>
    @endif

    @if($order->addServices->count())
        
        @if(!isset($old_price))
            @php($old_price = 0)
            @php($arr_price = [])
            @foreach($order->orderPlaces as $op)
                @php(array_push($arr_price, $op->price))
                {{$old_price}}
            @endforeach
            @php($old_price = array_sum($arr_price))
        @endif

        <b>{{ trans('print_ticket.old.price') }} </b> {{$old_price}} {{ trans('admin_labels.currencies_short.'.$order->tour->route->currency->alfa) }}<br>
        <b>Доп. сервисы:</b><br>
        @foreach ($order->addServices as $item)
            <b>{{ $item->name }}</b> {{ $item->value*$item->pivot->quantity.' '.trans('admin_labels.currencies_short.'.$order->tour->route->currency->alfa) }}
            ({{ trans('admin_labels.for').' '.$item->pivot->quantity.' '.trans('admin_labels.units') }})<br>
        @endforeach
    @endif
    <b>{{ trans('admin_labels.total_sum') }}:</b> {{$order->price}} {{ trans('admin_labels.currencies_short.'.$order->tour->route->currency->alfa) }}<br>
    @if (env('PARTIAL_PREPAID') && $order->tour->route->partial_prepaid)
            <b>{{ trans('index.order.prepaid') }} </b>{{ $order->calcPrepaid() }} {{ trans('admin_labels.currencies_short.'.$order->tour->route->currency->alfa) }}<br>
    @endif
    
    @if ((env('PRINT') && !\App\Models\Setting::first()->is_pay_on) || (env('PRINT') && $order->StatusPay == \App\Models\Order::TYPE_PAY_SUCCESS) 
        || (env('PRINT')  && \App\Models\Setting::first()->anyway_download_tickets))
        <br>
        <a href="{{route('index.profile.generatePDF', ["id"=>$order->id])}}">
            <p class="title">
                <button style="text-align: center;" class="confirmReservations">{{ trans('index.partials.download_ticket') }}</button>
            </p>
        </a>
    @endif
    @if(env('EKAM') == true && (\App\Models\Setting::first()->is_pay_on && $order->type_pay == \App\Models\Order::TYPE_PAY_SUCCESS))
        <br>
        <a href="{{route('index.order.get_check', $order)}}">
            <button class="confirmReservations">
                Чек
            </button>
        </a>
        <br>
    @endif
    <br>
    <div>
        @if(auth()->user()->client->email && env("EMAIL")==true)
            <button class="confirmReservations">
                <a class="js_send-to-email" href="javascript:;" data-order-id="{{$order->id}}">{{ trans('index.profile.to_email') }}</a>
            </button>
        @else
            <br /><br />
        {{-- <div class="small_warning"><a href="{{ route('index.profile.settings.index') }}">{{ trans('index.profile.warning_email_sent')}}</a></div> --}}
        @endif
    </div>
</div>