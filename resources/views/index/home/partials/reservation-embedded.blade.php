<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Embed reservation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">

    @include('index.home.partials.thems-styles')
    <link rel="stylesheet" href="{{ asset('assets/index/css/template.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/index/css/secondStylesFile.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/index/css/orderPage.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/index/css/shedulePage.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/index/css/personalCabinet.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/index/css/thirdStylesFile.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/index/css/changeBusOrientation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/index/css/style.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/index/css/embedded-form.css') }}">
    <style>
        
        @media screen and (min-width: 550px) {
            form{
                display: flex !important;
                justify-content: center !important;
            }
        }
        @media screen and (max-width: 550px) {
            .visModWrapp, .withVerCentEl, .makeAReservation {
                width: 100% !important;
            }
            .visModWrapp{
                margin-bottom: 1em !important;
            }
        }
    </style>
</head>
<body>
<div class="reservationsWindWrapp reservationsWindWrapp_embedded">
    <h2 class="text-center" style="color: rgb(42, 101, 146)"></h2>
    {!! Form::open(['route' => 'index.schedules.index', 'class' => 'js-embed-form', 'id' => 'reservations', 'method' => 'GET']) !!}
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
        </div>
        <div class="visModWrapp forDate js_fordate">
            {!! Form::text('date', \Carbon\Carbon::now()->format('d.m.Y'), ['class' => 'js_date-pick', 'readonly', 'data-date-start-date' => 'd']) !!}
            <p class="additImageBlock">
                <a></a>
            </p>
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
</div>

    @php($timeHash = time())
    <script src="{{ asset('assets/index/js/markup/jquery-2.2.4.min.js') }}"></script>
    <script src="{{ asset('assets/index/js/markup/shedulePage.js?'.$timeHash) }}"></script>
    <script src="{{ asset('assets/index/js/main.js?'.$timeHash) }}"></script>
    <script src="{{ asset('assets/index/js/app.js?'.$timeHash) }}"></script>

    <script>
        function sendEmbedReservationForm() {
            var $form = document.querySelector('.js-embed-form');

            if ($form) {
                var from = $form.city_from_id.value;
                var to = $form.city_to_id.value;
                var return_flag = $form.return_flag ? $form.return_flag.value : '';
                var date = $form.querySelector('.js_date-pick').value;
                var places = $form.places.value;

                var newLink = '{{ url('/') }}/?from_embed_form=1&from=' + from +  '&to=' + to + '&date=' + date + '&places=' + places;

                window.open(newLink,"_top");
            }
        }
    </script>
</body>
</html>
