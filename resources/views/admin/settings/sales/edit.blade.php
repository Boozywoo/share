@extends('panel::layouts.main')

@section('title', $sale->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i class="fa fa-chevron-left"></i> {{trans('admin.filter.back')}}</a>
@endsection

@section('main')

    {!! Form::model($sale, ['route' => 'admin.'. $entity . '.store', 'class' => "ibox form-horizontal js_form-ajax js_form-ajax-reset"])  !!}
        {!! Form::hidden('id') !!}
        <div class="ibox-content">
            <h2>
                {{ $sale->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}
            </h2>
            <div class="hr-line-dashed"></div>
            <div class="row">
                <div class="col-md-6">
                    {{ Form::panelText('name') }}
                    {{ Form::panelText('date_start', $sale->date_start ? $sale->date_start->format('d.m.Y') : Carbon\Carbon::now()->format('d.m.Y'), 'js_datepicker') }}
                    {{ Form::panelText('date_finish', $sale->date_finish ? $sale->date_finish->format('d.m.Y') : Carbon\Carbon::now()->addWeek()->format('d.m.Y'), 'js_datepicker') }}
                    {{ Form::panelSelect('type', trans('admin.settings.sales.types')) }}
                    {{ Form::panelText('count') }}
                    {{--{{ Form::panelText('percent') }}--}}
                    {{ Form::panelText('value') }}
                    {{ Form::panelSelect('is_percent', trans('admin_labels.no_yes')) }}

                    @if($sale->id)
                        {{ Form::panelSelect('status', trans('admin.settings.sales.statuses')) }}
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
