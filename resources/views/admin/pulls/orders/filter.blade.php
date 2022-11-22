{!! Form::open(['class' => 'form-inline js_table-search', 'method' => 'get']) !!}
    <div class="input-group">
        <span class="input-group-btn"><span class="btn btn-default"><i class="fa fa-calendar"></i></span></span>
        {!! Form::text('date', request('date') ? request('date') : '', ['class' => "form-control js_table-reset-no js_datepicker", 'readonly']) !!}
    </div>
    <div class="form-group">
        {!! Form::select('route_id', $routes, request('route_id'), ['placeholder' => trans('admin.orders.sel_route'), 'class' => "form-control"]) !!}
    </div>
    <div class="form-group">
        {!! Form::select('status', trans('admin.drivers.statuses'), request('status'), ['placeholder' => trans('admin.buses.sel_status'),'class' => "form-control"]) !!}
    </div>
    {{--<button type="submit" class="btn btn-warning"><span class="fa fa-filter"></span> {{trans('admin.filter.find')}}</button>--}}
    <a href="{{ route('admin.users.index') }}" class="btn btn-default js_table-reset"><span class="fa fa-ban"></span> {{trans('admin.filter.clear')}}</a>
{!! Form::close() !!}