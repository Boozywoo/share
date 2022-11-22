{!! Form::open(['class' => 'form-inline js_table-search', 'method' => 'get']) !!}
<div class="form-group">
    <div class="input-daterange input-group js_table-reset-no" id="datepicker">
        {!! Form::text('date_from', request('date_from', Carbon\Carbon::now()->subMonths(1)->format('Y-m-d')), ['class' => "input-sm form-control js_table-reset-no", 'readonly']) !!}
        <span class="input-group-addon">{{trans('admin.filter.to')}}</span>
        {!! Form::text('date_to', request('date_to', Carbon\Carbon::now()->format('Y-m-d')), ['class' => "input-sm form-control js_table-reset-no", 'readonly']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::select('pay_type', trans('admin.orders.pay_types'), request('pay_type'), ['placeholder' => trans('admin.companies.sel_type'),'class' => "form-control"]) !!}
</div>
<div class="form-group">
    {!! Form::select('route_id', $routes, request('route_id'), ['placeholder' => trans('admin.companies.sel_route'),'class' => "form-control"]) !!}
</div>
<div class="form-group">
    {!! Form::select('bus_id', $buses, request('bus_id'), ['class' => "form-control"]) !!}
</div>
<a href="{{ route('admin.companies.companyStatics', $company) }}" class="btn btn-default js_table-reset">
   {{trans('admin.filter.clear')}} </a>
{!! Form::close() !!}