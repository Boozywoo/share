{!! Form::open(['class' => 'form-inline js_table-search', 'method' => 'get']) !!}
    <div class="form-group">
        {!! Form::text('name', request('name'), ['placeholder' => trans('admin.buses.enter_name'),'class' => "form-control"]) !!}
    </div>
    <div class="form-group">
        {!! Form::select('status', trans('admin.companies.statuses'), request('status'), ['placeholder' => trans('admin.buses.sel_status'),'class' => "form-control"]) !!}
    </div>
    <div class="form-group">
        {!! Form::select('reputation', trans('admin.companies.reputations'), request('reputation'), ['placeholder' => trans('admin.clients.sel_rep'),'class' => "form-control"]) !!}
    </div>
    {{--<button type="submit" class="btn btn-warning"><span class="fa fa-filter"></span>{{trans('admin.filter.find')}}</button>--}}
    <a href="{{ route('admin.users.index') }}" class="btn btn-default js_table-reset"><span class="fa fa-ban"></span>{{trans('admin.filter.clear')}}</a>
{!! Form::close() !!}