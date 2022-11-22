{!! Form::open(['class' => 'form-inline js_table-search', 'method' => 'get']) !!}
    <div class="form-group">
        {!! Form::text('phone', request('phone'), ['placeholder' => trans('admin.clients.enter_tel'),'class' => "form-control"]) !!}
    </div>
    <div class="form-group">
        {!! Form::select('status', trans('admin.clients.statuses'), request('status'), ['placeholder' => trans('admin.buses.sel_status'),'class' => "form-control"]) !!}
    </div>
    <div class="form-group">
        {!! Form::select('reputation', trans('admin.clients.reputations'), request('reputation'), ['placeholder' => trans('admin.clients.sel_rep'),'class' => "form-control"]) !!}
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