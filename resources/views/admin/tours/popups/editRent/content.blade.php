{!! Form::model($tour, ['route' => 'admin.'. $entity . '.storeRent', 'class' => 'ibox-content form-horizontal js_form-ajax js_form-ajax-popup js_form-ajax-table js_form-current-page js_tours-from', 'data-wrap' => '.js_tour-edit-info', 'data-wrap-sub' => '.js_tour-edit-template']) !!}
{!! Form::hidden('id', $tour->id) !!}
{!! Form::hidden('calculation', 0) !!}
{!! Form::hidden('is_rent', 1) !!}
    <button type="button" class="close" data-dismiss="modal">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    <h2>{{ $tour->id ? trans('admin.'. $entity . '.edit_rent') : trans('admin.'. $entity . '.create_rent') }}</h2>
    @if($tour->id)
        <b>{{ trans('admin_labels.price') }}</b> @price($tour->price)
    @endif
    <div class="hr-line-dashed"></div>
    <div class="row">
        <div class="col-md-6">
            {{ Form::panelSelect('bus_id', $buses, null, ['class' => 'form-control js_bus-change', 'data-url' => route('admin.schedules.getDriverId') ]) }}
            {{ Form::panelSelect('driver_id', $drivers, null, ['class' => 'form-control js_driver-select']) }}
            {{ Form::panelText('date_start', $tour->date_start ? $tour->date_start->format('d.m.Y') : Carbon\Carbon::now()->format('d.m.Y'), '', ['class' => 'form-control js_datepicker']) }}
            {{ Form::panelText('date_finish', $tour->date_finish ? $tour->date_finish->format('d.m.Y') : Carbon\Carbon::now()->addHour()->format('d.m.Y'), '', ['class' => 'form-control js_datepicker']) }}
            {{ Form::panelText('time_start', $tour->time_start ? $tour->prettyTimeStart : Carbon\Carbon::now()->format('H:i'), '', ['class' => 'form-control time-mask']) }}
            {{ Form::panelText('time_finish', $tour->time_finish ? $tour->prettyTimeFinish : Carbon\Carbon::now()->addHour()->format('H:i'), '', ['class' => 'form-control time-mask']) }}
        </div>
        <div class="col-md-6">
                {{ Form::panelTextarea('comment') }}
        </div>
    </div>
    <div class="hr-line-dashed"></div>
    {{ Form::panelButton() }}
{!! Form::close() !!}

