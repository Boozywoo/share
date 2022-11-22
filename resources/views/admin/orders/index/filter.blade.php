{!! Form::open(['class' => 'js_table-search text-center', 'method' => 'get']) !!}
    {!! Form::panelRadios('which_date', trans('admin_labels.calendars'), 'tours_date') !!}
    <div class="form-group dib">
        <div class="js_datepicker" data-date-start-date="-" data-date="{{ request('date') ? request('date') : Carbon\Carbon::now()->format('d.m.Y') }}">
    </div>
        {!! Form::hidden('date', request('date') ? request('date') : Carbon\Carbon::now()->format('d.m.Y'), ['class' => 'form-control js_table-reset-no']) !!}</div>
    {{--<div class="input-group js_table-reset-no">
        {!! Form::text('time_from', request('time_from', Carbon\Carbon::now()->startOfDay()->format('H:i')), ['class' => 'input-sm form-control js_table-reset-no time-mask']) !!}
        <span class="input-group-addon">{{trans('admin.filter.to')}}</span>
        {!! Form::text('time_to', request('time_to', Carbon\Carbon::now()->endOfDay()->format('H:i')), ['class' => 'input-sm form-control js_table-reset-no time-mask']) !!}
    </div>--}}
    <div class="form-group">
    {!! Form::text('id', request('id'), ['placeholder' => trans('admin.orders.enter_num'),'class' => "form-control"]) !!}
</div>
@php($routes->prepend(trans('admin.filter.all'),0))
{!! Form::panelRadios('route_id', $routes) !!}
<div class="form-group"> {!! Form::select('bus_id', $buses, request('bus_id'), ['placeholder' => trans('admin.orders.sel_bus'), 'class' => "form-control"]) !!}
</div>
<div class="form-group">
    {!! Form::text('phone', request('phone'), ['placeholder' => trans('admin.clients.enter_tel'),'class' => "form-control"]) !!}
</div>  
<div class="form-group">
    {!! Form::select('status', trans('admin.orders.statuses'), request('status'), ['placeholder' => trans('admin.buses.sel_status'),'class' => "form-control"]) !!}
</div> 
<div class="form-group">
    {!! Form::select('type_pay', trans('admin.orders.pay_types'), request('type_pay'), ['placeholder' => trans('admin.buses.sel_status'),'class' => "form-control"]) !!}
</div><!-- <button type="submit" class="btn btn-warning"><span class="fa fa-filter"></span> {{trans('admin.filter.find')}}</button> -->
<a href="{{ route('admin.orders.index') }}" class="btn btn-default js_table-reset"><span class="fa fa-ban"></span> {{trans('admin.filter.clear')}}</a>
{!! Form::close() !!}