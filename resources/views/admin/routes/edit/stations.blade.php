<div class="js-sortable-station js_multiple-wrapper">
    @php($i = 0)
    <p class="text-shadow">{{ trans('admin.routes.time') }}</p>
    @foreach($route->stations as $station)
        {{--trans('admin.routes.except_for_taxi')--}}
        @if(($route->is_taxi && $station->status != \App\Models\Station::STATUS_ACTIVE) )@continue  @endif
        {{--trans('admin.routes.except_for_taxi')--}}
        <div class="row js_reindex-stations js_multiple-row form-inline m-b-xs" data-name="stations">
            <div class="col-md-12">
                <div class="input-group input-group-sm">
                    <span class="input-group-btn"><span class="btn btn-default js_multiple-order"><i class="fa fa-bars"></i></span></span>
                    {!! Form::select('stations['. $i .'][station_id]', $stations, $station->id, ['class' => "form-control js_stations-station_id"]) !!}
                </div>
                <div class="input-group input-group-sm w-14">
                    <span class="input-group-btn"><span class="btn btn-default"><i class="fa fa-clock-o"></i> {{ trans('admin.routes.min')}}</span></span>
                    {!! Form::text('stations['. $i .'][time]', $station->pivot->time, ['class' => "form-control js_stations-time"]) !!}
                </div>
                <div class="input-group input-group-sm w-14">
                    <span class="input-group-btn"><span class="btn btn-default"><i class="fa fa-money"></i> {{ trans('admin.routes.boarding')}}</span></span>
                    {!! Form::text('stations['. $i .'][cost_start]', $station->pivot->cost_start, ['class' => "form-control js_stations-cost_start"]) !!}
                </div>
                <div class="input-group input-group-sm w-14">
                    <span class="input-group-btn"><span class="btn btn-default"><i class="fa fa-money"></i> {{ trans('admin.routes.disembarkation')}}</span></span>
                    {!! Form::text('stations['. $i .'][cost_finish]', $station->pivot->cost_finish, ['class' => "form-control js_stations-cost_finish"]) !!}
                </div>
                <div class="input-group input-group-sm">
                    <!--<span class="input-group-btn"><span class="btn btn-default"><i class="fa fa-star"></i> {{ trans('admin.routes.central')}}</span></span>
                    <span class="input-group-btn"><span class="btn btn-default"><i class="fa fa-star"></i> {{ trans('admin.routes.central')}}</span></span>
                    <div style="padding-left: 5px;padding-bottom: 7px" class="checkbox">
                        <input name="stations[{{$i}}][central]" type="hidden" value="0">
                        <input class="checkbox" @if($station->pivot->central) checked @endif name="stations[{{$i}}][central]" type="checkbox" value="1">
                        <label for="stations[{{$i}}][central]"></label>
                    </div> -->
                    <span class="input-group-btn"><span class="btn btn-default"><i class="fa fa-star"></i> {{ trans('admin.routes.tickets_from')}}</span></span>
                    <div style="padding-left: 5px;padding-bottom: 7px" class="checkbox">
                        <input class="checkbox js_stations-tickets_from" @if($station->pivot->tickets_from) checked @endif name="stations[{{$i}}][tickets_from]" type="checkbox" value="1" id="stations-tickets-{{$i}}">
                        <label for="stations-tickets-{{$i}}"></label>
                    </div>
                    &nbsp;<span class="input-group-btn"><span class="btn btn-default"><i class="fa fa-star"></i> {{ trans('admin.routes.tickets_to')}}</span></span>
                    <div style="padding-left: 5px;padding-bottom: 7px" class="checkbox">
                        <input class="checkbox js_stations-tickets_to" @if($station->pivot->tickets_to) checked @endif name="stations[{{$i}}][tickets_to]" type="checkbox" value="1" id="stations-tickets-to-{{$i}}">
                        <label for="stations-tickets-to-{{$i}}"></label>
                    </div>
                    <span class="input-group-btn">
                        <span class="btn btn-default js_multiple-remove" data-name="stations" type="button">
                            <i class="fa fa-minus"></i>
                        </span>
                    </span>

                </div>
            </div>
        </div>
        @php($i++)
    @endforeach
    <div class="row js_multiple-row js_multiple-row-clone js_reindex-stations form-inline m-b-xs"
         data-name="stations">
        <div class="col-md-12">
            <div class="input-group input-group-sm">
                        <span class="input-group-btn"><span class="btn btn-default js_multiple-order"><i
                                        class="fa fa-bars"></i></span></span>
                {!! Form::select(null, $stations, null, ['class' => 'form-control js_stations-station_id', 'disabled']) !!}
            </div>
            <div class="input-group input-group-sm">
                        <span class="input-group-btn"><span class="btn btn-default"><i
                                        class="fa fa-clock-o"></i> {{trans('admin.routes.min')}}</span></span>
                {!! Form::text(null, null, ['class' => 'form-control js_stations-time', 'disabled']) !!}
            </div>
            <div class="input-group input-group-sm">
                <span class="input-group-btn"><span class="btn btn-default"><i class="fa fa-money"></i> {{ trans('admin.routes.boarding')}}</span></span>
                {!! Form::text(null, null, ['class' => "form-control js_stations-cost_start", 'disabled']) !!}
            </div>
            <div class="input-group input-group-sm">
                <span class="input-group-btn"><span class="btn btn-default"><i class="fa fa-money"></i> {{ trans('admin.routes.disembarkation')}}</span></span>
                {!! Form::text(null, null, ['class' => 'form-control js_stations-cost_finish', 'disabled']) !!}
            </div>
            <div class="input-group input-group-sm">
                <span class="input-group-btn"><span class="btn btn-default"><i class="fa fa-star"></i> {{ trans('admin.routes.tickets_from')}}</span></span>
                <div style="padding-left: 5px;padding-bottom: 7px" class="checkbox">
                    <input class="checkbox js_stations-tickets_from" checked type="checkbox" value="1">
                    <label></label>
                </div>
                &nbsp;<span class="input-group-btn"><span class="btn btn-default"><i class="fa fa-star"></i> {{ trans('admin.routes.tickets_to')}}</span></span>
                <div style="padding-left: 5px;padding-bottom: 7px" class="checkbox">
                    <input class="checkbox js_stations-tickets_to" checked type="checkbox" value="1">
                    <label></label>
                </div>
                <span class="input-group-btn">
                    <span class="btn btn-default js_multiple-remove" data-name="stations" type="button"><i class="fa fa-minus"></i></span>
                </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 m-b-md">
            <p class="text-warning">
                {{ trans('admin.routes.sum')}}
            </p>
            <button class="btn btn-default btn-sm js_multiple-add" data-name="stations" type="button"><i
                        class="fa fa-plus"></i> {{ trans('admin.routes.add')}}
            </button>
        </div>
    </div>
</div>
