{!! Form::open(['class' => 'form-inline js_table-search', 'method' => 'get']) !!}
    <div class="input-daterange input-group js_table-reset-no" id="datepicker">
        {!! Form::text('date_from', request('date_from', Carbon\Carbon::now()->subDay(3)->format('Y-m-d')), ['class' => "input-sm form-control js_table-reset-no", 'readonly']) !!}
        <span class="input-group-addon">{{trans('admin.filter.to')}}</span>
        {!! Form::text('date_to', request('date_to', Carbon\Carbon::now()->format('Y-m-d')), ['class' => "input-sm form-control js_table-reset-no", 'readonly']) !!}
    </div>
    <div class="form-group">
        {!! Form::select('status', trans('admin.tours.statuses'), 'active', ['placeholder' => trans('admin.buses.sel_status'),'class' => "form-control"]) !!}
    </div>
    {{--<button type="submit" class="btn btn-warning"><span class="fa fa-filter"></span>{{trans('admin.filter.find')}}</button>--}}
    <a href="{{ route('admin.users.index') }}" class="btn btn-default js_table-reset"><span class="fa fa-ban"></span>{{trans('admin.filter.clear')}}</a>
{!! Form::close() !!}