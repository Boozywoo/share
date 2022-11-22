{!! Form::open(['class' => 'form-inline js_table-search', 'method' => 'get']) !!}
    <div class="form-group">
        {!! Form::text('name', request('name'), ['placeholder' => trans('admin.buses.enter_name'),'class' => "form-control"]) !!}
    </div>
    <div class="form-group">
        {!! Form::select('status', trans('admin.companies.statuses'), request('status'), ['placeholder' => trans('admin.buses.sel_status'),'class' => "form-control"]) !!}
    </div>
    <div class="form-group">
        {!! Form::select('company_id', $companies, request('company_id'), ['placeholder' => trans('admin.buses.sel_company'),'class' => "form-control"]) !!}
    </div>
    <div class="form-group">
        {!! Form::select('route_id', $routes, request('company_id'), ['placeholder' => trans('admin.buses.sel_routes'),'class' => "form-control"]) !!}
    </div>
    <div class="form-group">
        <div class="input-daterange input-group js_table-reset-no" id="datepicker">
            {!! Form::text('date_from', request('date_from', Carbon\Carbon::now()->subMonths(1)->format('Y-m-d')), ['class' => "input-sm form-control js_table-reset-no", 'readonly']) !!}
            <span class="input-group-addon">{{trans('admin.filter.to')}}</span>
            {!! Form::text('date_to', request('date_to', Carbon\Carbon::now()->format('Y-m-d')), ['class' => "input-sm form-control js_table-reset-no", 'readonly']) !!}
        </div>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-xs btn-default m-t-xs js_table-reset"><span class="fa fa-ban"></span> {{trans('admin.filter.clear')}}</a>
    <button type="submit" class="btn btn-xs m-t-xs  btn-warning"><span class="fa fa-filter"></span> {{trans('admin.auth.apply')}}</button>
{!! Form::close() !!}