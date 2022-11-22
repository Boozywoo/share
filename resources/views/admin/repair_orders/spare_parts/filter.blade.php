{!! Form::open(['class' => ' form-inline js_table-search', 'method' => 'get']) !!}

<div class="form-inline row" style="margin-top:5px;">
    <div class="form-group">
        {!! Form::select('status', __('admin.'.$entity.'.statuses'), request('status'), array_merge(['class' => "form-control select-with-placeholder",'placeholder' => trans('admin_labels.status')])) !!}
    </div>

    <div class="form-group">
        <button type="submit" class="btn form-control btn-warning">{{__('admin.filter.find')}}</button>
    </div>
    <div class=" form-control btn btn-default js_table-reset">{{__('admin.filter.clear')}}</div>
</div>
{!! Form::close() !!}
