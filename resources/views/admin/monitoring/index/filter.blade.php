{!! Form::open(['class' => 'form-inline js_map-filter', 'method' => 'get']) !!}
<div class="form-group">
    {!! Form::select('bus_id', $buses, request('bus_id'), ['class' => "form-control",  'id' => 'bus', 'onchange' => 'onBusChange()']) !!}
</div>
<div class="form-group">
    {!! Form::select('route_id', $routes, request('route_id'), ['placeholder' => trans('admin.orders.sel_route'), 'class' => "form-control",  'id' => 'route']) !!}
</div>
<div class="form-group">
    {!! Form::select('tour_id', $times, request('tour_id'), ['class' => "form-control", 'id' => 'time']) !!}
</div>
{{--<button type="submit" class="btn btn-warning"><span class="fa fa-filter"></span> {{trans('admin.filter.find')}}</button>--}}
<a href="{{ route('admin.users.index') }}" class="btn btn-default js_table-reset" onclick="deleteAll"><span class="fa fa-ban"></span> {{trans('admin.filter.clear')}}</a>
{!! Form::close() !!}
{!! Form::open(['class' => 'form-inline js_set-high-speed', 'method' => 'post']) !!}
<div class="form-group">
<br>
{!! Form::text('high_speed', request('high_speed'), ['placeholder' => $highSpeed, 'class' => "form-control"]) !!}
<input id="setSpeed" type="button" class="btn btn-warning" value="{{trans('admin.filter.save')}}"/>
{!! Form::text('speed', null, ['placeholder' => 'Скорость автобуса', 'class' => "form-control", 'readonly' => 'true']) !!}
</div>
{!! Form::close() !!}