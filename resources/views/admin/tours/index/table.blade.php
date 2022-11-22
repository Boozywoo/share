@if($tours->count())
    <div id="tours-index">
        <div class="table-responsive">
            <table class="table table-condensed table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{ trans('admin_labels.schedule_id') }}</th>
                    <th>{{ trans('admin_labels.time_start') }}</th>
                    @if ($settings['show_arrival_time'])
                        <th>{{ trans('admin_labels.time_finish') }}</th>
                    @endif
                    <th>{{ trans('admin_labels.route_id') }}</th>
                    <th>{{ trans('admin_labels.bus_id') }}</th>
                    <th>{{ trans('admin_labels.driver_id')}}</th>
                    <th>{{ trans('admin_labels.free_places')}}</th>
                    <th>{{ trans('admin_labels.sum')}}</th>
                    <th>{{ trans('admin_labels.price')}}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                @php($isMediator = Auth::user()->isMediator)
                @php($isAgent = Auth::user()->isAgent)
                @php($currency = ' ' . $tours->first()->currency)

                @foreach($tours as $tour)
                    @if(($isAgent || $isMediator) && !$tour->is_show_agent)
                        @continue
                    @endif
                    @php($freePlacesCount = config('app.FRAGMENTATION_RESERVED') ? $tour->ordersFreeCity(request('city_from_id'), request('city_to_id')) : $tour->freePlacesCount)
                    @php($freePlacesCount = $freePlacesCount > 0 ? $freePlacesCount : 0)

                    <tr class="{{ $tour->id == request('tour_id') ? $bgWarning : '' }} {{ $tour->status == 'virtual' ? 'transp' : ''}}">
                        <td nowrap class="td-actions">
                            {!! trans('pretty.statuses.'. $tour->status ) !!}
                            {!! trans('pretty.shift.'. $tour->shift ) !!}
                            @if($tour->package)
                                <span data-url="{{ route('admin.packages.tourPackages', $tour->id) }}"
                                      data-toggle="modal"
                                      data-target="#popup_packages_of_tour" role="button"
                                      class="text-info cursor-pointer">
                                <i class="fa fa-shopping-bag"></i>
                            </span>
                            @endif
                            @if($tour->comment)
                                <i class="fa fa-comment" data-toggle="tooltip" title="{{ $tour->comment }}"></i>
                            @endif
                            @if (!$tour->is_show_front)
                                <span class="text-danger" data-toggle="tooltip" title=""
                                      data-original-title="{{trans('admin.routes.not_displayed')}}">
                                <i class="text-danger fa fa-eye"></i>
                            </span>
                            @endif
                            @if($tour->schedule)
                                @if($tour->schedule->repeat)
                                    <i class="fa fa-retweet" data-toggle="tooltip" title="Постоянное расписание"></i>
                                @else
                                    <i class="fa fa-calendar" data-toggle="tooltip" title="Сезонное расписание"></i>
                                @endif
                            @else
                                @if($tour->is_individual)
                                    <i class="fa fa-fighter-jet" data-toggle="tooltip"
                                       title="Индивидуальный трансфер"></i>
                                @else
                                    <i class="fa fa-send-o" data-toggle="tooltip" title="Одиночный рейс"></i>
                                @endif
                            @endif
                            @if (isset($order))
                                <span data-url="{{ route('admin.orders.toTour', [$tour, $order]) }}"
                                      data-city_from_id="{{request('city_from_id')}}"
                                      data-city_to_id="{{request('city_to_id')}}"
                                      data-toggle="title"
                                      data-tour_id="{{$tour->id}}"
                                      data-title="Перейти в рейс"
                                      class="btn btn-sm btn-primary js_orders-toTour">
                                @if($tour->reservation_by_place==1)
                                        <i class="fa fa-plus-place"></i>
                                    @else
                                        <i class="fa fa-plus"></i>
                                    @endif
                            </span>
                            @elseif(($freePlacesCount && in_array($tour->status, [\App\Models\Tour::STATUS_ACTIVE, \App\Models\Tour::STATUS_DUPLICATE])) || env('FRAGMENTATION_RESERVED'))
                                <a href="{{ route('admin.orders.create',
                            [
                                'order' => '',
                                'tour_id' => $tour,
                                'route_id' => $tour->route_id,
                                'date' =>  $tour->date_start->format('d.m.Y'),
                                'city_from_id' => request('city_from_id'),
                                'city_to_id' => request('city_to_id'),
                                'incoming_phone' => request('incomming_phone'),
                                'order_return' => request('order_return')
                            ]) }}" data-toggle="title" data-title="Перейти в рейс"
                                   class="btn btn-sm btn-primary">
                                    @if($tour->reservation_by_place==1)
                                        <i class="fa fa-plus-place" data-toggle="tooltip" title="Бронировать"></i>
                                    @else
                                        <i class="fa fa-plus" data-toggle="tooltip" title="Бронировать"></i>
                                    @endif
                                </a>
                            @elseif (!$freePlacesCount && $tour->is_reserve)
                                <a href="{{ route('admin.orders.create',
                            [
                                'order' => '',
                                'status' => \App\Models\Order::STATUS_RESERVE,
                                'tour_id' => $tour,
                                'route_id' => $tour->route_id,
                                'date' => $tour->date_start->format('d.m.Y'),
                                'city_from_id' => request('city_from_id'),
                                'city_to_id' => request('city_to_id'),
                                'incoming_phone' => request('incomming_phone'),
                                'order_return' => request('order_return')
                            ]) }}" data-toggle="title" data-title="Перейти в рейс"
                                   class="btn btn-sm btn-success">
                                    <i class="fa fa-plus"></i>
                                </a>
                            @endif
                            @if ($tour->ordersPullReserve->sum('count_places'))
                                <i class="fa fa-link" data-toggle="tooltip"
                                   title="Есть брони в пуле: {{ $tour->ordersPullReserve->sum('count_places') }}"></i>
                            @endif
                            @if ($tour->route->is_taxi || $tour->route->is_route_taxi)
                                <span class="text-warning" data-toggle="tooltip" title="Taxi"><i
                                            class="fa-warning fa fa-taxi"></i></span>
                            @endif
                            @if ($tour->route->is_transfer && $tour->route->flight_type == 'arrival')
                                &nbsp;<img src="{{asset('assets/admin/images/landing.png')}}" alt="">
                            @endif
                            @if ($tour->route->is_transfer && $tour->route->flight_type == 'departure')
                                &nbsp;<img src="{{asset('assets/admin/images/takeoff.png')}}" alt="">
                            @endif
                        </td>
                        <td>
                            @if ($tour->route->is_transfer)
                                @if ($tour->schedule && !empty($tour->schedule->flight_time))
                                    <span data-toggle="tooltip"
                                          title="{{ trans('index.schedules.time_'.$tour->route->flight_type).': '.\App\Services\Prettifier::prettifyTime($tour->schedule->flight_time) }}">
                                    {{ $tour->flightNumber }}
                                </span>
                                @else
                                    {{ trans('admin_labels.is_transfer') }}
                                @endif
                            @else
                                {{ $tour->schedule_id }}
                            @endif
                        </td>
                        <td>
                            <span data-toggle="tooltip"
                                  title="{{ $tour->time_start_tz }}">{!! $tour->time_start !!}</span>
                        </td>
                        @if ($settings['show_arrival_time'])
                            <td nowrap><span data-toggle="tooltip"
                                             title="{{ $tour->time_finish_tz }}">{!! $tour->time_finish !!}</span></td>
                        @endif
                        <td>
                            @if($tour->route)
                                {{ $tour->route->name }}
                            @elseif($tour->is_rent)
                                <b>Аренда</b>
                            @endif
                        </td>
                        @php($busPlaces = $tour->bus ? $tour->bus->places : 0 )
                        <td>
                            @if($tour->bus)
                                <span @if($tour->status == \App\Models\Tour::STATUS_DUPLICATE && (
                            $tour->type_duplicate == \App\Models\Tour::TYPE_DUPLICATE_BUS || $tour->type_duplicate == \App\Models\Tour::TYPE_DUPLICATE_ALL))
                                          class="font-bold text-danger"
                                      @elseif($tour->bus->status == \App\Models\Bus::STATUS_SYSTEM)
                                          class="font-bold text-success" data-toggle="tooltip" title="Системный автобус"
                                    @endif>
                                {{ $tour->bus->name }} {{ $tour->bus->number }}
                            </span>
                            @else
                                <b>Автобус не назначен</b>
                            @endif
                            <span class="font-bold">
                            @if($busPlaces)
                                    {{ $tour->bus->places }} мест
                                @endif
                        </span>
                            {{--{!! trans('pretty.tours.types.'. $tour->type_driver ) !!}--}}
                        </td>
                        <td>
                            @if($tour->driver)
                                <span @if($tour->status == \App\Models\Tour::STATUS_DUPLICATE && (
                            $tour->type_duplicate == \App\Models\Tour::TYPE_DUPLICATE_DRIVER || $tour->type_duplicate == \App\Models\Tour::TYPE_DUPLICATE_ALL))
                                          class="font-bold text-danger"
                                      @elseif($tour->driver->status == \App\Models\Bus::STATUS_SYSTEM)
                                          class="font-bold text-success" data-toggle="tooltip"
                                      title="Системный водитель"
                                    @endif>
                                {{ $tour->driver->last_name.' '.$tour->driver->full_name }}
                            </span>
                            @else
                                <b>Водитель не назначен</b>
                            @endif
                            {!! trans('pretty.tours.types.'. $tour->type_driver ) !!}
                        </td>
                        <td class="{{ $freePlacesCount < 3 ? 'text-red font-bold' : '' }} {{ $freePlacesCount == $busPlaces ? 'text-green font-bold' : '' }}">
                        <span @if($freePlacesCount < $busPlaces) data-toggle="tooltip" title=""
                              data-original-title="Броней:{{ $tour->ordersReady->count() }}" }}@endif >
                                {{ $freePlacesCount }}
                        </span>
                        </td>
                        <td class="{{ $tour->unpaidOrders ? 'text-yellow' : ''}} {{ $tour->ordersReady->sum('price') ? 'very-bold' : 'font-bold text-green'}}">
                            <span>{{ $tour->ordersReady->sum('price') }} {{ $tour->currency }}</span>
                        </td>
                        <td nowrap {{--data-original-title="{{$tour->statisticPlaces['amount']}}"--}}>
                            @php($route_price = \App\Models\RouteStationPrice::where('route_id', $tour->route->id)
                                ->where('station_from_id', $tour->route->stations->first()->id)
                                ->where('station_to_id', $tour->route->stations->last()->id)->first())

                            @if($isMediator)
                                {{ ($tour->route->is_line_price ? $tour->price : ($route_price ? $route_price->price : $tour->price)) + (isset($userRoutes[$tour->route->id]) ? $userRoutes[$tour->route->id]->pivot->added_price : 0) }} {{ $tour->currency }}
                            @else
                                {{ $tour->route->is_line_price ? $tour->price : ($route_price ? $route_price->price : $tour->price) }} {{ $tour->currency }}
                            @endif
                        </td>
                        <td class="td-actions">
                            @if($tour->is_rent)
                                @php($routePopup='.showPopupRent')
                            @else
                                @php($routePopup='.showPopup')
                            @endif
                            @if(!$isAgent && !$isMediator)
                                <span data-url="{{route ('admin.' . $entity . $routePopup, $tour)}}" data-toggle="modal"
                                      data-target="#popup_tour-edit"
                                      class="btn btn-sm btn-primary">
                            <i class="fa fa-edit"></i>
                        </span>
                            @endif
                            <a href="{{route ('admin.' . $entity . '.show', $tour)}}"
                               class="btn btn-sm btn-warning pjax-link">
                                <i class="fa fa-eye"></i>
                            </a>
                            @if($tour->is_edit && $tour->orders_ready_count && !$isAgent && !$isMediator)
                                <span data-url="{{route ('admin.' . $entity . '.sendSmsPopup', $tour)}}"
                                      data-toggle="modal"
                                      data-target="#popup_tour-edit"
                                      class="btn btn-sm btn-info">
                                <i class="fa fa-envelope-o"></i>
                            </span>
                            @endif
                            @if ($tour->route->is_egis && $tour->orders_ready_count  && !$isMediator && $tour->egis_status != 'success')
                                <span data-url="{{route ('admin.' . $entity . '.sendEgisPopup', $tour)}}"
                                      data-toggle="modal"
                                      data-target="#popup_tour-edit" class="btn btn-sm
                                @if(empty($tour->egis_status))btn-info" @elseif($tour->egis_status == 'sent')btn-success
                                "
                            @elseif($tour->egis_status == 'error')
                                btn-danger"
                            @endif
                            title="Отправить в ЕГИС"><i class="fa fa-newspaper-o" aria-hidden="true"></i>
                            </span>
                            @endif
                            @if(!$tour->schedule && !$isMediator)
                                <a href="{{route ('admin.' . $entity . '.delete', $tour)}}"
                                   class="btn btn-sm btn-danger js_panel_confirm" data-toggle="tooltip"
                                   title="{{trans('admin.filter.delete')}}">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="packages-index" style="display: none"></div>

    @if (isset($stats) && !$isAgent && !$isMediator)
        <div class="text-right">
            {{ $stats['totalOrders'] }} {{ Lang::choice('бронь|брони|броней', $stats['totalOrders']) }},
            {{ $stats['passengers'] }} {{ Lang::choice('пассажир|пассажира|пассажиров', $stats['passengers']) }}
            на {{ $stats['toursWithOrders'] }} {{ Lang::choice('рейс|рейса|рейсов', $stats['toursWithOrders']) }}
            .<br>
            Оплачено @if ($stats['totalPaidCash'])
                наличными: {{ $stats['totalPaidCash'] . $currency }},
            @endif
            @if ($stats['totalPaidOnline'])
                онлайн: {{ $stats['totalPaidOnline'] . $currency }},
            @endif
            @if ($stats['totalPaidBank'])
                на р/c: {{ $stats['totalPaidBank'] . $currency }},
            @endif
            всего: {{ $stats['totalPaid'] . $currency }}<br>
            В ожидании оплаты: {{ $stats['totalWaitPay'] . $currency }}<br>
            Средняя выручка за
            рейс: {{ round($stats['totalPaid']/($stats['toursWithOrders'] ? $stats['toursWithOrders'] : 1), 2) . $currency }}
            <br>
            Количество посылок: {{ $stats['totalCountPackages'] }} шт.
            <br>
            Общая стоимость посылок:

            @foreach($stats['totalPricePackages'] as $nameCurrency => $valueCurrency)
                {{$valueCurrency}} {{$nameCurrency}}<br>
            @endforeach

        </div>
    @endif
@else
    <p class="text-muted">{{ trans('admin.users.nothing') }}</p>
@endif