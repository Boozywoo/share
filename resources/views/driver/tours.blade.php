@extends('driver.layouts.app')
@section('content')
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/driver/tours') }}">
                Рейсы
            </a>
            <button class="btn btn-sm btn-success ml-auto m-2 bd-highlight js_settings_show" onclick="">
                <span class="glyphicon glyphicon-cog"></span>
            </button> 
            <div class="top-right links">
                <a style="color: #fff" class="navbar-brand" href="{{ route('driver.logout') }}">Выйти</a>
        </div>
        </div>
    </nav>
    <div class="col-md-8 pt-2">
        <div id="settings" style="display: none; overflow:auto" class="container-fluid">
            <p>Размер шрифта:</p>
            <div class="form-check form-check-inline">
                <input type="radio" class="form-check-input" id="normal" onclick="normalFont()" name="inlineMaterialRadiosExample">
                <label class="form-check-label" for="normalFont">Маленький</label>
            </div>

            <div class="form-check form-check-inline">
                <input type="radio" class="form-check-input" id="larger" onclick="largerFont()" name="inlineMaterialRadiosExample">
                <label class="form-check-label" for="largerFont">Средний</label>
            </div>

            <div class="form-check form-check-inline">
                <input type="radio" class="form-check-input" id="biggest" onclick="biggestFont()" name="inlineMaterialRadiosExample">
                <label class="form-check-label" for="biggestFont">Большой</label>
            </div>
        </div>
    </div>
    <main class="py-2">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-around">
                                <a href="/driver/tours/getToursToday"><button class="btn btn-sm btn-primary pjax-link">Сегодня</button></a>
                                <a href="/driver/tours/getToursTomorrow"><button class="btn btn-sm btn-primary pjax-link">Завтра</button></a>
                                <a href="/driver/tours/getToursOnWeek"><button class="btn btn-sm btn-primary pjax-link">Неделя</button></a>
                                <a href="/driver/tours/getToursOnMonth"><button class="btn btn-sm btn-primary pjax-link">Месяц</button></a>
                            </div>
                        </div>
                    </div>
                    <br>
                    @foreach($tours as $tour)
                        @if(!in_array($tour->id, $ids_time_show_driver))
                            @continue;
                        @endif
                        <div class="card" @if($tour->route->is_taxi) style="background-color:#a4a468 !important" @endif>
                            @if(in_array($tour->id, $ids_time_click_driver))
                                <a href="{{route ('driver.landing', $tour)}}">
                            @else
                                <a onclick="alertForD({{$time_limit_db}})">
                            @endif
                            <div class="card-header">
                                {{$tour->route->name ?? ''}}
                                <br>
                                Отправление: <b>{{\Carbon\Carbon::parse($tour->time_start)->format('H:i')}}</b> {{$tour->date_start->format('d-m-Y')}} 
                            </div>
                            <div class="card-body">
                                <p>
                                    Количество пассажиров: <b>{{$tour->ordersConfirm()->sum('count_places')}}</b>
                                </p>
                                @if ($tour->packages->count())
                                    <p>
                                        Количество посылок: <b>{{ $tour->packages->count() }}</b>
                                    </p>
                                @endif
                                <p class="card-text">
                                    Автобус: {{$tour->bus->name}} {{$tour->bus->number}}
                                    (мест: {{$tour->bus->places}})
                                    <br>
                                    Касса рейса:
                                    {{ $tour->cash }} {{ trans('admin_labels.currencies_short.' . $tour->route->currency->alfa) }}
                                </p>
                                @if (!empty($tour->addServices))
                                    <div class="card-header">
                                        Доп. сервисы:<br>
                                        @foreach($tour->addServices as $key => $item)
                                            {{ $key }}: {{ $item }}<br>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            </a>
                        </div>
                        <br>
                    @endforeach
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
