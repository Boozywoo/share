{!! Form::open(['class' => ' text-center filter_form', 'method' => 'get']) !!}

<div class="form-inline row">
    <div class="form-group ">
        {!! Form::number('id', request('id'), ['class' => 'form-control ', 'placeholder' => __('admin_labels.order_id')]) !!}
    </div>

    <div class="form-group">
        {!! Form::select('type', __('admin.repair_orders.types'), request('type'), array_merge(['class' => "form-control select-with-placeholder",'placeholder' => trans('admin_labels.type')])) !!}
    </div>
    <div class="form-group">
        {!! Form::select('status', __('admin.repair_orders.statuses'), request('status'), array_merge(['class' => "form-control select-with-placeholder",'placeholder' => trans('admin_labels.status')])) !!}
    </div>
    <div class="form-group ">
        {!! Form::text('name', request('name'), ['class' => 'form-control ', 'placeholder' => __('admin_labels.name')]) !!}
    </div>
    <div class="form-group ">
        {!! Form::text('bus_number', request('bus_number'), ['class' => 'form-control ', 'placeholder' => __('admin_labels.state_number')]) !!}
    </div>
    <div class="form-group ">
        {!! Form::text('bus_garage_number', request('bus_garage_number'), ['class' => 'form-control ', 'placeholder' => __('admin_labels.garage_number')]) !!}
    </div>
</div>
<div class="form-inline row" style="margin-top:5px;">

    <div class="form-group">
        <button type="submit" class="btn form-control btn-warning">{{__('admin.filter.find')}}</button>
    </div>
    <div class=" form-control btn btn-default js_table-reset">{{__('admin.filter.clear')}}</div>
</div>
{!! Form::close() !!}
