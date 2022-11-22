{!! Form::open(['class' => 'form-inline js_table-search', 'method' => 'get']) !!}
    <div class="form-group">
        {!! Form::text('name', request('name'), ['placeholder' => trans('admin.buses.enter_name'),'class' => "form-control"]) !!}
    </div>
    <div class="form-group">
        {!! Form::text('number', request('number'), ['placeholder' => trans('admin.buses.enter_num'),'class' => "form-control"]) !!}
    </div>
    <div class="form-group">
        {!! Form::select('company_id', $companies, request('company_id'), ['placeholder' => trans('admin.buses.sel_company'),'class' => "form-control"]) !!}
    </div>
    <div class="form-group">
        {!! Form::select('status', trans('admin.drivers.statuses'), request('status'), ['placeholder' => trans('admin.buses.sel_status'),'class' => "form-control"]) !!}
    </div>
    {{--<button type="submit" class="btn btn-warning"><span class="fa fa-filter"></span> {{trans('admin.filter.find')}}</button>--}}
    <a href="{{ route('admin.users.index') }}" class="btn btn-default js_table-reset"><span class="fa fa-ban"></span> {{trans('admin.filter.clear')}}</a>
{!! Form::close() !!}