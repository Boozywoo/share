@if ($firstRoute && $firstRoute->is_transfer)
    <style>
        @media (max-width: 639px){
            .sheduleBlock div.shedule ul.thead > li:nth-of-type(-n+3)::before{
                content: '';
            }
            .sheduleBlock div.shedule ul.thead > li:nth-of-type(1),
            .sheduleBlock div.shedule ul.thead > li:nth-of-type(2){
                white-space: normal;
                text-indent: inherit;
            }
        }
    </style>
    
@endif
<script>
    if( $('.sheduleBlock div.shedule ul.sheduleRow > li:nth-child(5)').val() == null) {
        $(".sheduleBlock").addClass("big");
    } else {
        $(".sheduleBlock").addClass("small");
    }
</script>
<div style="padding-top: 0px" class="item-page shedulePage mainWidth">
    <div style="padding-top:0px" class="sheduleBlock">

        {{-- @if ($firstRoute->allow_ind_transfer ?? 0)
            <form method="POST" action="{{ route('index.order.ind_transfer') }}">
                {!! Form::hidden('_token', csrf_token()) !!}
                {!! Form::hidden('route_id', $firstRoute->id) !!}
                {!! Form::hidden('city_from_id') !!}
                {!! Form::hidden('city_to_id') !!}
                {!! Form::hidden('date') !!}
                <input type="submit" class="reservationButton makeAReservation" value="{{trans('admin_labels.allow_ind_transfer')}}">
            </form>
        @endif --}}

        @if(count($tours))
            <div class="backg"><p class="mainTitle">
                {{ \App\Models\City::find(session()->get('order'.(request('return_ticket') ? '_return':''))['city_from_id'])->name }}
                - {{\App\Models\City::find(session()->get('order'.(request('return_ticket') ? '_return':''))['city_to_id'])->name}}</p>
            </div>
            <p class="subTitle subTitleL1"></p>
            <span class="note backg">* цена указана за {{ $places . ' ' . Lang::choice('место|места|мест', $places, [], 'ru') }}</span>
            <div class="shedule mainWidth">
                <ul class="thead @if (!$firstRoute->is_transfer) main @endif">
                    @if ($firstRoute->is_transfer)
                        @if ($firstRoute->flight_type == 'arrival')
                            <li>{{ trans('index.schedules.flight_number') }}</li>
                            <li>{{ trans('index.schedules.time_arrival') }} </li>
                            <li>{{ trans('index.schedules.departure_port') }} </li>
                        @else
                            <li>{{ trans('index.schedules.house_departure') }}</li>
                            <li>{{ trans('index.schedules.arrival_port') }}</li>
                        @endif 
                    @else
                        <li>{{trans('index.schedules.departure')}}</li>
                        <li>{{trans('index.schedules.arrival')}}</li>
                        @if($setting->show_places_left)
                            <li>{{trans('index.schedules.empty')}}</li>
                        @endif
                    @endif
                    <li>{{trans('index.home.cost')}}*</li>
                    <li></li>
                </ul>
                @if ($firstRoute->allow_ind_transfer ?? 0)
                    <form method="POST" action="{{ route('index.order.ind_transfer') }}">
                        {!! Form::hidden('_token', csrf_token()) !!}
                        {!! Form::hidden('route_id', $firstRoute->id) !!}
                        {!! Form::hidden('city_from_id') !!}
                        {!! Form::hidden('city_to_id') !!}
                        {!! Form::hidden('date') !!}
                        {!! Form::hidden('ind_transfer', 1) !!} 
                        @php($tour = $tours->first())
                        <ul class="sheduleRow">
                            <li>Выберите &nbsp;&nbsp;<input class="visModWrapp" style="font-size: 24px !important; font-weight: bold" name="time" value="00:00" type="time"></li>
                            <li></li>
                            @php ($tour->price = App\Services\Sale\SaleToOrderService::tourPrice($tour, $places))
                            <li>{{ $tour->route->discount_front_type ? $tour->price - ($tour->price * $tour->route->discount_front / 100) : $tour->price - $tour->route->discount_front + 10}}
                                <span class="ruble">{{trans('admin_labels.currencies_short.'.$tour->route->currency->alfa)}}</span>
                            </li>
                            <li><input type="submit" class="reservationButton makeAReservation" value="{{trans('admin_labels.allow_ind_transfer')}}"></li>
                        </ul>
                    </form>
                @endif
                
                @foreach($tours as $tour)
                    @php($freePlacesCount = $tour->freeCountPlacesNew)
                    <ul class="sheduleRow" id="tour-{{ $tour->id }}">
                        @if ($firstRoute->is_transfer)
                            @if ($firstRoute->flight_type == 'arrival')
                                <li>{{ $tour->schedule->flight_ac_code ?? ''}}-{{ $tour->schedule->flight_number ?? ''}}</li>
                                <li>{{ \App\Services\Prettifier::prettifyTime($tour->schedule->flight_time ?? '') }}</li>
                                <li>{{ \App\Services\Prettifier::prettifyTime($tour->time_start ?? '') }}</li>
                            @else
                                <li>{{ \App\Services\Prettifier::prettifyTime($tour->time_start ?? '') }}</li>
                                <li>{{ \App\Services\Prettifier::prettifyTime($tour->time_finish ?? '') }}</li>
                            @endif
                        @else
                            <li>{{$tour->datetime_start}} @if($tour->is_collect) <b>&#x267b;</b> @endif </li>
                            <li>{{$tour->datetime_finish}}</li>
                            @if($setting->show_places_left)
                                @php($showPlaceCount = ($freePlacesCount>5) ? '5+': $freePlacesCount )
                                <li>{{ $showPlaceCount }}</li>
                            @endif
                        @endif
                        @php ($tour->price = App\Services\Sale\SaleToOrderService::tourPrice($tour, $places))
                        <li>{{ $tour->route->discount_front_type ? $tour->price - ($tour->price * $tour->route->discount_front / 100) : $tour->price - $tour->route->discount_front }}
                            <span class="ruble">{{trans('admin_labels.currencies_short.'.$tour->route->currency->alfa)}}</span>
                        </li>
                        <li>
                            @if ($freePlacesCount >= $places)
                                @if($tour->datetime_start_iso > \Carbon\Carbon::now()->addMinutes($setting->time_hidden_tour_front + $tour->route->time_hidden_tour_front)->subMinutes($stationFromInterval))
                                    <a class="reservationButton makeAReservation js_get-bus" data-return="{{ $return_ticket }}"
                                       data-url="{{ route('index.schedules.getBus', $tour) }}" data-places="{{ $places ?? 1}}">
                                        {{trans('index.schedules.sel_seat')}}
                                    </a>
                                @endif
                            @else 
                                <span class="reservationButton disabled"></span>
                                <!-- свободно мест: {{ $freePlacesCount }} -->
                            @endif
                        </li>
                    </ul>
                    <div class="busRow js_get-bus-row" style="{{ $order && $order->tour_id == $tour->id ? '' : '' }}">
                        <div class="busLayoutBlock">
                            <div class="busAndInfoWrapp">
                                <div class="busBodeyWrapp js_get-bus-row-bus">
                                    @if($order && $order->tour_id == $tour->id)
                                        @include('index.schedules.partials.places')
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="shedule mainWidth">
                <h3>Рейсы на выбранную Вами дату (@date($date)) не найдены</h3>
            </div>
        @endif
    </div>
</div>