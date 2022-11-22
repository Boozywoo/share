<div class="reservationsWindWrapp">
    {!! Form::open(['route' => 'index.schedules.index', 'class' => '', 'id' => 'reservations', 'method' => 'GET']) !!}
        <div class="reservRoundWrapp">
            <div class="reservRound">{{trans('index.home.booking')}}</div>
        </div>
        <input type="hidden" name="station_from_id" value="0">
        <input type="hidden" name="station_to_id" value="0">
        <div class="visModWrapp">
            {!! Form::select('city_from_id', $cities, null,
            ['placeholder' => trans('index.home.landing_city'),
            'class' => 'js_city_from_id',
            'data-url' => route('index.cities'),
            'data-station_url' => route('index.cityStations'),
            'id' => 'city_from_id'
            ]) !!}
        </div>
        <div class="visModWrapp">
            {!! Form::select('city_to_id', [], null,
            ['placeholder' => trans('index.home.disembarkation_city'),
            'class' => 'js_city_to_id', 'disabled' => 'disabled',
            'data-station_url' => route('index.cityStations'),
            'data-get_route_url' => route('index.get_route'),
            'id' => 'city_to_id'
            ]) !!}
            <div class="js_bus-overlay small js_city_to"></div>
        </div>
        <div class="visModWrapp forDate js_fordate">
            {!! Form::text('date', \Carbon\Carbon::now()->format('d.m.Y'), ['class' => 'js_date-pick', 'readonly', 'data-date-start-date' => 'd']) !!}
            <p class="additImageBlock">
                <a></a>
            </p>
            <div class="js_bus-overlay small tgk"></div>
        </div>
        <div class="visModWrapp">
            {!! Form::select('places', ['1' => '1 место', '2' => '2 места', '3' => '3 места', '4' => '4 места', '5' => '5 мест', '6' => '6 мест'], 1,
                ['id' => 'places-count', 'class' => '']) !!}
        </div>
        {{--<div class="buttsWrapper">
            <a class="reservatButon today active mr-2" data-val="{{ \Carbon\Carbon::now()->format('d.m.Y') }}">{{trans('index.home.today')}}</a>
            <a class="reservatButon tomorrow" data-val="{{ \Carbon\Carbon::now()->addDay()->format('d.m.Y') }}">{{trans('index.home.tomorrow')}}</a>
        </div>--}}
        @if (env('RETURN_TICKET'))
        <div class="visModWrapp">
            <select name="return-select" id="return_flag">
                <option value="0">Туда</option>
                <option value="1">Туда и обратно</option>
            </select>
            </div>
        @endif
        <div class="withVerCentEl">
            <a href="#" data-url="{{route('index.schedules.index')}}" class="makeAReservation js_reservation-button" disabled="disabled">
                {{trans('index.home.find')}}
            </a>
        </div>

    <br style="clear: both;">

    {!! Form::close() !!}

    @if(!empty(\App\Models\Setting::all()->pluck('field_popup_window')->first()))
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

        <div class="w3-container">
        <div id="modalText" class="w3-modal">
            <div class="w3-modal-content"  style="border-radius: 10px;">
            <div class="w3-container">
                <span onclick="document.getElementById('modalText').style.display='none'" class="w3-button w3-display-topright" style="border-radius: 10px;">
                    &times;
                </span>
                <h2>Внимание!</h2>
                <hr>
                <p>{{\App\Models\Setting::all()->pluck('field_popup_window')->first()}}</p>
                <br>
            </div>
            </div>
        </div>
    </div>

    @endif
    
   @if(!empty(\App\Models\Setting::all()->pluck('field_popup_window')->first()))
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
        <script type="text/javascript">
        
            $(document).ready(function() {
                setTimeout(function() {
                    document.getElementById('modalText').style.display='block';
                }, 1000);
            });
        </script>
   @endif

   
</div>
