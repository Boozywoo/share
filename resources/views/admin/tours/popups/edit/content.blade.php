{!! Form::model($tour, ['route' => 'admin.'. $entity . '.store', 'class' => 'ibox-content form-horizontal js_form-ajax js_form-ajax-popup js_form-ajax-table js_form-current-page js_tours-from', 'data-wrap' => '.js_tour-edit-info', 'data-wrap-sub' => '.js_tour-edit-template']) !!}
{!! Form::hidden('id', $tour->id) !!}
{!! Form::hidden('calculation', 0) !!}
@php($isMediator = Auth::user()->isMediator)

<button type="button" class="close" data-dismiss="modal">
    <span aria-hidden="true">&times;</span>
    <span class="sr-only">Close</span>
</button>
<h2>{{ $tour->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}</h2>
@if($tour->id)
    <div class="js_tour-edit-info">
        @include('admin.tours.popups.edit.info')
    </div>
    <b>{{ trans('admin_labels.route_id') }}</b> {{ $tour->route->name }} <br>
    <b>{{ trans('admin_labels.price') }}</b>
        {{ $price }} {{ trans('admin_labels.currencies_short.' . $tour->route->currency->alfa) }}
@endif
<div class="hr-line-dashed"></div>
<div class="row">
    <div class="col-md-6">

        {{ Form::panelSelect('bus_id', $buses, null, ['class' => "form-control js_bus-change", 'data-url' => route('admin.schedules.getDriverId') ]) }}
        {{ Form::panelSelect('driver_id', $drivers, null, ['class' => "form-control js_driver-select"]) }}
        @if ($tour->id)
            {{ Form::panelText('date_start', $tour->date_start->format('d.m.Y'), '', ['class' => "form-control js_datepicker", 'disabled' => 'disabled', 'name' => 'fake_date']) }}
            {{ Form::hidden('date_start', $tour->date_start->format('d.m.Y')) }}
        @else
            {{ Form::panelText('date_start', (!empty($output['date'])? Carbon\Carbon::createFromFormat('d.m.Y', $output['date'])->format('d.m.Y'): Carbon\Carbon::now()->format('d.m.Y')), '', ['class' => "form-control js_datepicker"]) }}
        @endif 
        {{ Form::panelText('time_start', $tour->time_start ? $tour->prettyTimeStart : Carbon\Carbon::now()->format('H:i'), '', ['class' => "form-control time-mask"]) }}

        {{ Form::panelSelect('status', trans('admin.tours.statuses')) }}
    </div>
    <div class="col-md-6">
        @if(!$tour->id)
            {{ Form::panelSelect('route_id', $routes) }}
        @else
            {!! Form::hidden('route_id', $tour->route_id) !!}
        @endif
        {{ Form::panelText('price', $price) }}
        {{ Form::panelRadio('is_reserve') }}
        {{ Form::panelRadio('reservation_by_place') }}
        {{ Form::panelRadio('is_collect')}}
        {{ Form::panelRadio('is_show_front')}}
        {{ Form::panelRadio('is_show_agent')}}
        {{ Form::panelTextarea('comment') }}

    </div>
</div>
<div class="hr-line-dashed"></div>
@if($tour->id)
    <span class="btn btn-sm btn-warning js_tour-edit-calculation">{{trans('admin.orders.calc')}} </span>
@endif
{{ Form::panelButton() }}
@if($tour->id)
@endif
<span class="btn btn-sm btn-danger js_tour-edit-forced">{{trans('admin.routes.forced')}}</span>
@if($tour->id)
    <div class="hr-line-dashed"></div>
    <h3>Раскладка</h3>
    <b>Текущая рассадка</b>
    @include('admin.tours.show.partials.bus')
    {{--@include('admin.tours.show.partials.bus', ['little' => true])--}}
    <b>Новая рассадка</b>
    <div class="js_tour-edit-template">
    </div>
@endif
{!! Form::close() !!}

