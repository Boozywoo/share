@extends('panel::layouts.main')
@php
    $title = trans('admin.'. $entity . '.take_car');

switch (request()->get('type')){
case 'take':
    $title = trans('admin.'. $entity . '.take_car');
    break;
case 'put':
    $title = trans('admin.'. $entity . '.put_car');
    break;
case 'review':
    $title = trans('admin.'. $entity . '.inspect_car');
    break;
}
@endphp
@section('title', $title)

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i> {{trans('admin.filter.back')}}</a>
@endsection

@section('main')
    {!! Form::model($userTakenBus, ['route' => (request('type') == 'put' ? 'admin.'. $entity . '.put' : 'admin.'. $entity . '.store'), 'class' => 'ibox form-horizontal js_form-ajax js_form-ajax-redirect'])  !!}
    {!! Form::hidden('bus_id', $bus->id) !!}
    <div class="ibox-content">
        <h2>
            {{ $title }}
        </h2>
        <div class="hr-line-dashed"></div>
        <h3>
            {{trans('admin.'.$entity.'.describe')}} {{$bus->name}}
        </h3>
        <div class="row">
            <div class="col-md-6">
                {!! Form::hidden('type', request('type')) !!}
                {!! Form::hidden('min_odometer', $bus->getLastVariables()->odometer ?? 0) !!}
                <p>{{trans('admin.'.$entity.'.km')}} : {{$bus->getLastVariables()->odometer ?? '0'}}</p>

                <div class="form-group">
                    <label for="fuel"
                           class="col-md-4 control-label">{{__('admin_labels.odometer')}}</label>
                    <div class="col-md-8">
                        {{ Form::number('odometer', $bus->getLastVariables()->odometer ?? 0 ,['class' => 'form-control','placeholder' => trans('admin.'.$entity.'.new_km'), 'min' => $bus->getLastVariables()->odometer ?? '0']) }}
                        <p class="error-block"></p>
                    </div>
                </div>

                {{--                {{ Form::panelText('odometer', '', null,['placeholder' => trans('admin.'.$entity.'.new_km'), 'min' => $bus->getLastVariables()->odometer ?? '0']) }}--}}

                <p>{{trans('admin.'.$entity.'.fuel')}} : {{ $bus->getLastVariables()->fuel ?? '0'}}</p>
                <div class="form-group">
                    <label for="fuel"
                           class="col-md-4 control-label">{{__('admin_labels.fuel')}}</label>
                    <div class="col-md-8">
                        {{ Form::number('fuel', $bus->getLastVariables()->fuel ?? 0 ,['placeholder' => trans('admin.'.$entity.'.new_fuel'),'class' => 'form-control']) }}
                        <p class="error-block"></p>
                    </div>
                </div>
                {!! Form::panelRadios('condition', $conditions, 1) !!}
            </div>
        </div>
    </div>
    <div class="ibox-footer">
        {{ Form::panelButton() }}
    </div>
    {!! Form::close() !!}
@endsection
