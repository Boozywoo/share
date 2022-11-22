{!! Form::open(['class' => 'form-inline js_table-search', 'method' => 'get']) !!}

    <div class="form-group">
        {!! Form::select('status', trans('admin.notification.statuses'), request('status'),
            [
                'placeholder' => trans('admin.buses.sel_status'),
                'class' => "form-control"
            ]
        ) !!}
    </div>

    <div class="noty-calendar">
        {{ Form::panelText('create-date', null, 'js_datepicker', ['placeholder' => trans('admin_labels.create-date'), 'class' => 'form-control js_datepicker'],false) }}
    </div>

    <div class="noty-calendar">
        {{ Form::panelText('treatment-date', null, 'js_datepicker', ['placeholder' => trans('admin_labels.treatment-date'), 'class' => 'form-control js_datepicker'],false) }}
    </div>

    {{--<button type="submit" class="btn btn-warning"><span class="fa fa-filter"></span> {{trans('admin.filter.find')}}</button>--}}
    <a href="{{ route('admin.notifications.noti-index') }}" class="btn btn-default js_table-reset"><span class="fa fa-ban"></span> {{trans('admin.filter.clear')}}</a>
{!! Form::close() !!}
