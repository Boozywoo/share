{!! Form::open(['class' => 'form-inline js_table-search', 'method' => 'get']) !!}
    <div class="form-group">
        {!! Form::select('route_id', $routes, request('route_id'), 
            [
                'placeholder' => trans('admin.orders.sel_route'),
                'class' => "form-control"
            ]
        ) !!}
    </div>
    <div class="form-group">
        {!! Form::select('type', trans('admin.reviews.types'), request('type'), 
            [
                'placeholder' => trans('admin.buses.sel_type'),
                'class' => "form-control"
            ]
        ) !!}
    </div>
    {{--<button type="submit" class="btn btn-warning"><span class="fa fa-filter"></span> {{trans('admin.filter.find')}}</button>--}}
    <a href="{{ route('admin.users.index') }}" class="btn btn-default js_table-reset"><span class="fa fa-ban"></span> {{trans('admin.filter.clear')}}</a>
{!! Form::close() !!}