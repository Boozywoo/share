<div class="js_flight-data" @if ($schedule->route && !$schedule->route->is_transfer)style="display: none"@endif>
    <div class="form-group">
        <label for="flight-number" id="to-flight" class="col-md-4">{{ trans('admin_labels.tour_id') }}</label>
        <div class="row">
            <div class="col-xs-3">
                <input class="form-control" name="flight_ac_code" value="{{ $schedule->flight_ac_code }}" type="text" placeholder="код авиакомпании">
            </div>
            <div class="col-xs-4">
                <input class="form-control" name="flight_number" value="{{ $schedule->flight_number }}" type="text" placeholder="номер рейса" id="flight-number">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="flight-time" class="col-md-4">{{ trans('index.home.time') }}</label>
        <div class="row">
            <div class="col-xs-3">
                <input class="form-control time-mask" name="flight_time" value="{{ $schedule->flight_time }}" type="text" placeholder="время рейса самолета" id="flight-time">
            </div>
            <div class="col-xs-4">
                <input class="form-control time-mask" name="flight_offset" value="{{ $schedule->flight_offset }}" type="text" data-interval="0" placeholder="время сдвига" id="flight-offset">
            </div>
        </div>
    </div>
</div>