{!! Form::open(['class' => 'form-inline js_table-search', 'method' => 'get']) !!}
    {!! Form::hidden('bus_id', request('bus_id'), ['class' => 'js_table-reset-no']) !!}
    <div class="form-group">
        {!! Form::select('status', 
            trans('admin.buses.repairs.statuses'), 
            request('status'), 
            [
                'placeholder' => trans('admin.buses.sel_status'),
                'class' => "form-control"
            ]
        ) !!}
    </div>
    <div class="form-group">
        {!! Form::select('type', 
            trans('admin.buses.repairs.types'), 
            request('type'), 
            [
                'placeholder' => trans('admin.buses.sel_type'),
                'class' => "form-control"
            ]
        ) !!}
    </div>
    {{--<button type="submit" class="btn btn-warning"><span class="fa fa-filter"></span> {{trans('admin.filter.find')}}</button>--}}
    <a href="{{ route('admin.users.index') }}" class="btn btn-default js_table-reset"><span class="fa fa-ban"></span> {{trans('admin.filter.clear')}}</a>
{!! Form::close() !!}