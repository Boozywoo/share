{!! Form::open(['class' => 'form-inline js_table-search', 'method' => 'get']) !!}
<div class="form-group">
    {!! Form::checkbox('all_cars', 1, request('all_cars'), ['class' => "form-control"]) !!}
    {{ Form::label(trans('admin.'.$entity.'.all_cars')) }}
</div>
<div class="form-group">
    {!! Form::text('number', request('number'), ['placeholder' => trans('admin.'.$entity.'.enter_number'),'class' => "form-control"]) !!}
</div>
<div class="form-group">
    {!! Form::text('name', request('name'), ['placeholder' => trans('admin.'.$entity.'.enter_name'),'class' => "form-control"]) !!}
</div>
<div class="form-group">
    {!! Form::select('department_id', $departments, request('department_id  '), ['placeholder' => trans('admin_labels.departments'),'class' => "form-control"]) !!}
</div>

<button type="submit" class="btn btn-warning"><span class="fa fa-filter"></span> {{trans('admin.filter.find')}}</button>
<a href="{{ route('admin.'.$entity.'.index') }}" class="btn btn-default js_table-reset"><span class="fa fa-ban"></span>
    {{trans('admin.filter.clear')}}</a>
{!! Form::close() !!}