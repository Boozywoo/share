@extends('driver.layouts.app')
@section('content')
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/driver/tours') }}">
                Рейсы
            </a>
            <div class="top-right links">
                <a style="color: #fff" class="navbar-brand" href="{{ route('driver.logout') }}">Выйти</a>
        </div>
        </div>
    </nav>
    <main class="py-2">
        <div class="container">
            <meta id="tour_id" name="tour_id" content="{{ $tour->id }}">
            <meta id="is_taxi" name="is_taxi" content="{{ $tour->route->is_route_taxi }}">
            <meta id="env" name="env" content="{{ $env }}">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card  mb-2">
                        <div class="d-flex bd-highlight">
                            <h3 class="flex-grow-1 bd-highlight mt-2 ml-2">Добавление пассажира</h3>
                            <button class="btn btn-sm btn-primary pjax-link m-2 icon bd-highlight"><a href="url()->current()">
                            <span class="glyphicon glyphicon-repeat"></span>
                            </a></button> 
                            <button class="btn btn-sm btn-primary pjax-link m-2 icon bd-highlight"><a href="{{url()->previous()}}">
                                <span class="glyphicon glyphicon-arrow-left"></span>
                            </a></button> 
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <form id="pass_form" action="{{ url(route ('driver.landing.add_passenger', $tour)) }}">
                                {{ csrf_field() }}
                                <p>Направление:
                                    <b name='tour' value='{{$tour->route->name}}'>
                                        {{$tour->route->name}}
                                    </b>
                                </p>
                                <hr>
                                <p>Имя: <input class="form-control" type="text" id='first_name' name="first_name" value="Новый клиент"></p>
                                <label for="phone">Телефон: </label>
                                <div class="row ml-1">
                                    <div id="phone-block">
                                        <select style="-webkit-appearance: none; -moz-appearance: none; appearance: none;" id="country" class="form-control">
                                        @foreach(\App\Models\Client::CODE_PHONES as $abbr => $code)
                                            <option @if ($tour->driver->default_code == $abbr) selected @endif value="{{$abbr}}" code="{{$code}}">+{{$code}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    
                                    <div id="phone-input">
                                        <input id="phone" class="form-control" type="text" name="phone">
                                    </div>
                                </div>
                                <br>

                                @if (in_array('last_name', $required_inputs)) 
                                    <p>Фамилия: <input class="form-control" type="text" id='last_name' name="last_name"></p>
                                    <br>
                                @endif

                                @if (in_array('middle_name', $required_inputs)) 
                                    <p>Отчество: <input class="form-control" type="text" id='middle_name' name="middle_name"></p>
                                    <br>
                                @endif

                                @if (in_array('birth_day', $required_inputs)) 
                                    <p>Дата рождения: <input class="form-control" type="text" id='birth_day' name="birth_day"></p>
                                    <br>
                                @endif

                                @if (in_array('country_id', $required_inputs)) 
                                    <p>Гражданство
                                        <select class="form-control" name="country" id="country">
                                            <option value="0">-Выберите гражданство-</option>
                                            @foreach (trans('admin_labels.countries') as $country)
                                            <option value="{{$country}}">{{$country}}</option>
                                            @endforeach
                                        </select>
                                    </p>
                                    <br>
                                @endif

                                @if (in_array('passport', $required_inputs)) 
                                    <p>Паспорт: <input class="form-control" type="text" id='passport' name="passport"></p>
                                    <br>
                                @endif

                                @if (in_array('doc_type', $required_inputs)) 
                                    <p> Вид документа, удостоверяющего личность
                                        <select class="form-control" name="doc_type" id="doc_type">
                                            <option value="0">-Выберите документ-</option>
                                            @foreach (trans('admin_labels.doc_types') as $doc_type)
                                            <option value="{{$doc_type}}">{{$doc_type}}</option>
                                            @endforeach
                                        </select>
                                    </p>
                                @endif

                                @if (in_array('doc_number', $required_inputs)) 
                                    <p>Номер докум-та, удостоверяющего личность: <input class="form-control" type="text" id='doc_number' name="doc_number"></p>
                                    <br>
                                @endif

                                @if (in_array('card', $required_inputs)) 
                                    <p>№ карты: <input class="form-control" type="text" id='card' name="card"></p>
                                    <br>
                                @endif

                                @if (in_array('gender', $required_inputs)) 
                                    <p>Пол
                                        <select class="form-control" name="gender" id="gender">
                                            <option value="0">-Выберите пол-</option>
                                            @foreach (trans('admin_labels.genders') as $gender)
                                            <option value="{{$gender}}">{{$gender}}</option>
                                            @endforeach
                                        </select>
                                    </p>
                                    <br>
                                @endif

                                @if (in_array('flight_number', $required_inputs)) 
                                    <p>Номер рейса самолета: <input class="form-control" type="text" id='flight_number' name="flight_number"></p>
                                    <br>
                                @endif
                                
                                <p>Откуда:
                                    <select class="form-control" name="from" id="from">
                                        @foreach ($stations as $s)
                                            <option city="{{$s->city->name}}" value="{{$s->id}}"
                                                @if($stations->first()->id == $s->id)
                                                    selected
                                                @endif
                                                >{{$s->name}}, {{$s->street->name}}, {{$s->city->name}}</option>
                                        @endforeach
                                    </select>
                                </p>
                                <p>Куда:
                                    <select class="form-control" name="to" id="to">
                                        @foreach ($stations as $s)
                                            <option city="{{$s->city->name}}" value="{{$s->id}}"
                                            @if($stations->last()->id == $s->id)
                                                selected
                                            @endif    
                                            >{{$s->name}}, {{$s->street->name}}, {{$s->city->name}}</option>
                                        @endforeach
                                    </select>
                                </p>
                                @if($d_a_setting->is_change_price == 1)
                                <p>Цена: <input class="form-control" type="text" id='price' name="price" price="{{$tour->price}}" value="{{$tour->price}}"></p>
                                @endif
                                @if($d_a_setting->is_accept_cashless_payment == 1)
                                    <p>
                                        Тип оплаты:
                                        <div class="pretty p-default p-round">
                                            <input type="radio" name="type_pay" value="cash-payment" checked>
                                            <div class="state p-warning-o">
                                                <label>Расчет наличными</label>
                                            </div>
                                        </div>
                                        <div class="pretty p-default p-round">
                                            <input type="radio" name="type_pay" value="cashless-payment">
                                            <div class="state p-danger-o">
                                                <label>Безналичный расчет</label>
                                            </div>
                                        </div>
                                    </p>
                                @endif
                                @php($freePlacesCount = $tour->freePlacesCount)
                                @php($countPlaces = $freePlacesCount)

                                {!! Form::panelRange('count_places', $freePlacesCount ? 1 : 0, $countPlaces, '', null, ['class' => 'form-control js_orders-count_places'], false) !!}

                                <br>
                                @if($client_sum < $places) <input id="btn_add" type="button" class="btn btn-sm btn-primary pjax-link" value="Добавить" @if($tour->type_driver == 'collection')
                                    onclick="add(this, {{$tour->id}}, 'landing', [ @foreach($clients_phone as $cp)
                                    {{$cp}},
                                    @endforeach ] )">
                                    @else
                                    onclick="add(this, {{$tour->id}}, 'way', [ @foreach($clients_phone as $cp)
                                    {{$cp}},
                                    @endforeach ] )">
                                    @endif
                                    @else
                                    <input id="btn_add" type="button" class="btn btn-sm btn-primary pjax-link" value="Добавить" onclick="alert('Мест больше нет!')">
                                    @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection