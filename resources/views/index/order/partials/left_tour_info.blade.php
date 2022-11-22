{!! Form::open(['route' => 'index.order.store', 'class' => 'js_ajax-form js_form-order']) !!}
@php($currency = (isset($tour) && $tour->route->currency) ? $tour->route->currency->alfa : (!empty($curr_back->alfa)?$curr_back->alfa:'BYN'))
<p class="title">{{trans('index.order.info')}} {{ $dirText ?? ''}}</p>
<ul class="flightInfo">
    <table class="table">
        <tbody>
        <tr>
            <td>{{trans('index.order.tickets_quantity')}}:</td>
            <td>{{ $order->count_places }}</td>
        </tr>
        <tr>
            <td>{{trans('index.schedules.departure')}}:</td>
            <td class="value js_time_from{{ $isReturn ? '_return' : ''}}">
                {{ \App\Services\Prettifier::prettifyDateTimeFull($order->from_date_time) }}
                @if (!$order->tour->route->is_transfer)
                    {{ $order->stationFrom->city->FullTimezone }}
                @endif
            </td>
        </tr>
        <tr>
            <td>{{trans('index.schedules.arrival')}}:</td>
            <td class="value js_time_to{{ $isReturn ? '_return' : ''}}">
                {{ \App\Services\Prettifier::prettifyDateTimeFull($order->to_date_time) }}
                @if (!$order->tour->route->is_transfer)
                    {{ $order->stationTo->city->FullTimezone }}
                @endif
            </td>
        </tr>
        <tr>
            <td>{{trans('index.profile.route')}}:</td>
            <td class="value">{{ $order->tour->route->name }}</td>
        </tr>
        </tbody>
    </table>



    <li><br></li>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script>
        if (typeof ymaps !== 'undefined') {
            ymaps.ready(init_from);
        }

        function init_from() {
            var suggestView = new ymaps.SuggestView('suggest_to');
            var suggestView2 = new ymaps.SuggestView('suggest_from');

            function change () {
                this.value.indexOf(this.defaultValue) && (this.value = this.defaultValue);
            }
            $("#suggest_to").on("input", change);
            $("#suggest_from").on("input", change);
        }
    </script>
    <li class="fieldStation">
        {{trans('index.home.landing_place')}}: {{$order->stationFrom->city->name}}
        @if($order->tour->route->is_taxi)
            <input type="hidden" name="is_new_stations" value="1">
            <div class="row">
                <input style="width: 90%; height: 33px; box-sizing: border-box; line-height: 32px; padding-left: 5px;"
                       autocomplete="address-line" type="text" id="suggest_from" class="input"
                       name="new_from_station" value="{{$order->StationFrom->city->name}}, ул."
                       placeholder="{{trans('index.order.enter_address')}}">
                <a class="js_map-from" style="cursor: pointer;"><i class="material-icons">&#xe55f;</i></a>
                <div id="map-from" city="{{$order->StationFrom->city->name}}" style="display: none; margin-top: 2%; width: 100%; height: 400px;"></div>
            </div>
        @elseif($order->tour->route->is_transfer)
            <input type="hidden" name="is_new_stations" value="0">
            {!! Form::hidden('station_from_id', session('order.station_from_id')) !!}
            <div class="unregisteredUserForm">
                @if($order->tour->route->flight_type == 'departure')
                    <div class="left field w-40">
                        <input name="address_from_street" value="{{ $order->address_from_street }}" type="text" placeholder="улица">
                    </div>
                    <div class="left field w-20">
                        <input name="address_from_house" value="{{ $order->address_from_house }}" type="text" placeholder="дом">
                    </div>
                    <div class="left field w-20">
                        <input name="address_from_building" value="{{ $order->address_from_building }}" type="text" placeholder="кор.">
                    </div>
                    <div class="left field w-20">
                        <input name="address_from_apart" value="{{ $order->address_from_apart }}" type="text" placeholder="п.">
                    </div>
                @else
                    <div class="left field w-100"><input name="custom_address_from" value="{{ $order->custom_address_from }}" type="text" id="custom_address_from" placeholder="информация о багаже"></div>
                @endif
            </div>
            <br />

        @else
            <input type="hidden" name="is_new_stations" value="0">
            <select onchange="this.blur();" style="width: 100%;" data-url="{{route('index.order.set_stations')}}" data-return="{{ $isReturn ?? 0 }}"
                    name="station_from_id{{ $isReturn ? '_return' : ''}}" class="select_place_from js_select_station_from">
                @foreach($stationsFrom as $street => $station)
                    <optgroup class="select_option_place_from" label="{{$street}}">
                        @foreach($station as $key=> $name_station)
                            <option @if ($key == session('order.station_from_id')) selected @endif
                            value="{{$key}}" class="select_option_place_from">{{$name_station}}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        @endif
    </li>
    <li><br></li>
    <li class="fieldStation">{{trans('index.home.disembarkation_place')}}: {{$order->stationTo->city->name}}
        @if($order->tour->route->is_taxi)
            <div class="row">
                <input style="width: 90%; height: 33px; box-sizing: border-box; line-height: 32px; padding-left: 5px;"
                       autocomplete="address-line" type="text" id="suggest_to" name="new_to_station"
                       class="input" value="{{$order->StationTo->city->name}}, ул."
                       placeholder="{{trans('index.order.enter_address')}}">
                <a class="js_map-to" style="cursor: pointer;"><i class="material-icons">&#xe55f;</i></a>
                <div id="map-to" city="{{$order->StationTo->city->name}}" style="display: none; margin-top: 2%; width: 100%; height: 400px;"></div>
            </div>
        @elseif($order->tour->route->is_transfer)
            <input type="hidden" name="is_new_stations" value="0">
            {!! Form::hidden('station_to_id', session('order.station_to_id')) !!}
            <div class="unregisteredUserForm">
                @if($order->tour->route->flight_type == 'arrival')
                    <div class="left field w-40">
                        <input name="address_to_street" value="{{ $order->address_to_street }}" type="text" placeholder="улица">
                    </div>
                    <div class="left field w-20">
                        <input name="address_to_house" value="{{ $order->address_to_house }}" type="text" placeholder="дом">
                    </div>
                    <div class="left field w-20">
                        <input name="address_to_building" value="{{ $order->address_to_building }}" type="text" placeholder="кор.">
                    </div>
                    <div class="left field w-20">
                        <input name="address_to_apart" value="{{ $order->address_to_apart }}" type="text" placeholder="п.">
                    </div>
                @else
                    <div class="left field w-100"><input name="custom_address_to" value="{{ $order->custom_address_to }}" type="text" placeholder="информация о багаже"></div>
                @endif
            </div>
            <br /><br />
        @else
            <select onchange="this.blur();" style="width: 100%;" data-url="{{route('index.order.set_stations')}}" data-return="{{ $isReturn ?? 0 }}"
                    name="station_to_id{{ $isReturn ? '_return' : ''}}" class="select_place_from js_select_station_to">
                @foreach($stationsTo as $street => $station)
                    <optgroup class="select_option_place_from" label="{{$street}}">
                        @foreach($station as $key=> $name_station)
                            <option @if($key == session('order.station_to_id')) selected @endif
                            value="{{$key}}" class="select_option_place_from">{{$name_station}}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        @endif
    </li>
</ul>
    @if(!($hideCountdown ?? false))
        <p id="countDown" class="placeNum"></p>
    @endif
    @if($order->places_with_number)
        <p class="placeNum">{{trans('index.order.seat')}} {{ $order->orderPlaces->implode('number', ', ') }}</p>
    @endif

    <ul class="infoForUser">
        @if(auth()->user() && auth()->user()->client)
            @if(auth()->user()->client->socialStatus)
                <li class="customerStatus">
                    <div class="pictureWrapper">
                        <div class="picture"></div>
                    </div>
                    <div class="inscriptionWrapper">
                        <span class="nameInscription">{{trans('index.profile.your_status')}}</span>
                    </div>
                    <div class="bottomInfoWrapper">
                        <div class="value">{{ auth()->user()->client->socialStatus->name }}</div>
                    </div>
                </li>
                <li class="discount">
                    <div class="pictureWrapper">
                        <div class="picture"></div>
                    </div>
                    <div class="inscriptionWrapper">
                        <span class="nameInscription">{{trans('index.profile.your_sale')}}</span>
                    </div>
                    <div class="bottomInfoWrapper">
                        @if (auth()->user()->client->socialStatus->is_percent > 0)
                            <td><span class="font-bold">{{ auth()->user()->client->socialStatus->percent }}%</span></td>
                        @else
                            <td><span class="font-bold">{{ auth()->user()->client->socialStatus->value }} {{ trans('admin_labels.currencies_short.'.$currency) }}</span></td>
                        @endif
                    </div>
                </li>
            @endif
        @endif
        <br style="clear: both"/>
    </ul>