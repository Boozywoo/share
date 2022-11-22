{!! Form::open(['class' => 'form-inline js_table-search', 'method' => 'get']) !!}
    <div class="form-group">
        {!! Form::text('name', request('name'), ['placeholder' => trans('admin.buses.enter_name'), 'class' => "form-control"]) !!}
    </div>
    <div class="form-group">
        {!! Form::select('city_id', $cities, request('city_id'), ['placeholder' => trans('admin.clients.sel_city'), 'class' => "form-control"]) !!}
    </div>
    {{--<button type="submit" class="btn btn-warning"><span class="fa fa-filter"></span> {{trans('admin.filter.find')}}</button>--}}
    <a href="{{ route('admin.users.index') }}" class="btn btn-default js_table-reset"><span class="fa fa-ban"></span> {{trans('admin.filter.clear')}}</a>
{!! Form::close() !!}