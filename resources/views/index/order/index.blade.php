@extends('index.layouts.main')
@php($currency = trans('admin_labels.currencies_short.'.$order->tour->route->currency->alfa))
@section('title', trans('index.order.title'))

@section('content')
    <div class="item-page mainWidth">
        <ul class="breadCrumbs backg" style="width: 95%;">
            <li><a href="{{ route('index.home') }}">{{trans('index.home.title')}}</a></li>
            {{--<li><a href="{{ $order->urlSchedules }}">{{trans('index.home.booking')}}</a></li>--}}
            <li><a class="thisPage">{{trans('index.order.order_registration')}}</a></li>
        </ul>
        <br>
        <div class="mainOrderBlock backg">
            <div class="left">
                @include('index.order.partials.left_tour_info', ['order' => $order, 'hideCountdown' => $orderReturn !== false, 'isReturn' => 0])
                @if ($orderReturn)
                    @include('index.order.partials.left_tour_info', [ 'order' => $orderReturn, 'dirText' => '(обратно)', 'stationsFrom' => $stationsFromReturn, 'stationsTo' => $stationsToReturn,'isReturn' => 1])
                @endif
            </div>
            <div class="right">
                @if(!auth()->user() || !auth()->user()->client_id)
                    @php($code = \App\Models\Client::CODE_PHONES[$order->tour->route->phone_code])
                    <div class="unregisteredUserForm">
                        @if(isset($order->tour->route->required_inputs) &&
                        $order->tour->route->required_inputs != '')
                            @foreach(explode(',', $order->tour->route->required_inputs) as $field)
                                @if ($field == 'phone')
                                    <div class="left field">
                                        <label for="unregUserPhone"
                                               class="mandatory">{{trans('admin_labels.'.$field)}}</label>
                                        <div class="row">
                                            <div style="position: absolute">
                                                @php($phone_codes = [])
                                                @php($phoneCodes = \App\Models\Client::CODE_PHONES)
                                                @foreach($phoneCodes as $key => $phoneCode)
                                                    @if(in_array($key, explode(",", $codes)))
                                                        @php($phone_codes[$key] = '+'.$phoneCode)
                                                    @endif
                                                @endforeach
                                                {!! Form::select('phone-code', $phone_codes , $order->tour->route->phone_code, [
                                                'style' => 'padding: 10% 3.5%; transition: border-color .15s ease-in-out 0s,box-shadow .15s ease-in-out 0s;
                                                 font-size: 15px;', 'id' => 'country-codes']) !!}
                                            </div>
                                            <div  style="padding-left: 25%">
                                                {!! Form::tel($field, '', ['class' => 'js_mask-phone field-phone', 'id' => 'unregUserPhone', 'placeholder' => '']) !!}
                                            </div>
                                        </div>

                                    </div>
                                @elseif($field == 'birth_day')
                                    <div class="left field">
                                        <label for="unregUserBirthDay"
                                               class="mandatory">{{trans('admin_labels.'.$field)}}</label>
                                        {!! Form::text($field, '', ['class' => 'js_datepicker', 'id' => 'unregUserBirthDay', 'placeholder' => trans('index.profile.birth_date'), 'autocomplete' => 'off']) !!}
                                    </div>
                                @elseif($field == 'gender')
                                    <div class="left field">
                                        {!! Form::panelSelect($field, trans('admin_labels.genders'), null, ['class' => 'select_place_from'], false) !!}
                                    </div>
                                @elseif($field == 'country_id')
                                    <div class="left field">
                                        {!! Form::panelSelect($field, trans('admin_labels.countries'), null, ['class' => 'select_place_from mandatory'], false) !!}
                                    </div>
                                @elseif($field == 'doc_type')
                                    <div class="left field">
                                        {!! Form::panelSelect($field, trans('admin_labels.doc_types'), null, ['class' => 'select_place_from'], false) !!}
                                    </div>
                                @elseif($field == 'doc_number')
                                    <div class="left field">
                                        <label for="{{ $field }}" class="mandatory">{{trans('admin_labels.'.$field)}}</label>
                                        {!! Form::text($field, null, ['id' => $field]) !!}
                                    </div>
                                @else
                                    <div class="left field">
                                        <label for="{{ $field }}" class="mandatory">{{trans('admin_labels.'.$field)}}</label>
                                        {!! Form::text($field, session('order.'.$field), ['id' => $field]) !!}
                                    </div>
                                @endif
                            @endforeach
                        @else
                            @foreach(array('first_name', 'phone') as $field)
                                @if ($field == 'phone')
                                    <div class="left field">
                                        <label for="unregUserPhone" class="mandatory">{{trans('index.order.your_phone')}}</label>
                                        {!! Form::text($field, $code, ['class' => 'js_mask-phone', 'id' => 'unregUserPhone', 'placeholder' => '']) !!}
                                    </div>
                                @else
                                    <div class="left field">
                                        <label for="unregUserName"
                                               class="mandatory">{{trans('admin_labels.'.$field)}}</label>
                                        {!! Form::text($field, null, ['id' => 'unregUserName']) !!}
                                    </div>
                                @endif
                            @endforeach
                        @endif
                        <br style="clear: both"/>
                    </div>
                @endif
                @if ($order->count_places > 1 && $discount_children > 0)
                    {{trans('admin.orders.num_of_children')}}:
                    {!! Form::selectRange('count_places', 0, $order->count_places - 1, 0,
                            ['class' => 'js_orders-count_places_child', 'data-url'=> route('index.order.children')]) !!}
                @endif
                <div class="mainTitle">

                </div>
                <div class="installmentForm">
                    @if(\App\Models\Coupon::active($order->tour)->count())
                     <div style="display: none" class="numberOfTheFreeTripsWrapp inputWrapper">
                         <input type="text" id="numberOfTheFreeTrips" placeholder="введите кол-во бесплатных поездок">
                         <a class="enterButton">Ок</a>
                     </div>
                     <div class="enterThePromotionalCodeWrapp inputWrapper">
                         <input type="text" class="js_form-coupon-code" id="enterThePromotionalCode"
                                placeholder="ввести промокод">
                         <span class="enterButton js_form-coupon-btn"
                               data-url="{{ route('index.order.coupon') }}">Ок</span>
                     </div>
                    @endif
                </div>
                <div class="js_order-prices direct-tickets">
                    @include('index.order.order.prices', ['order' => $order])
                </div>
                @if ($orderReturn)
                    <h3>Обратные билеты</h3>
                    <div class="js_order-prices return-tickets">
                        @include('index.order.order.prices', ['order' => $orderReturn])
                    </div>
                @endif
                @if (App\Models\Setting::first()->payment == 'both')
                    <div class="inTotal payment-method">
                        <p class="left payment-method__title">{{ trans('admin_labels.payment')}}:</p>
                        <p class="left payment-method__item" style="padding-left: 15px">
                            <input class="form-control" id="pay-online" type="radio" name="payment" checked="checked" value="{{ \App\Models\Order::TYPE_PAY_WAIT}}"/>
                            <label for="pay-online" style="padding-right: 50px"> Оплата картой онлайн</label>
                        </p>
                        <p class="left payment-method__item">
                            <input class="form-control" id="pay-cash" type="radio" name="payment" value="{{ \App\Models\Order::TYPE_PAY_CASH_PAYMENT }}"/>
                            <label for="pay-cash"> Наличными водителю</label>
                        </p>
                        <br style="clear: both"/>
                    </div>
                    <br />
                @endif
                <div class="inTotal">
                    <p class="left">{{ trans('admin.orders.total_ticket')}}:</p>
                    <p class="right">
                        <span id="total-ticket">{{ number_format($order->totalPrice + ($orderReturn ? $orderReturn->totalPrice : 0), 2, '.', ' ') }}</span>
                        <span class="ruble"> {{ $currency }}</span>
                    </p>
                    <br style="clear: both"/>
                </div>
                <br />

                @if ($order->tour->route->addServices->count())
                    <div id="add-services-block" class="d-none">
                        <div class="inTotal">
                            <p class="left">{{ trans('admin.orders.add_services_total') }}:</p>
                            <p class="right"><span id="add-service-total"></span> {{ $currency }}</p>
                        </div>
                        <br style="clear: both"/>
                        <div class="inTotal">
                            <p class="left">{{ trans('admin.orders.total_sum') }}:</p>
                            <p class="right"><span id="total-full"></span> {{ $currency }}</p>
                        </div>
                        <br style="clear: both"/>
                    </div>
                    <div class="unregisteredUserForm">
                        <p class="title">{{ trans('index.order.add_services') }}:</p>
                        @foreach ($order->tour->route->addServices as $item)
                            <div class="left field additional-item">
                                <label for="serv-{{ $item->id }}">{{ $item->name }} - <strong><span id="dop-cost-{{ $item->id }}">{{ $item->value }}</span> {{ $currency }}</strong></label>
                                {!! Form::number('add_services['.$item->id.']', 0,
                                    ['id' => 'serv-'.$item->id, 'min' => 0, 'max' =>  $order->tour->bus->places,
                                    'class' => 'js-dop-number', 'data-id' => $item->id,
                                    'inputmode' => 'numeric', 'pattern' => '[0-9]*', 'integral', 'number'] ) !!}
                            </div>
                        @endforeach
                    </div>
                    <br style="clear: both"/>
                    <br style="clear: both"/>
                @endif

                @if(!auth()->user() || !auth()->user()->client_id)
                    <div style="padding-top: 1%; padding-bottom: 2%; text-align: left;">
                        <label class="check-container">{{trans('index.order.consent_to_the_processing')}}
                            <input name="agree_personal_data" type="checkbox">
                            <span class="checkmark"></span>
                        </label>
                        @if(\App\Models\Page::where('slug', 'usloviya-polzovatelskogo-soglasheniya')->first())
                            <a style="color: #c4c7ff; margin-left: 20px; padding-left: 15px;" target="_blank" href="/usloviya-polzovatelskogo-soglasheniya">
                                {{trans('index.order.go_to_agreement')}}
                            </a>
                        @endif
                    </div>
                @endif

                <a class="confirmReservations js_btn js_form-order-btn">{{trans('index.schedules.continue')}}</a>

                @if(auth()->user() && \App\Models\Setting::first()->is_pay_on && auth()->user()->client_id && !$order->tour->route->is_international)
                    <!--<span class="confirmReservations js_btn js_form-order-pay-btn" style="margin-top: 10px">{{trans('index.order.pay')}}</span>-->
                @endif
                <div class="row">
                    <br>
                    <div class="left">
                        <label for="unregUserName"
                               class="mandatory">{{trans('admin_labels.comment')}}</label>
                        {!! Form::textarea('comment', null, ['id' => 'unregUserName', 'style' => 'width: 100%; height: 120px; box-sizing: border-box; line-height: 20px; padding-left: 5px;']) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <br style="clear: both"/>
        </div>
    </div>
    <style>
        /* The check-container */
        .check-container {
            display: block;
            position: relative;
            padding-left: 35px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 22px;
            -webkit-user-select: text;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media screen and (max-width: 767px) {
            .check-container {
                padding-right: 35px;
                padding-left: 0;
            }
        }

        /* Hide the browser's default checkbox */
        .check-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        /* Create a custom checkbox */
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 25px;
            width: 25px;
            background-color: #c8c9c8;
        }

        @media screen and (max-width: 767px) {
            .checkmark {
                left: auto;
                right: 0;
                top: 50%;
                transform: translateY(-50%);
            }
        }

        /* On mouse-over, add a grey background color */
        .check-container:hover input ~ .checkmark {
            background-color: #ccc;
        }

        /* When the checkbox is checked, add a blue background */
        .check-container input:checked ~ .checkmark {
            background-color: #44b759;
        }

        /* Create the checkmark/indicator (hidden when not checked) */
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the checkmark when checked */
        .check-container input:checked ~ .checkmark:after {
            display: block;
        }

        /* Style the checkmark/indicator */
        .check-container .checkmark:after {
            left: 9px;
            top: 5px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }
    </style>
@endsection
@push('scripts')
    <script src="{{ asset('assets/index/js/markup/input_validation.js') }}"></script>
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
                    $('.js_form-order-btn').prop("disabled", true);
                    $('.js_form-order-btn').click(function () {
                        toastr.error('{{trans("messages.index.order.expired")}}');
                    });
                }

                // Days left = time left / Seconds per Day
                var days = Math.floor(timeLeft / 86400);
                // 86400 seconds per Day
                timeLeft -= days * 86400;
                // Hours left = time left / Seconds per Hour
                var hours = Math.floor(timeLeft / 3600) % 24;
                // 3600 seconds per Hour
                timeLeft -= hours * 3600;
                // Minutes left = time left / Minutes per Hour
                var min = Math.floor(timeLeft / 60) % 60;
                // 60 seconds per minute
                timeLeft -= min * 60;
                // Seconds Left
                var sec = timeLeft % 60;

                // Combined DAYS+HOURS+MIN+SEC

                if (isPast) {
                    min = 0;
                    sec = 0;
                }
                var timeString = "{{trans('index.order.time_left_for_booking')}}:<br /> <span style='font-weight:normal;' class='minutes'>  " + min + " {{trans('index.schedules.min')}} " + "</span>" +
                    "<span style='font-weight:normal;' class='seconds'>" + sec + " {{trans('index.schedules.sec')}} " + "</span>";
                elem.html(timeString);

            }, 1000);
        }
        let limitTime = '{{ $setting->limit_booking_time }}';
        if (limitTime > 0) {
            countDownTimer('{!! $order->updated_at->setTimezone("UTC")->addMinutes($setting->limit_booking_time)->format("j/F/Y H:i:s") !!}');
        }

        $('.js-dop-number').on('change', function () {
            let dopId = $(this).data('id');
            let dopTotal = 0;
            $('.js-dop-number').each(function () {
                let dopId = $(this).data('id');
                dopTotal += $(this).val()*$('#dop-cost-'+dopId).text();
            });

            if (dopTotal > 0) {
                $('#add-service-total').text(dopTotal.toFixed(2));
                $('#total-full').text((dopTotal + parseFloat($('#total-ticket').text().replace(/\s+/g, ''))).toFixed(2));
                $('#add-services-block').slideDown();
            } else {
                $('#add-services-block').slideUp();
            }
        });

    </script>
@endpush

