@php($cityFrom = request('city_from_id') ? \App\Models\City::find(request('city_from_id'))->name : head(array_keys($stations)))
@php($stationsStart = $stations)
@php(array_pop($stationsStart))
@php($FirstCityStations = $stationsStart[$cityFrom])
@php(key($FirstCityStations))
@php($stationFromId = key($FirstCityStations))

@php($cityTo = request('city_to_id') ? \App\Models\City::find(request('city_to_id'))->name : last(array_keys($stations)))
@php($stationsFinish    = $stations)
@php(array_shift($stationsFinish))
@php($lastCityStations = $stationsFinish[$cityTo])
@php($stationToId = end($lastCityStations))
@php($stationToId = key($lastCityStations))

@php($stationFromId     = $order->station_from_id ? $order->station_from_id : $stationFromId )
@php($stationToId       = $order->station_to_id   ? $order->station_to_id : $stationToId)
@if ($tour->route->is_taxi)
    <input type="hidden" name="is_new_stations" value="0">
    <input type="hidden" name="city_from" value="{{$cityFrom}}">
    <input type="hidden" name="city_to" value="{{$cityTo}}">
    <script>
        if (typeof ymaps !== 'undefined') {
            ymaps.ready(init_from);
        }

        function init_from() {
            // Подключаем поисковые подсказки к полю ввода.
            var suggestView = new ymaps.SuggestView('suggest_to');
            var suggestView2 = new ymaps.SuggestView('suggest_from');
        }

        $(document).on('change', ".js-from-station input",  function () {
            let thisId = this.id;
            $('.from-inputs').not('#type-'+thisId).slideUp(400).find('input:text').prop('disabled', true);      // Remove custom address from Post query
            $('#type-'+thisId).slideDown(400).find('input:text').prop('disabled', false);
        })
        $(document).on('change', ".js-to-station input",  function () {
            let thisId = this.id;
            $('.to-inputs').not('#type-'+thisId).slideUp(400).slideUp(400).find('input:text').prop('disabled', true);
            $('#type-'+thisId).slideDown(400).find('input:text').prop('disabled', false);
        })

        $(document).ready(function () {
            @if (empty($order->custom_address_from))
                $('#from-station').trigger('click');
            @endif
            @if (empty($order->custom_address_to))
                $('#to-station').trigger('click');
            @endif
             
         });

    </script>
    @php($stationFromName = $cityFrom.', '.trans('admin.orders.street'))
    @php($stationToName = $cityTo.', '.trans('admin.orders.street'))
    <div style="clear:both" class="pt-10">
        <div class="col-sm-3 no-padding">
            {!! Form::label('suggest_from', trans('admin_labels.station_from_id'), ['class' => 'control-label clear']) !!}
            <div class="radio radio-warning radio-inline pt-5 js-from-station">
                {{ Form::radio('from_type', 'from-address', true, ['id' => 'from-address']) }}<label for="from-address">Адрес</label>
            </div>
            <div class="radio radio-danger radio-inline pt-5 js-from-station">
                {{ Form::radio('from_type', 'from-station', false, ['id' => 'from-station']) }}<label for="from-station">Объект</label>
            </div>
        </div>
        <div class="col-sm-9 no-padding from-inputs" id="type-from-address">
            {!! Form::label('', ' ', ['class' => 'control-label']) !!}
            {!! Form::text('custom_address_from', $order->custom_address_from ?? $stationFromName, ['class' => "form-control", 'id' => 'suggest_from']) !!}
            
        </div>
        <div class="col-sm-9 no-padding from-inputs" style='display: none' id="type-from-station">
            {!! Form::label('', ' ', ['class' => 'control-label']) !!}
            {!! Form::select('station_from_id', $stations, $stationFromId, ['class' => "form-control js-select-search-tours"]) !!}
        </div>
    </div>
    <div style="clear:both" class="pt-10">
        <div class="col-sm-3 no-padding">
            {!! Form::label('suggest_to', trans('admin_labels.station_to_id'), ['class' => 'control-label clear']) !!}
            <div class="radio radio-warning radio-inline pt-5 js-to-station">
                {{ Form::radio('to_type', 'to-address', true, ['id' => 'to-address']) }}<label for="to-address">Адрес</label>
            </div>
            <div class="radio radio-danger radio-inline pt-5 js-to-station">
                {{ Form::radio('to_type', 'to-station', false, ['id' => 'to-station']) }}<label for="to-station">Объект</label>
            </div>
        </div>
        <div class="col-sm-9 no-padding to-inputs" id="type-to-address">
            {!! Form::label('', ' ', ['class' => 'control-label']) !!}
            {!! Form::text('custom_address_to', $order->custom_address_to ?? $stationToName, ['class' => "form-control", 'id' => 'suggest_to']) !!}

        </div>
        <div class="col-sm-9 no-padding to-inputs" style='display: none' id="type-to-station">
            {!! Form::label('', ' ', ['class' => 'control-label']) !!}
            {!! Form::select('station_to_id', $stations, $stationToId, ['class' => "form-control js-select-search-tours"]) !!}
        </div>
    </div>
    <div class="col-sm-12 pt-10"></div>
@elseif ($tour->route->is_transfer)
    <div class="form-group">
        @if(mb_strpos(mb_strtolower($cityFrom), 'аэропорт') === false)
            {!! Form::hidden('station_to_id', $stationToId, ['id' => 'station_to_id']) !!}
            {!! Form::panelSelect('station_from_id', $stationsTicketsFrom, $stationFromId,
                [
                    'class'              =>"form-control js_set_station_from",
                    'data-url'           => route('admin.orders.select_station_to_id'),
                    'data-route_id'      => $tour->route->id,
                    'data-station_to_id' => $stationToId
                ], false) !!}
            <div class="row">
                <div class="col-xs-5">
                    <input class="form-control js_transfer_street" name="address_from_street" value="{{ $order->address_from_street }}" type="text" placeholder="улица">
                </div>
                <div class="col-xs-2">
                    <input class="form-control js_transfer_house" name="address_from_house" value="{{ $order->address_from_house }}" type="text" placeholder="дом">
                </div>
                <div class="col-xs-2">
                    <input class="form-control js_transfer_building" name="address_from_building" value="{{ $order->address_from_building }}" type="text" placeholder="кор.">
                </div>
                <div class="col-xs-2">
                    <input class="form-control js_transfer_apart" name="address_from_apart" value="{{ $order->address_from_apart }}" type="text" placeholder="п.">
                </div>
            </div>
        @else
            <label for="custom_address_from" class="control-label">{{ trans('admin_labels.station_from_id').': '.$cityFrom }}</label>
            <input class="form-control blackBg lightFont" name="custom_address_from" value="{{ $order->custom_address_from }}" type="text" id="custom_address_from" placeholder="терминал">
        @endif
        <p class="error-block"></p>

    </div>

    <div class="form-group">
        @if (mb_strpos(mb_strtolower($cityTo), 'аэропорт') === false)
            {!! Form::hidden('station_from_id', $stationFromId, ['id' => 'station_from_id']) !!}
            {!! Form::panelSelect('station_to_id', $stationsTicketsTo, $stationToId,
                    ['class' => "form-control js_station_to_filter"], false) !!}
            <div class="row">
                <div class="col-xs-5">
                    <input class="form-control js_transfer_street" name="address_to_street" value="{{ $order->address_to_street }}" type="text" placeholder="улица">
                </div>
                <div class="col-xs-2">
                    <input class="form-control js_transfer_house" name="address_to_house" value="{{ $order->address_to_house }}" type="text" placeholder="дом">
                </div>
                <div class="col-xs-2">
                    <input class="form-control js_transfer_building" name="address_to_building" value="{{ $order->address_to_building }}" type="text" placeholder="кор.">
                </div>
                <div class="col-xs-2">
                    <input class="form-control js_transfer_apart" name="address_to_apart" value="{{ $order->address_to_apart }}" type="text" placeholder="п.">
                </div>
            </div>
        @else
            <label for="custom_address_to" class="control-label">{{ trans('admin_labels.station_to_id').': '.$cityTo }}</label>
            <input class="form-control" name="custom_address_to" value="{{ $order->custom_address_to }}" type="text" id="custom_address_to" placeholder="терминал">
        @endif
        <p class="error-block"></p>

    </div>

@else
    {!! Form::panelSelect('station_from_id', $stationsStart, $stationFromId,
                    [
                        'class'              =>"form-control js_set_station_from",
                        'data-url'           => route('admin.orders.select_station_to_id'),
                        'data-route_id'      => $tour->route->id,
                        'data-station_to_id' => $stationToId,
                        'data-toggle'        => "tooltip",
                        'title'              => "Введите сначала данные клиента"
                    ], false) !!}
    <div class="js_set_station_to">
        @if ($order->id || request('city_from_id'))
            @php($temp = $stationsFinish)
            @foreach($stationsFinish as $key => $station)
                @unset($stationsFinish[$key])
                @if (key($station) == $stationFromId)
                    @break
                @endif
            @endforeach
            @if (empty($stationsFinish))
                @php($stationsFinish = $temp)
            @endif
        @endif
        {!! Form::panelSelect('station_to_id', $stationsFinish, $stationToId,
             ['class' => "form-control js_station_to_filter", 'data-toggle' => "tooltip", 'title' => "Введите сначала данные клиента"], false) !!}
    </div>
    
@endif
@if(!Auth::user()->isAgent && $order->type_pay != 'success')  {{-- Если тип юзера - Посредник (isMediator), то убираем возможность поставить 'Оплачено онлайн' --}}
        {!! Form::panelSelect('type_pay', Auth::user()->isMediator ? array_diff_key(trans('admin.orders.pay_types'), array_flip([\App\Models\Order::TYPE_PAY_SUCCESS])) : trans('admin.orders.pay_types'), $order ? $order->type_pay : null,
                        ['class' => "form-control"], false) !!}
@endif
    