{!! Form::open(['class' => 'form-inline js_table-search', 'method' => 'get']) !!}
<div class="form-group">
    {!! Form::text('number', request('number'), ['placeholder' => trans('admin.orders.num_contract'),
        'class' => "form-control"]) !!}
</div>
<div class="form-group">
    {!! Form::select('enabled', trans('admin_labels.no_yes_active'), request('enabled'), 
        [
            'placeholder' => trans('admin.buses.sel_status'),
            'class' => "form-control"
        ]
    ) !!}
</div>
<a href="{{ route('admin.users.index') }}" class="btn btn-default js_table-reset"><span
            class="fa fa-ban"></span> {{trans('admin.filter.clear')}}</a>
{!! Form::close() !!}