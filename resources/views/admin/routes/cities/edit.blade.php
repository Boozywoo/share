@extends('panel::layouts.main')

@section('title', $city->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i class="fa fa-chevron-left"></i> {{ trans('admin.filter.back') }}</a>
@endsection

@section('main')

    {!! Form::model($city, ['route' => 'admin.'. $entity . '.store', 'class' => "ibox form-horizontal js_form-ajax js_form-ajax-reset"])  !!}
        {!! Form::hidden('id') !!}
        <div class="ibox-content">
            <h2>{{ $city->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}</h2>
            <div class="hr-line-dashed"></div>
            <div class="row">
                <div class="col-md-6">
                    {{ Form::panelText('name') }}
                    {{ Form::panelText('name_tr') }}
                    @if($city->id)
                        {{ Form::panelSelect('status', trans('admin.routes.cities.statuses')) }}
                    @endif
                    {{ Form::panelSelect('timezone', $timezonelist, $city->timezone) }}
                </div>
                <div class="col-md-6">
                    {{ Form::panelSelect('is_rent', trans('admin_labels.no_yes'), $city->is_rent) }}
                    {{ Form::panelSelect('is_transfer', trans('admin_labels.no_yes'), $city->is_transfer) }}
                </div>
            </div>
        </div>
        <div class="ibox-footer">
            {{ Form::panelButton() }}
        </div>
    {!! Form::close() !!}
@endsection