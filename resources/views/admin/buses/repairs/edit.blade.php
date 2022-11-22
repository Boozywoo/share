@extends('panel::layouts.main')

@section('title', $repair->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i class="fa fa-chevron-left"></i> {{
    trans('admin.filter.back') }}</a>
@endsection

@section('main')

    {!! Form::model($repair, ['route' => 'admin.'. $entity . '.store', 'class' => "ibox form-horizontal js_form-ajax js_form-ajax-reset"])  !!}
        {!! Form::hidden('id') !!}
        {!! Form::hidden('bus_id', request('bus_id')) !!}
        <div class="ibox-content">
            <h2>{{ $repair->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}</h2>
            <div class="hr-line-dashed"></div>
            <div class="row">
                <div class="col-md-6">
                    {{ Form::panelText('date_from', $repair->date_from ? $repair->date_from->format('d.m.Y') : Carbon\Carbon::now()->format('d.m.Y'), 'js_datepicker', [$repair->id ? 'disabled' : '']) }}
                    {{ Form::panelText('date_to', $repair->date_to ? $repair->date_to->format('d.m.Y') : Carbon\Carbon::now()->addWeek()->format('d.m.Y'), 'js_datepicker') }}

                    {{ Form::panelText('name') }}
                    {{ Form::panelTextarea('comment') }}
                    {{ Form::panelSelect('type', trans('admin.buses.repairs.types')) }}
                    @if($repair->id)
                        {{ Form::panelSelect('status', trans('admin.buses.repairs.statuses')) }}
                    @else
                        {!! Form::hidden('status', $repair_status) !!}
                    @endif
                </div>
                <div class="col-md-6">
                </div>
            </div>
        </div>
    <div class="ibox-footer">
        {{ Form::panelButton() }}
    </div>
    {!! Form::close() !!}
@endsection
