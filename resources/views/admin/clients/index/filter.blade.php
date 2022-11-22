{!! Form::open(['class' => 'form-inline js_table-search', 'method' => 'get']) !!}
    <div class="form-group">
        {!! Form::text('first_name', request('first_name'), ['placeholder' => trans('admin.clients.enter_name'),'class' => "form-control"]) !!}
    </div>
    <div class="form-group">
        {!! Form::text('last_name', request('last_name'), ['placeholder' => trans('admin.clients.enter_surname'),'class' => "form-control"]) !!}
    </div>
    <div class="form-group">
        {!! Form::text('passport', request('passport'), ['placeholder' => trans('admin.clients.enter_pass'),'class' => "form-control"]) !!}
    </div>
    <div class="form-group">
        {!! Form::text('phone', request('phone'), ['placeholder' => trans('admin.clients.enter_tel'),'class' => "form-control"]) !!}
    </div>
    <div class="form-group">
        {!! Form::select('status', trans('admin.users.statuses'), request('status'), ['placeholder' => trans('admin.buses.sel_status'),'class' => "form-control"]) !!}
    </div>
    {{--<button type="submit" class="btn btn-warning"><span class="fa fa-filter"></span> {{trans('admin.filter.find')}}</button>--}}
    {{--<a href="{{ route('admin.users.index') }}" class="btn btn-default js_table-reset"><span class="fa fa-ban"></span> {{trans('admin.filter.clear')}}</a>--}}
{!! Form::close() !!}