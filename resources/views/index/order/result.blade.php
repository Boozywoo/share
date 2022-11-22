@extends('index.layouts.main')

@section('title', trans('index.order.ticket_success'))

@section('content')
<div class="backg">
    <div class="mainWidth ticketMainBlock">
        {{--<ul class="breadCrumbs">--}}
        {{--<li><a href="{{ route('index.home') }}">{{trans('index.home.title')}}</a></li>--}}
        {{--<li><a href="{{ $order->urlSchedules }}">{{trans('index.home.booking')}}</a></li>--}}
        {{--<li><a href="{{ route('index.order.index') }}">{{trans('index.order.order_registration')}}</a></li>--}}
        {{--<li><a class="thisPage">{{trans('index.order.ticket_success')}}</a></li>--}}
        {{--</ul>--}}

    @if($order->status == \App\Models\Order::STATUS_DISABLE)
        <p style="margin-bottom: 10%; color: #bf800c" class="title"><strong>{{trans('index.order.canceled')}}!</strong></p><br /><br />
    @else
        @if ($order->pay_id)
            @php($statusPay = $order->StatusPay)
            @if($statusPay == \App\Models\Order::TYPE_PAY_SUCCESS)
                <p style="padding-bottom: 1%; color: #00cc99" class="title"><strong>{{trans('index.order.payment_accepted')}}</strong>
                @if(env('EKAM') == true)
                    <br>
                    <a href="{{route('index.order.get_check', $order)}}">
                        <button class="confirmReservations">
                            Чек
                        </button>
                    </a>
                    <br>
                @endif
            @elseif($statusPay == \App\Models\Order::TYPE_PAY_WAIT)
                <p style="padding-bottom: 1%; color: #bf800c" class="title"><strong>{{trans('index.order.wait')}}</strong></p>
            @elseif($statusPay == \App\Models\Order::TYPE_PAY_CANCEL)
                <p style="padding-bottom: 1%; color: #880000" class="title"><strong>{{trans('index.order.error')}}. {{$order->StatusPayDescription}}</strong></p>
            @endif
        @else
            <p style="padding-bottom: 1%;" class="title"><strong>{{trans('index.order.booking_is_confirm')}}<br></strong></p>
            @if (\App\Models\Setting::first()->is_pay_on && session('payment') != \App\Models\Order::TYPE_PAY_CASH_PAYMENT)
                <p class="title">
                    <a href="{{route('index.order.pay')}}">
                        <button class="confirmReservations" @if ((\App\Models\Setting::first()->time_limit_pay ?? 0) && $order->updated_at->lessThan(\Carbon\Carbon::now()->subMinutes(\App\Models\Setting::first()->time_limit_pay))) 
                            style="background-color: lightgrey;" disabled="disabled" @endif>
                            {{trans('index.order.pay')}}
                        </button>
                    </a><br />
                    @if ($partial_prepaid)
                        &nbsp;&nbsp;&nbsp;
                        <a href="{{route('index.order.partial_pay')}}">
                            <button class="confirmReservations" @if ((\App\Models\Setting::first()->time_limit_pay ?? 0) && $order->updated_at->lessThan(\Carbon\Carbon::now()->subMinutes(\App\Models\Setting::first()->time_limit_pay))) disabled="disabled" @endif>
                                {{ trans('index.order.partial_pay').' '.$partial_prepaid.'%' }}
                            </button>
                        </a>
                    @endif
                    <br>
                    @if (\App\Models\Setting::first()->time_limit_pay ?? 0)
                        <span style="text-align: center; margin-bottom: 5px;" id="countDown">
                            {{trans('index.order.online_pay_limit')}}<br>{{trans('index.order.time_left').': '.\App\Models\Setting::first()->time_limit_pay.' '.trans('index.schedules.min').' 0 '.trans('index.schedules.sec')}}
                        </span>
                    @endif
                </p>

            @else
                <a href="{{route('index.home')}}">
                    <p class="title">
                        <span style="text-align: center;" class="confirmReservations">{{trans('index.order.continue')}}</span>
                    </p>
                </a>&nbsp;
                @if($setting->main_site)
                    <a href="{{ $setting->main_site }}">
                        <p class="title">
                            <span style="text-align: center;"
                                class="confirmReservations">{{trans('index.partials.return_to_website')}}</span>
                        </p>
                    </a>&nbsp;
                @endif
                <a href="javascript:;">
                    <p class="title">
                        @if(env('EMAIL')==true)
                        <span style="text-align: center;" class="js_send-to-email confirmReservations"
                              data-order-id="{{$order->id}}">{{trans('index.profile.to_email')}}</span>
                        @endif
                    </p>
                </a>
            @endif
        @endif
        {{--<div class="savePrintBlock ticketWidth">
            <a href="{{ route('index.order.printing', $order) }}" target="_blank"></a>
        </div>--}}
        @include('index.order.partials.ticket')
        @isset($orderSecond)
            @if ($orderSecond->pay_id)
                @php($statusPay = $orderSecond->StatusPay)
                @if($statusPay == \App\Models\Order::TYPE_PAY_SUCCESS)
                    <p style="padding-bottom: 1%; color: #00cc99" class="title"><strong>Платеж за обратный билет успешно принят!</strong>
                    </p>
                @elseif($statusPay == \App\Models\Order::TYPE_PAY_WAIT)
                    <p style="padding-bottom: 1%; color: #bf800c" class="title"><strong>{{trans('index.order.wait')}}</strong></p>
                @elseif($statusPay == \App\Models\Order::TYPE_PAY_CANCEL)
                    <p style="padding-bottom: 1%; color: #880000" class="title"><strong>{{trans('index.order.error')}}: {{$orderSecond->StatusPayDescription}}</strong></p>
                @endif
            @endif
            @include('index.order.partials.ticket', ['order' => $orderSecond])
        @endisset
    </div>
    @endif
    <div id="js_email-popup"></div>
</div>
@endsection
@push('scripts')
    <script>
        function countDownTimer(date) {
            var elem = $('#countDown');
            var futureTime = new Date(date).getTime();
            var isPast = false;
            setInterval(function () {
                // Time left between future and current time in Seconds
                var timeLeft = Math.floor((futureTime - new Date().getTime()) / 1000) - new Date().getTimezoneOffset()*60;

                if (!isPast && timeLeft < 0) {
                    isPast = true;
                    $('.confirmReservations').prop("disabled", true);
                    $('.confirmReservations').css("background-color", 'lightgray');
                    $('.confirmReservations a').css("pointer-events", 'none');
                    $('.confirmReservations, .confirmReservations a').click(function () {
                        toastr.error('{{trans('messages.index.order.expired')}}');
                    });
                }

                var days = Math.floor(timeLeft / 86400);
                timeLeft -= days * 86400;
                var hours = Math.floor(timeLeft / 3600) % 24;
                timeLeft -= hours * 3600;
                var min = Math.floor(timeLeft / 60) % 60;
                timeLeft -= min * 60;
                var sec = timeLeft % 60;

                if (isPast) {
                    min = 0;
                    sec = 0;
                }
                var timeString = "{{trans('index.order.online_pay_limit')}} <br> {{trans('index.order.time_left')}}: <span style='font-weight: bold;' class='minutes'>  " + min + " {{trans('index.schedules.min')}} " + "</span>" +
                    "<span style='font-weight:bold;' class='seconds'>" + sec + " {{trans('index.schedules.sec')}} " + "</span>";
                elem.html(timeString);

            }, 1000);
        }
        @if (\App\Models\Setting::first()->is_pay_on && \App\Models\Setting::first()->time_limit_pay ?? 0)
            countDownTimer('{!! $order->updated_at->setTimezone('UTC')->addMinutes(\App\Models\Setting::first()->time_limit_pay)->format('F j, Y H:i:s') !!}');
        @endif

    </script>
@endpush
