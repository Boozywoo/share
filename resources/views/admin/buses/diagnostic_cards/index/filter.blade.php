{!! Form::open(['class' => 'form-inline js_table-search', 'method' => 'get']) !!}
{!! Form::hidden('bus_id', request('bus_id'), ['class' => 'js_table-reset-no']) !!}
<div class="form-group">
    {!! Form::select('status', trans('admin.buses.diagnostic_cards.statuses'), request('status'), ['placeholder' => trans('admin.buses.sel_status'),'class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::text('name', request('name'), ['placeholder' => trans('admin.buses.enter_name'),'class' => 'form-control']) !!}
</div>


<a href="{{ route('admin.users.index') }}" class="btn btn-default js_table-reset"><span
            class="fa fa-ban"></span> {{trans('admin.filter.clear')}}</a>
{!! Form::close() !!}
