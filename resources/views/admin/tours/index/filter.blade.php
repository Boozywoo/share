<style>
    .selection > .select2-selection {
        border-radius: 0px !important;
        height: 35px;
    }
</style>
{!! Form::open(['class' => 'js_table-search text-center', 'method' => 'get']) !!}
@if (count($routeTypes) > 1)
    <div class="form-group">
        @foreach ($routeTypes as $rType)
            {{ Form::checkbox($rType, 1, request($rType), ['class' => 'js_checkbox', 'id' => 'type-'. $rType]) }}
            {{ Form::label('type-'. $rType, trans('admin_labels.regular_transfer.'.$rType), ['class' => 'text-weight']) }} &nbsp;&nbsp;&nbsp;&nbsp;
        @endforeach
    </div>
@endif
<div class="form-group dib">
    <div class="js_datepicker" data-date-start-date="-" data-date="{{ request('date') ?? request('from') ?? Carbon\Carbon::now()->format('d.m.Y') }}"></div>
    {!! Form::hidden('date', request('date') ?? request('from') ?? Carbon\Carbon::now()->format('d.m.Y'), ['class' => 'form-control js_table-reset-no']) !!} 
    {!! Form::hidden('incomming_phone', request('incomming_phone')?request('incomming_phone'):'' , ['class' => 'form-control js_table-reset-no']) !!}
    {!! Form::hidden('order_return', request('order_return')?request('order_return'):'' , ['class' => 'form-control js_table-reset-no']) !!}
    {!! Form::hidden('all_dates', 0 , [ 'id' => 'all_dates', 'disabled' => 'disabled']) !!}
    {!! Form::hidden('only_visible', 0 , [ 'id' => 'only_visible', 'disabled' => 'disabled']) !!}
    {!! Form::hidden('mass_price_update', 0 , ['id' => 'mass_price_update', 'disabled' => 'disabled']) !!}
</div>

<div class="form-group">
    <div class="col-xs-6">
        {!! Form::text('from', request('from'), ['class' => 'form-control js_datepicker ', 'placeholder' => 'Начальная дата', 'data-date-clear-btn' => '1']) !!}
            </div>
    <div class="col-xs-6">
        {!! Form::text('to', request('to'), ['class' => 'form-control js_datepicker', 'placeholder' => 'Конечная дата', 'data-date-clear-btn' => '1']) !!}
    </div>
</div><br /><br />

<div class="form-group">
    <div class="col-xs-6">
        {!! Form::select('city_from_id', $cities, request('city_from_id'),
        [
        'placeholder' => trans('admin.orders.from'),
        'class' => "form-control js_city_from_id",
        'data-url' => route('admin.tours.get_cities')
        ]) !!}
    </div>
    <div class="col-xs-6">
        {!! Form::select('city_to_id', $cities, request('city_to_id'),
        [
        'placeholder' => trans('admin.orders.to'),
        'class' => "form-control js_city_to_id",
        ]) !!}
    </div>
    <div style="padding-top: 5%" class="col-xs-12">
        @php($routes->prepend(trans('admin.filter.all'),0))
        {!! Form::panelRadios('route_id', $routes) !!}
    </div>
</div>
<div class="form-group">
    <select name="status" class="form-control">
        <option value="">{{ trans('admin.buses.sel_status') }}</option>
        <option value="active_all" @if (\request()->get('status') == 'active_all')selected @endif>{{ trans('admin.tours.active_all') }}</option>
        @foreach(trans('admin.tours.statuses') as $key => $item)
            <option value="{{ $key }}"{{ $key == request('status') ? ' selected="selected"' : '' }}>{{ $item }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    {!! Form::select('bus_id', $buses, request('bus_id'), ['class' => "form-control js-select-search-tours", 'data-allow-clear' => 'true', 'data-placeholder' => trans('admin.clients.sel_bus')]) !!}
</div>
<div class="form-group">
    {!! Form::select('driver_id', $drivers, request('driver_id'), ['class' => "form-control js-select-search-tours", 'data-allow-clear'=>'true', 'data-placeholder' => trans('admin.clients.sel_driver')]) !!}
</div>

<div class="form-group">
    {!! Form::select('have_orders', $haveOrders, request('have_orders'), ['class' => "form-control"]) !!}
</div>

<div class="form-group">
    {!! Form::select('visible', $visible, request('visible'), ['class' => "form-control"]) !!}
</div>

@if ($companies->count() > 2) 
    <div class="form-group">
        {!! Form::select('company', $companies, request('company'), ['class' => "form-control js-select-company"]) !!}
    </div>
@endif


{!! Form::close() !!}

