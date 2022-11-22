{!! Form::open(['class' => 'form-inline js_table-search', 'method' => 'get']) !!}
<div class="form-group">
    <div class="input-daterange input-group js_table-reset-no" id="datepicker">
        {!! Form::text('date_from', request('date_from', Carbon\Carbon::now()->subMonths(1)->format('Y-m-d')), ['class' => "input-sm form-control js_table-reset-no", 'readonly']) !!}
        <span class="input-group-addon">{{trans('admin.filter.to')}}</span>
        {!! Form::text('date_to', request('date_to', Carbon\Carbon::now()->format('Y-m-d')), ['class' => "input-sm form-control js_table-reset-no", 'readonly']) !!}
    </div>
    <div class="input-group js_table-reset-no">
        {!! Form::select('currency_id', App\Models\Currency::all('id', 'name')->pluck('name', 'id'), request('currency_id'), ['placeholder' => 'Все валюты','class' => "form-control"]) !!}
    </div>
    <div class="input-group js_table-reset-no">
        {!! Form::select('appearance', trans('admin.users.appearances'), request('appearance'), ['placeholder' => trans('admin.users.sel_appearance'), 'class' => "form-control"]) !!}
    </div>
</div>
&nbsp;{{-- с &nbsp;
<div class="form-group">
    {!! Form::select('year_start', $years, request()->get('year_start',date('Y')), ['placeholder' => trans('admin.users.sel_from_year'),'class' => "form-control"]) !!}
</div>
<div class="form-group">
    {!! Form::select('month_start', trans('admin.months'), request()->get('month_start',date('m')), ['placeholder' => trans('admin.users.sel_from_mon'),'class' => "form-control"]) !!}
</div>
&nbsp; {{trans('admin.filter.to')}} &nbsp;
<div class="form-group">
    {!! Form::select('year_finish', $years, request()->get('year_start',date('Y')), ['placeholder' => trans('admin.users.sel_year'),'class' => "form-control"]) !!}
</div>
<div class="form-group">
    {!! Form::select('month_finish', trans('admin.months'), request()->get('month_start',date('m')), ['placeholder' => trans('admin.users.sel_mon'),'class' => "form-control"]) !!}
</div>--}}
{!! Form::close() !!}
