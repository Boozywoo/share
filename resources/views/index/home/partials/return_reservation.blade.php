
<div class="reservationsWindWrapp js-return-ticket d-none" style="padding-top: 30px; padding-bottom: 50px">
    {!! Form::open(['route' => 'index.schedules.index', 'class' => '', 'id' => 'reservations', 'method' => 'GET']) !!}
    <div class="reservRoundWrapp">
        <div class="reservRound">Обратно</div>
    </div>
    <input type="hidden" name="station_from_id" value="0">
    <input type="hidden" name="station_to_id" value="0">
    <input type="hidden" name="return_ticket" value="1">
    <div class="visModWrapp">
        {!! Form::text('return-from', '', ['class' => 'js_return-from-city', 'readonly', 'id' => 'return-from']) !!}
        {!! Form::hidden('city_from_id', '', ['id' => 'return_city_from_id']) !!}
    </div>
    <div class="visModWrapp">
        {!! Form::text('return-to', '', ['class' => 'js_return-city', 'readonly', 'id' => 'return-to']) !!}
        {!! Form::hidden('city_to_id', '', ['id' => 'return_city_to_id']) !!}
    </div>
    <div class="withVerCentEl right">
        <a href="#" data-url="{{route('index.schedules.index')}}" class="makeAReservation js_reservation-return-button" style="line-height: 20px; padding-top: 7px;">
            {{trans('index.home.find') }}<br>
            <sub>обратный билет</sub>
        </a>
    </div>
    <div>
        {{--<div class="buttsWrapper">
            <a class="reservatButon today active" data-return="1" data-val="{{ \Carbon\Carbon::now()->format('d.m.Y') }}">{{trans('index.home.today')}}</a>
            <a class="reservatButon tomorrow" data-return="1" data-val="{{ \Carbon\Carbon::now()->addDay()->format('d.m.Y') }}">{{trans('index.home.tomorrow')}}</a>
        </div>--}}
        <div class="visModWrapp forDate js_fordate2">
            {!! Form::text('date', \Carbon\Carbon::now()->format('d.m.Y'), ['class' => 'js_date-pick js_date-pick-return', 'readonly', 'data-date-start-date' => 'd']) !!}
            <p class="additImageBlock">
                <a></a>
            </p>
        </div>
    </div>
    {!! Form::close() !!}
</div>


@push('scripts')
    <script>

        $(document).ready(function () {
            $('.js_city_from_id, .js_city_to_id').trigger('change');
        });

        $('#return_flag').on('change',  function () {
            $('.js-return-ticket, .scheduleBlockReturn').fadeToggle();
            $('.reservRound').eq(0).text(this.checked ? 'Туда' : 'Бронь');
        });

        $('.js_city_from_id, .js_city_to_id').on('change', function () {
            if ($('#city_from_id').val()) {
                $('#return-to').val($("#city_from_id option:selected").text());
                $('#return_city_to_id').val($("#city_from_id").val());
            } else {
                $('#return-to').val('{{ trans('index.home.disembarkation_city') }}');
                $('#return_city_to_id').val('');
            }
            if (parseInt($('#city_to_id').val()) > 0 && $('#city_from_id').val()) {
                $('#return-from').val($("#city_to_id option:selected").text());
                $('#return_city_from_id').val($("#city_to_id").val());
            } else {
                $('#return-from').val('{{ trans('index.home.landing_city') }}');
                $('#return_city_from_id').val('');
            }
        });

        $('#return-to').on('focus', function(e) {
            $('#city_from_id').fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
            $('#city_from_id').focus();
        });

        $('#return-from').on('focus', function(e) {
            $('#city_to_id').fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
            $('#city_to_id').focus();
        });

    </script>
@endpush
