{!! Form::open(['class' => 'form-inline js_table-search', 'method' => 'get']) !!}
    {!! Form::hidden('mass_price_update', 0, ['id' => 'mass_price_update', 'disabled' => 'disabled']) !!}
    {!! Form::hidden('page', request('page') ?? 1, ['id' => 'js-current-page']) !!}
    <div class="form-group">
        {!! Form::text('id', request('id'), ['placeholder' => trans('admin.buses.enter_num'),
            'class' => "form-control"]) !!}
    </div>
    <div class="form-group">
        {!! Form::text('flight_ac_code', request('flight_ac_code'), ['placeholder' => trans('admin_labels.flight_ac_code'),
            'class' => "form-control"]) !!}
    </div>
    <div class="form-group">
        {!! Form::text('flight_number', request('flight_number'), ['placeholder' => trans('admin_labels.flight_number'),
            'class' => "form-control"]) !!}
    </div>
    <div class="form-group">
        {!! Form::select('flight_type', trans('admin.schedules.flight_types'), request('flight_type'), 
            ['class' => "form-control"]) !!}
    </div>
    <div class="form-group">
        {!! Form::select('bus_id', $buses, request('bus_id'), [
            'class' => "form-control"]) !!}
    </div>
    <div class="form-group">
        {!! Form::select('route_id', $routes, request('route_id'), ['placeholder' => trans('admin.orders.sel_route'), 
            'class' => "form-control"]) !!}
    </div>
    <div class="form-group">
        {!! Form::select('status', trans('admin.schedules.statuses'), request('status'), ['placeholder' => trans('admin.buses.sel_status'), 
            'class' => "form-control"]) !!}
    </div>
    {{--<button type="submit" class="btn btn-warning"><span class="fa fa-filter"></span> {{trans('admin.filter.find')}}</button>--}}
    <a href="{{ route('admin.users.index') }}" class="btn btn-default js_table-reset"><span class="fa fa-ban"></span> {{trans('admin.filter.clear')}}</a>
{!! Form::close() !!}