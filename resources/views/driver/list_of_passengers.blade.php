@extends('driver.layouts.app')
@section('content')
    @php($currency = $setting->currency ? $setting->currency->alfa : 'BYN')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light shadow-sm">
            <div class="container">
                <a class="navbar-brand">Кол-во пассажиров: {{$client_sum}}</a>
                <div class="top-right links">
                    <a class="navbar-brand">Касса: {{$sum}} {{ trans('admin_labels.currencies_short.'.$currency) }}</a>
                    <a style="color: #fff" class="navbar-brand" href="{{ route('driver.logout') }}">Выйти</a>
                </div>
            </div>
        </nav>
        <main class="py-2">
            <div class="container">
                <meta id="tour_id" name="tour_id" content="{{ $tour->id }}">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="d-flex justify-content-around mb-2">
                            <a href="{{route ('driver.landing', $tour)}}">
                                <button class="btn btn-sm btn-primary pjax-link"
                                        @if($tour->type_driver == 'collection')
                                            id='curr'
                                        @endif>Посадка
                                </button>
                            </a>
                            <a href="{{route ('driver.way', $tour)}}">
                                <button class="btn btn-sm btn-primary pjax-link"
                                        @if($tour->type_driver == 'way')
                                            id='curr'
                                        @endif>В пути
                                </button>
                            </a>
                            <a>
                                <button class="btn btn-sm btn-primary pjax-link" data-toggle="modal"
                                        data-target="#endtour"><span class="glyphicon glyphicon-flag"></span></button>
                            </a>
                            <div class="modal fade" id="endtour" tabindex="-1" role="dialog"
                                 aria-labelledby="basicModal" aria-hidden="true">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <h5>Вы действительно хотите завершить рейс?</h5>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                Отмена
                                            </button>
                                            <button onclick="completed({{ $tour->id }})" type="button"
                                                    class="btn btn-primary">Да
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a>
                                <button class="btn btn-sm btn-primary pjax-link js_inform_show" value="show"><span
                                            class="glyphicon glyphicon-user"></span></button>
                            </a>

                            @if($d_a_setting->is_see_map == 1)
                                <!--<div>
                                <a href="#" class="btn btn-sm btn-primary pjax-link js_address_show" data-tour="{{$tour->id}}" data-toggle="tooltip" title="{{trans('admin.tours.map')}}">
                                    <i class="material-icons">&#xe55f;</i>
                                </a>
                            </div>-->
                                <div>
                                    <a href="{{ route('driver.buildRoute', $tour) }}"
                                       onclick="setTimeout(function(){ window.location.reload(); }, 2000);"
                                       target="_blank"
                                       class="btn btn-sm {{ $tour->mvrp_id ? 'btn-primary' : 'btn-warning'}}"
                                       data-tour="{{$tour->id}}" data-toggle="tooltip" title="Построить маршрут">
                                        <i class="material-icons">&#xe55b;</i>
                                    </a>
                                </div>
                                @if ($tour->mvrp_id)
                                    <a href="{{ route('driver.naviLink', $tour) }}" target="_blank"
                                       class="btn btn-sm btn-primary" data-tour="{{$tour->id}}" data-toggle="tooltip"
                                       title="Ссылка на навигатор">
                                        <i class="material-icons">&#xe569;</i>
                                    </a>
                                    <a href="{{ route('driver.calcTime', $tour) }}"
                                       onclick="setTimeout(function(){ window.location.reload(); }, 2000);"
                                       target="_blank" class="btn btn-sm btn-primary" data-tour="{{$tour->id}}"
                                       data-toggle="tooltip" title="Отсортировать брони">
                                        <i class="material-icons">&#xe164;</i>
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div id="address_map" bus="{{$tour->bus->id}}" style="display: none; height: 90vh"
                             class="form-group container-fluid"></div>
                    </div>
                    @if($d_a_setting->is_see_map == 1)
                        <a class="btn btn-sm btn-success pjax-link" id="navigator" href="#"
                           style="display: none;margin-bottom:10px;width: 100%; font-size:20px">Перейти к навигатору</a>
                    @endif
                    <div class="col-md-8">
                        <div class="card">
                            <meta id="tour_id" name="tour_id" content="{{ $tour->id }}">
                            <meta id="is_taxi" name="is_taxi" content="{{ $tour->route->is_route_taxi }}">
                            <meta id="env" name="env" content="{{ $env }}">
                            <div class="d-flex justify-content-around m-1">
                                @if($orders->count() > 0 && $filterType != 'disable')
                                    <button id="app_all" onclick="switchAppearanceAll({{ $tour->id }}, {{ $is_all }})"
                                            @if(!$is_all) class="btn btn-sm btn-danger pjax-link mt-1 icon" value="1">
                                        <span class="glyphicon glyphicon-remove"></span>
                                        @else
                                            class="btn btn-sm btn-primary pjax-link mt-1 icon" value="0"><span
                                                    class="glyphicon glyphicon-ok"></span>
                                        @endif
                                    </button>
                                @endif

                                <div>
                                    {{$tour->route->name}}
                                    <br>
                                    {{$tour->date_start->format('d.m')}} {{\Carbon\Carbon::parse($tour->time_start)->format('H:i')}}
                                </div>
                                @if($client_sum < $places && !$tour->route->is_transfer && !$tour->route->is_taxi)
                                    <div>
                                        <a href="{{route('driver.add', $tour)}}">
                                            <button class="btn btn-sm btn-primary pjax-link mt-1 icon">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                        </a>
                                    </div>
                                @endif
                                <button class="btn btn-sm btn-primary pjax-link mt-1 icon">
                                    <a href="{{url()->current()}}">
                                        <span class="glyphicon glyphicon-repeat"></span>
                                    </a>
                                </button>

                                @if(env('BBV') && !session()->has('bbv_token'))
                                    <button class="btn btn-sm btn-primary mt-1 icon" style="width: 7rem" id="bbv-auth" onclick="bbvAuth()">
                                        <span class="glyphicon glyphicon-print" title="Открыть смену"><br>Откр.кассу</span>
                                    </button>
                                @endif
                                @if(env('BBV') && session()->has('bbv_token'))
                                    <button class="btn btn-sm btn-primary mt-1 icon" style="width: 7rem" id="bbv-close" onclick="bbvClose({{ $tour->id }})">
                                        <span class="glyphicon glyphicon-print" title="Закрыть смену"><br>Закр.кассу</span>
                                    </button>
                                @endif
                                @if($tour->packages->count())
                                    <button class="btn btn-sm btn-primary mt-1 icon"
                                            data-url="{{ route('driver.tourPackages', $tour->id) }}" data-toggle="modal"
                                            data-target="#popup_packages_of_tour">
                                        <span class="glyphicon glyphicon-briefcase"></span>
                                    </button>
                                @endif

                                <button class="btn btn-sm btn-primary pjax-link mt-1 icon"><a
                                            href="{{route('driver.tourToday', $tour)}}">
                                        <span class="glyphicon glyphicon-arrow-left"></span>
                                    </a></button>
                            </div>
                        </div>
                    </div>
                </div>
                <form>
                    {{ csrf_field() }}
                    <select class="form-control" name="filter_type" id="filter_type">
                        <option value="active" tour="{{$tour->id}}"
                                @if($filterType == 'active') selected @endif>Активные
                        </option>
                        <option value="disable" tour="{{$tour->id}}" @if($filterType == 'disable') selected @endif>
                            Неактивные
                        </option>
                        <option value="all" tour="{{$tour->id}}" @if($filterType == 'all') selected @endif>Все</option>
                    </select>
                </form>

                <div class="justify-content-center mt-2 client-inform">
                    @if(!$tour->route->is_taxi)
                        @foreach($stations as $station)
                            @if(!empty($station->getClientsCountFrom($orders)) || !empty($station->getClientsTo($orders)))
                                @foreach($station->getClients($orders) as $order)
                                    @if(!empty($order))
                                        <div class="card mb-2 orders" is_finished="{{$order->is_finished}}"
                                             id="main{{$order->id}}">
                                            <div class="d-flex flex-nowrap bd-highlight" id="head{{$order->id}}"
                                                 @if($order->appearance !== 1) style="background-color:#3f6a97" @endif>

                                                @if($order->isAllNoApp())
                                                    @if($d_a_setting->is_display_finished_button)
                                                        @if($order->is_finished != 1)
                                                            <button class="btn btn-sm btn-light pjax-link m-2 icon"
                                                                    onclick="setFinished({{$tour->id}}, {{$order->id}})">
                                                                <span class="glyphicon glyphicon-ok btn-outline-danger"></span>
                                                            </button>
                                                        @else
                                                            <button class="btn btn-sm btn-light pjax-link m-2 icon"
                                                                    onclick="unsetFinished({{$tour->id}}, {{$order->id}})">
                                                                <span class="glyphicon glyphicon-ok btn-outline-success"></span>
                                                            </button>
                                                        @endif
                                                    @endif
                                                @else
                                                    <button class="btn btn-sm btn-light pjax-link m-2 icon"><a
                                                                href="{{url()->current()}}">
                                                            <span class="glyphicon glyphicon-ok btn-outline-success"></span>
                                                        </a></button>
                                                @endif

                                                @if($d_a_setting->is_see_pay)
                                                    <button id="pay" order="{{ $order->id }}" tour="{{ $tour->id }}"
                                                            @if(($order->source == 'operator' || $order->source == 'site') && ($order->type_pay == 'success' || $order->type_pay == 'checking-account'))
                                                                disabled class="btn btn-sm btn-primary icon m-2"
                                                            onclick="switchPay({{ $order->id }}, {{ $tour->id }}, 0)">$
                                                    </button>
                                                @elseif($order->is_pay == 1)
                                                    class="btn btn-sm btn-primary icon m-2"
                                                    onclick="switchPay({{ $order->id }}, {{ $tour->id }}, 0)">$</button>
                                                @else
                                                    class="btn btn-sm btn-danger icon m-2"
                                                    onclick="switchPay({{ $order->id }}, {{ $tour->id }}, 1)">$</button>
                                                @endif
                                                @endif

                                                @if($d_a_setting->was_calling)
                                                    <button order="{{ $order->id }}" tour="{{ $tour->id }}"
                                                            @if($order->is_call == 1)
                                                                class="btn btn-sm btn-primary m-2 icon"
                                                            onclick="switchCall({{ $order->id }}, {{ $tour->id }}, 0)">
                                                        <span class="glyphicon glyphicon-earphone"></span></button>
                                                @else
                                                    class="btn btn-sm btn-danger m-2 icon"
                                                    onclick="switchCall({{ $order->id }}, {{ $tour->id }}, 1)">
                                                    <span class="glyphicon glyphicon-phone-alt"></span></button>
                                                @endif
                                                @endif

                                                @if(!empty($order->comment))
                                                    <span class="m-2" style="color: #ffbebe">Комментарий: <br> {{$order->comment}}</span>
                                                @endif


                                                <div class="m-2">
                                                    @if(!($d_a_setting->is_show_both_directions))
                                                        @if($order->appearance == 1)
                                                            {{$station->getStationTime($orders)}}
                                                            <b>До:</b>
                                                            @if($d_a_setting->is_display_stations)
                                                                {{$order->stationTo->name}},
                                                            @endif
                                                            @if($d_a_setting->is_display_streets)
                                                                {{$order->stationTo->street->name ?? ''}},
                                                            @endif
                                                            @if($d_a_setting->is_display_cities)
                                                                {{ $order->addressTo ?? $order->stationTo->name}}
                                                            @endif
                                                            @if($d_a_setting->is_display_utc)
                                                                {{ $station->city->getShortTimezoneAttribute()}}
                                                            @endif

                                                        @else
                                                            {{$station->getStationTime($orders)}}
                                                            <b>От:</b>
                                                            @if($d_a_setting->is_display_stations)
                                                                {{ $order->addressFrom ?? $order->stationFrom->name}},
                                                            @endif
                                                            @if($d_a_setting->is_display_streets)
                                                                {{$order->stationFrom->street->name ?? ''}},
                                                            @endif
                                                            @if($d_a_setting->is_display_cities)
                                                                {{$order->stationFrom->city->name}}
                                                            @endif
                                                            @if($d_a_setting->is_display_utc)
                                                                {{ $station->city->getShortTimezoneAttribute()}}
                                                            @endif
                                                        @endif
                                                    @else
                                                        {{$order->station_from_time}}
                                                        <b>От:</b>
                                                        @if($d_a_setting->is_display_stations)
                                                            {{ $order->addressFrom ?? $order->stationFrom->name}},
                                                        @endif
                                                        @if($d_a_setting->is_display_streets)
                                                            {{$order->stationFrom->street->name}},
                                                        @endif
                                                        @if($d_a_setting->is_display_cities)
                                                            {{$order->stationFrom->city->name}}
                                                        @endif
                                                        @if($d_a_setting->is_display_utc)
                                                            {{ $station->city->getShortTimezoneAttribute()}}
                                                        @endif
                                                        <br>
                                                        {{$order->station_to_time}}

                                                        <b>До:</b>
                                                        @if($d_a_setting->is_display_stations)
                                                            {{ $order->addressTo ?? $order->stationTo->name}},
                                                        @endif
                                                        @if($d_a_setting->is_display_streets)
                                                            {{$order->stationTo->street->name}},
                                                        @endif
                                                        @if($d_a_setting->is_display_cities)
                                                            {{$order->stationTo->city->name}}
                                                        @endif
                                                        @if($d_a_setting->is_display_utc)
                                                            {{ $station->city->getShortTimezoneAttribute()}}
                                                        @endif
                                                    @endif
                                                </div>

                                                @if(!empty($order->client->socialStatus))
                                                    <div class="m-2">Соц.статус:
                                                        <br> {{ $order->client->socialStatus->name }}</div>
                                                @endif

                                                @if($d_a_setting->is_cancel)
                                                    @if($order->appearance != 1 && $order->is_finished == '0')
                                                        <button id="cancel" onclick="openCancel({{ $order->id }})"
                                                                class="close ml-auto p-2 bd-highlight" href="#"
                                                                data-toggle="modal" data-target="#smallModal"><span
                                                                    class="glyphicon glyphicon-remove"></span></button>
                                                    @elseif($order->is_finished == '0' && $d_a_setting->is_display_finished_button)
                                                        <button class="btn btn-sm btn-success ml-auto m-2 bd-highlight icon"
                                                                onclick="setFinished({{$tour->id}}, {{$order->id}})">
                                                            <span class="glyphicon glyphicon-flag"></span>
                                                        </button>
                                                    @elseif($d_a_setting->is_display_finished_button)
                                                        <button class="btn btn-sm btn-danger ml-auto m-2 bd-highlight icon"
                                                                onclick="unsetFinished({{$tour->id}}, {{$order->id}})">
                                                            <svg width="1em" height="1em" viewBox="0 0 16 16"
                                                                 color="white" class="bi bi-arrow-counterclockwise"
                                                                 fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd"
                                                                      d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                                                                <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                                                            </svg>
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>

                                            @if($d_a_setting->is_see_statistics == 1)
                                                <table class="table table-bordered text-center">
                                                    <tr>
                                                        <td><i class="fa fa-arrow-right" title="Кол-во поездок"
                                                               aria-hidden="true"></i></td>
                                                        <td><i class="fa fa-check text-success" title="Кол-во явок"
                                                               aria-hidden="true"></i></td>
                                                        <td><i class="fa fa-times text-danger" title="Кол-во неявок"
                                                               aria-hidden="true"></i></td>
                                                        <td><i class="fa fa-ban text-danger"
                                                               title="Кол-во отмененных броней" aria-hidden="true"></i>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{$order->client->orders->where('status', 'active')->count()}}</td>
                                                        <td>{{$order->client->orders->where('appearance', 1)->where('status', 'active')->count()}}</td>
                                                        <td>{{$order->client->orders->where('appearance', 0)->where('status', 'active')->count()}}</td>
                                                        <td>{{$order->client->orders->where('status', 'disable')->count()}}</td>
                                                    </tr>
                                                </table>
                                            @endif

                                            <div class="modal fade" id="smallModal" tabindex="-1" role="dialog"
                                                 aria-labelledby="basicModal" aria-hidden="true">
                                                <div class="modal-dialog modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <h5>Вы действительно хотите отменить бронь?</h5>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Отмена
                                                            </button>
                                                            <button id="cancel_order"
                                                                    onclick="cancelOrder(this.value, {{ $tour->id }})"
                                                                    type="button" class="btn btn-primary">Да
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @foreach($order->orderPlaces->where('type', '!=', 'completed') as $op)
                                                <div class="card" id="app_on_place{{$op->id}}">

                                                    @if($op->appearance)
                                                        <div class="d-flex flex-nowrap bd-highlight bg-app">
                                                            @elseif($op->appearance === null)
                                                                <div class="d-flex flex-nowrap bd-highlight">
                                                                    @else
                                                                        <div class="d-flex flex-nowrap bd-highlight bg-no-app">
                                                                            @endif

                                                                            <button id="{{ $op->id }}"
                                                                                    onclick="switchAppearance({{ $op->id }}, {{ $tour->id }}, {{$op->order->id}})"
                                                                                    @if($op->appearance === null) class="btn btn-sm btn-info icon m-2 btn-lg"
                                                                                    appearance="0"><span
                                                                                        class="glyphicon glyphicon-question-sign"></span>
                                                                                @elseif($op->appearance == 0)
                                                                                    class="btn btn-sm btn-danger icon
                                                                                    m-2 btn-lg" appearance="0"><span
                                                                                            class="glyphicon glyphicon-remove"></span>
                                                                                @else
                                                                                    class="btn btn-sm btn-primary icon
                                                                                    m-2" appearance="1"><span
                                                                                            class="glyphicon glyphicon-ok"></span>
                                                                                @endif
                                                                            </button>


                                                                            <div class="m-2">
                                                                                <b>
                                                                                    @if($d_a_setting->is_display_first_name == 1)
                                                                                        {{empty($order->client->first_name) ? ' ' : $order->client->first_name}}
                                                                                    @endif
                                                                                    @if($d_a_setting->is_display_middle_name == 1)
                                                                                        {{empty($order->client->middle_name) ? ' ' : $order->client->middle_name}}
                                                                                    @endif
                                                                                    @if($d_a_setting->is_display_last_name == 1)
                                                                                        {{empty($order->client->last_name) ? ' ' : $order->client->last_name}}
                                                                                    @endif
                                                                                </b>
                                                                                @if($d_a_setting->is_see_passeger_passport == 1)
                                                                                    ({{substr($order->client->doc_number, -$d_a_setting->count_of_passport_digits) ?? substr($order->client->passport, -$d_a_setting->count_of_passport_digits)}}
                                                                                    )
                                                                                @endif
                                                                                <br>
                                                                                <div style="color: #ff8181; font-weight: bold;">{{$op->price}} {{ trans('admin_labels.currencies_short.'.$op->order->tour->route->currency->alfa) }}</b></div>
                                                                                @if($d_a_setting->is_see_passeger_phone == 1)
                                                                                    <u><a color="blue"
                                                                                          href="tel:+{{empty($order->client->phone) ? ' ' : $order->client->phone}}"><b>+{{empty($order->client->phone) ? ' ' : $order->client->phone}}</b></a></u>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                </div>
                                                                @endforeach
                                                        </div>
                                                    @endif
                                                    @endforeach
                                                    @endif
                                                    @endforeach
                                                    @else
                                                        @foreach($orders as $order)
                                                            @if(!empty($order))
                                                                <div class="card mb-2 orders"
                                                                     is_finished="{{$order->is_finished}}"
                                                                     id="main{{$order->id}}">
                                                                    <div class="d-flex flex-nowrap bd-highlight"
                                                                         id="head{{$order->id}}"
                                                                         @if($order->appearance !== 1) style="background-color:#3f6a97" @endif>
                                                                        @if($order->isAllNoApp())
                                                                            @if($d_a_setting->is_display_finished_button)
                                                                                <button class="btn btn-sm btn-light pjax-link m-2 icon"
                                                                                        onclick="setFinished({{$tour->id}}, {{$order->id}})">
                                                                                    <span class="glyphicon glyphicon-ok btn-outline-danger"></span>
                                                                                </button>
                                                                            @endif
                                                                        @else
                                                                            <button class="btn btn-sm btn-light pjax-link m-2 icon">
                                                                                <a href="{{url()->current()}}">
                                                                                    <span class="glyphicon glyphicon-ok btn-outline-success"></span>
                                                                                </a></button>
                                                                        @endif

                                                                        @if($d_a_setting->is_see_pay)
                                                                            <button id="pay" order="{{ $order->id }}"
                                                                                    tour="{{ $tour->id }}"
                                                                                    @if(($order->source == 'operator' || $order->source == 'site') && ($order->type_pay == 'success' || $order->type_pay == 'checking-account'))
                                                                                        disabled
                                                                                    class="btn btn-sm btn-primary icon m-2"
                                                                                    value="1">$
                                                                            </button>
                                                                        @elseif($order->is_pay == 1)
                                                                            class="btn btn-sm btn-primary icon m-2"
                                                                            value="1">$</button>
                                                                        @else
                                                                            class="btn btn-sm btn-danger icon m-2"
                                                                            value="0">$</button>
                                                                        @endif
                                                                        @endif

                                                                        @if($d_a_setting->was_calling)
                                                                            <button order="{{ $order->id }}"
                                                                                    tour="{{ $tour->id }}"
                                                                                    @if($order->is_call == 1)
                                                                                        class="btn btn-sm btn-primary m-2 icon"
                                                                                    onclick="switchCall({{ $order->id }}, {{ $tour->id }}, 0)">
                                                                                <span class="glyphicon glyphicon-earphone"></span>
                                                                            </button>
                                                                        @else
                                                                            class="btn btn-sm btn-danger m-2 icon"
                                                                            onclick="switchCall({{ $order->id }}
                                                                            , {{ $tour->id }}, 1)">
                                                                            <span class="glyphicon glyphicon-phone-alt"></span></button>
                                                                        @endif
                                                                        @endif

                                                                        @if(!empty($order->comment))
                                                                            <span class="m-2" style="color: #ffbebe">Комментарий: <br> {{$order->comment}}</span>
                                                                        @endif


                                                                        <div class="m-2">
                                                                            @if(!($d_a_setting->is_show_both_directions))
                                                                                @if($order->appearance == 1)
                                                                                    <b>До:</b>
                                                                                    @if($d_a_setting->is_display_stations)
                                                                                        {{$order->stationTo->name}},
                                                                                    @endif
                                                                                    {{ $order->station_to_time}}
                                                                                    @if($d_a_setting->is_display_utc)
                                                                                        {{ $order->stationTo->city->getShortTimezoneAttribute()}}
                                                                                    @endif
                                                                                @else
                                                                                    <b>От:</b>
                                                                                    @if($d_a_setting->is_display_stations)
                                                                                        {{ $order->addressFrom ?? $order->stationFrom->name}}
                                                                                        ,
                                                                                    @endif
                                                                                    {{ $order->station_from_time}}
                                                                                    @if($d_a_setting->is_display_utc)
                                                                                        {{ $order->stationFrom->city->getShortTimezoneAttribute()}}
                                                                                    @endif
                                                                                @endif
                                                                            @else
                                                                                <b>От:</b>
                                                                                @if($d_a_setting->is_display_stations)
                                                                                    {{ $order->addressFrom ?? $order->stationFrom->name}}
                                                                                    ,
                                                                                @endif
                                                                                {{ $order->station_from_time}}
                                                                                @if($d_a_setting->is_display_utc)
                                                                                    {{ $order->stationFrom->city->getShortTimezoneAttribute()}}
                                                                                @endif
                                                                                <br>
                                                                                <b>До:</b>
                                                                                @if($d_a_setting->is_display_stations)
                                                                                    {{$order->stationTo->name}},
                                                                                @endif
                                                                                {{ $order->station_to_time}}
                                                                                @if($d_a_setting->is_display_utc)
                                                                                    {{ $order->stationTo->city->getShortTimezoneAttribute()}}
                                                                                @endif
                                                                            @endif
                                                                        </div>

                                                                        @if(!empty($order->client->socialStatus))
                                                                            <div class="m-2">Соц.статус:
                                                                                <br> {{ $order->client->socialStatus->name }}
                                                                            </div>
                                                                        @endif

                                                                        @if($d_a_setting->is_see_map == 1)
                                                                            <div>
                                                                                <a href="#"
                                                                                   class="btn btn-sm btn-primary pjax-link ml-auto m-2 bd-highlight js_address_show_street icon"
                                                                                   @if($order->appearance == 1)
                                                                                       latitude="{{$order->stationTo->latitude}}"
                                                                                   longitude="{{$order->stationTo->longitude}}"
                                                                                   @else
                                                                                       latitude="{{$order->stationFrom->latitude}}"
                                                                                   longitude="{{$order->stationFrom->longitude}}"
                                                                                   @endif
                                                                                   data-toggle="tooltip">
                                                                                    <i class="material-icons">&#xe55f;</i>
                                                                                </a>
                                                                            </div>
                                                                        @endif

                                                                        @if($d_a_setting->is_cancel)
                                                                            @if($order->appearance != 1 && $order->is_finished == '0')
                                                                                <button id="cancel"
                                                                                        onclick="openCancel({{ $order->id }})"
                                                                                        class="close ml-auto p-2 bd-highlight"
                                                                                        href="#" data-toggle="modal"
                                                                                        data-target="#smallModal"><span
                                                                                            class="glyphicon glyphicon-remove"></span>
                                                                                </button>
                                                                            @elseif($order->is_finished == '0' && $d_a_setting->is_display_finished_button)
                                                                                <button class="btn btn-sm btn-success ml-auto m-2 bd-highlight icon"
                                                                                        onclick="setFinished({{$tour->id}}, {{$order->id}})">
                                                                                    <span class="glyphicon glyphicon-flag"></span>
                                                                                </button>
                                                                            @elseif($d_a_setting->is_display_finished_button)
                                                                                <button class="btn btn-sm btn-danger ml-auto m-2 bd-highlight icon"
                                                                                        onclick="unsetFinished({{$tour->id}}, {{$order->id}})">
                                                                                    <svg width="1em" height="1em"
                                                                                         viewBox="0 0 16 16"
                                                                                         color="white"
                                                                                         class="bi bi-arrow-counterclockwise"
                                                                                         fill="currentColor"
                                                                                         xmlns="http://www.w3.org/2000/svg">
                                                                                        <path fill-rule="evenodd"
                                                                                              d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                                                                                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                                                                                    </svg>
                                                                                </button>
                                                                            @endif
                                                                        @endif
                                                                    </div>

                                                                    @if($d_a_setting->is_see_statistics == 1)
                                                                        <table class="table table-bordered text-center">
                                                                            <tr>
                                                                                <td><i class="fa fa-arrow-right"
                                                                                       title="Кол-во поездок"
                                                                                       aria-hidden="true"></i></td>
                                                                                <td><i class="fa fa-check text-success"
                                                                                       title="Кол-во явок"
                                                                                       aria-hidden="true"></i></td>
                                                                                <td><i class="fa fa-times text-danger"
                                                                                       title="Кол-во неявок"
                                                                                       aria-hidden="true"></i></td>
                                                                                <td><i class="fa fa-ban text-danger"
                                                                                       title="Кол-во отмененных броней"
                                                                                       aria-hidden="true"></i></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>{{$order->client->orders->where('status', 'active')->count()}}</td>
                                                                                <td>{{$order->client->orders->where('appearance', 1)->where('status', 'active')->count()}}</td>
                                                                                <td>{{$order->client->orders->where('appearance', 0)->where('status', 'active')->count()}}</td>
                                                                                <td>{{$order->client->orders->where('status', 'disable')->count()}}</td>
                                                                            </tr>
                                                                        </table>
                                                                    @endif

                                                                    <div class="modal fade" id="smallModal"
                                                                         tabindex="-1" role="dialog"
                                                                         aria-labelledby="basicModal"
                                                                         aria-hidden="true">
                                                                        <div class="modal-dialog modal-sm">
                                                                            <div class="modal-content">
                                                                                <div class="modal-body">
                                                                                    <h5>Вы действительно хотите отменить
                                                                                        бронь?</h5>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button"
                                                                                            class="btn btn-secondary"
                                                                                            data-dismiss="modal">Отмена
                                                                                    </button>
                                                                                    <button id="cancel_order"
                                                                                            onclick="cancelOrder(this.value, {{ $tour->id }})"
                                                                                            type="button"
                                                                                            class="btn btn-primary">Да
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    @foreach($order->orderPlaces->where('type', '!=', 'completed') as $op)
                                                                        <div class="card" id="app_on_place{{$op->id}}">

                                                                            @if($op->appearance)
                                                                                <div class="d-flex flex-nowrap bd-highlight bg-app">
                                                                                    @elseif($op->appearance === null)
                                                                                        <div class="d-flex flex-nowrap bd-highlight">
                                                                                            @else
                                                                                                <div class="d-flex flex-nowrap bd-highlight bg-no-app">
                                                                                                    @endif

                                                                                                    <button onclick="switchAppearance({{ $op->id }}, {{ $tour->id }}, {{$op->order->id}})"
                                                                                                            @if($op->appearance === null) class="btn btn-sm btn-info icon m-2 btn-lg"
                                                                                                            appearance="0">
                                                                                                        <span class="glyphicon glyphicon-question-sign"></span>
                                                                                                        @elseif($op->appearance == 0)
                                                                                                            class="btn
                                                                                                            btn-sm
                                                                                                            btn-danger
                                                                                                            icon m-2
                                                                                                            btn-lg"
                                                                                                            appearance="0">
                                                                                                            <span class="glyphicon glyphicon-remove"></span>
                                                                                                        @else
                                                                                                            class="btn
                                                                                                            btn-sm
                                                                                                            btn-primary
                                                                                                            icon m-2"
                                                                                                            appearance="1">
                                                                                                            <span class="glyphicon glyphicon-ok"></span>
                                                                                                        @endif
                                                                                                    </button>

                                                                                                    <div class="m-2">
                                                                                                        <b>
                                                                                                            @if($d_a_setting->is_display_first_name == 1)
                                                                                                                {{empty($order->client->first_name) ? ' ' : $order->client->first_name}}
                                                                                                            @endif
                                                                                                            @if($d_a_setting->is_display_middle_name == 1)
                                                                                                                {{empty($order->client->middle_name) ? ' ' : $order->client->middle_name}}
                                                                                                            @endif
                                                                                                            @if($d_a_setting->is_display_last_name == 1)
                                                                                                                {{empty($order->client->last_name) ? ' ' : $order->client->last_name}}
                                                                                                            @endif
                                                                                                        </b>
                                                                                                        <br>
                                                                                                        <div style="color:#ffbebe">{{$op->price}} {{ trans('admin_labels.currencies_short.'.$op->order->tour->route->currency->alfa) }}</b></div>
                                                                                                        @if($d_a_setting->is_see_passeger_phone == 1)
                                                                                                            <u><a color="blue"
                                                                                                                  href="tel:+{{$order->client->phone}}"><b>+{{empty($order->client->phone) ? ' ' : $order->client->phone}}</b></a></u>
                                                                                                        @endif
                                                                                                    </div>
                                                                                                </div>
                                                                                        </div>
                                                                                        @endforeach
                                                                                </div>
                                                                            @endif
                                                                            <div class="col-md-8">
                                                                                <div id="station_map"
                                                                                     style="display: none; height: 90vh"
                                                                                     class="form-group container-fluid"></div>
                                                                            </div>
                                                                            @endforeach
                                                                            @endif
                                                                        </div>

                                                                        @foreach($stations as $station)
                                                                            @if(!empty($station->getClientsTo($orders)))
                                                                                <p class="text-center font-weight-bold">
                                                                                    Остановка высадки пассажиров</p>
                                                                                @include('driver.order_landing')
                                                                            @endif

                                                                            @if(!empty($station->getClientsCountFrom($orders)))
                                                                                <p class="text-center font-weight-bold">
                                                                                    Остановка посадки пассажиров</p>
                                                                                @include('driver.order_boarding')
                                                                            @endif
                                                                        @endforeach

                                                                </div>
                                                </div>
        </main>
        <div class="background-spinner"></div>
        <div class="js_spinner-overlay small"></div>
    </div>
@endsection

