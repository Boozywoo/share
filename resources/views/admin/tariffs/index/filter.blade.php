{!! Form::open(['class' => 'form-inline js_table-search', 'method' => 'get']) !!}
{{--<div class="form-group">
    {!! Form::text('name', request('name'), ['placeholder' => trans('admin.buses.enter_name'),'class' => "form-control"]) !!}
</div>--}}
<div class="form-group">
    {!! Form::select('bus_type_id', $busTypes, request('bus_type_id'), ['placeholder' => trans('admin.tariffs.sel_type_bus'),'class' => "form-control"]) !!}
</div>
<div class="form-group">
    {!! Form::select('type', trans('admin.tariffs.types'), request('type'), ['placeholder' => trans('admin.tariffs.sel_type'),'class' => "form-control"]) !!}
</div>
<div class="form-group">
    {!! Form::select('status', trans('admin.users.statuses'), request('status'), ['placeholder' => trans('admin.buses.sel_status'),'class' => "form-control"]) !!}
</div>

{{--<button type="submit" class="btn btn-warning"><span class="fa fa-filter"></span> {{trans('admin.filter.find')}}</button>--}}
<a href="{{ route('admin.users.index') }}" class="btn btn-default js_table-reset "><span class="fa fa-ban"></span>
   {{trans('admin.filter.clear')}}</a>
{!! Form::close() !!}