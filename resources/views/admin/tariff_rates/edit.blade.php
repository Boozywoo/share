@extends('panel::layouts.main')

@section('title', $rate->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i> {{trans('admin.filter.back')}}</a>
@endsection

@section('main')
    {!! Form::model($rate, ['route' => 'admin.'. $entity . '.store', 'class' => "ibox form-horizontal js_form-ajax js_form-ajax-reset"])  !!}
    {!! Form::hidden('tariff_id', $tariff ? $tariff->id : null) !!}
    {!! Form::hidden('id') !!}
    <div class="ibox-content">
        <h2>{{ $tariff->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}</h2>
        <div class="hr-line-dashed"></div>
        <div class="row">
            <div class="col-md-6">
                {{ Form::panelText('min', $minValue, null,['readonly' => true]) }}
                {{ Form::panelText('max', $minValue + 1, null, ['readonly' => $maxReadonly]) }}
                {{ Form::panelText('cost') }}
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