@extends('panel::layouts.main')

@section('title', $client->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i class="fa fa-chevron-left"></i> {{
    trans('admin.filter.back') }}</a>
@endsection

@section('main')
    {!! Form::model($client, ['route' => 'admin.'. $entity . '.store', 'class' => "ibox form-horizontal js_form-ajax js_form-ajax-reset"]) !!}
        {!! Form::hidden('id', $client->id) !!}
        <div class="ibox-content">
            <h2>{{ $client->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}</h2>
            <div class="hr-line-dashed"></div>
            <div class="row">
                <div class="col-md-6">
                    {{ Form::panelText('first_name') }}
                    {{ Form::panelText('middle_name') }}
                    {{ Form::panelText('last_name') }}
                    {{ Form::panelText('email') }}
                    {{ Form::panelText('passport') }}
                    {{ Form::panelSelect('doc_type', trans('admin_labels.doc_types')) }}
                    {{ Form::panelText('doc_number') }}
                    {{ Form::panelSelect('country_id', trans('admin_labels.countries')) }}
                    {{ Form::panelSelect('gender', trans('admin_labels.genders')) }}
                    {{ Form::panelText('birth_day', $client->birth_day ? $client->birth_day->format('d.m.Y') : '', 'js_datepicker') }}

                    {{ Form::panelSelect('status_id', $statuses) }}
                    {{ Form::panelText('date_social', $client->date_social ? $client->date_social->format('d.m.Y') : '', 'js_datepicker') }}

                    @if($client->id)
                        {{ Form::panelSelect('status', trans('admin.clients.statuses')) }}
                        {{ Form::panelSelect('reputation', trans('admin.clients.reputations')) }}
                    {{--@php($companies->prepend('- ' . trans('admin_labels.not_selected') . ' -'))--}}
                        {{ Form::panelSelect('company_id',$companies,$client ? $client->company_id : null ) }}
                    @endif
                    @if(env('TIME_ZONE'))

                    {{ Form::panelSelect('timezone',$timezonelist,$client ? $client->timezone : null ) }}
                    @endif
                    <div class="form-group">
                        <label for="email" class="col-md-4">{{trans('admin.auth.tel')}}</label>
                        <div class="col-md-8">
                            <input class="form-control" name="phone" type="text" value="{{$client->phone}}">
                            <p class="error-block"></p>
                        </div>
                    </div>
                    {{--{{ Form::panelText('phone', $client->phone, 'js_orders-client-phone') }}--}}
                    {{--{{ Form::panelText('phone', $client->editPhone, 'js_panel_input-phone') }}--}}
                    <div class="form-group">
                        {!! Form::label('password', trans('admin.auth.pass'), 
                            ['class' => 'col-md-4 control-label']
                        ) !!}
                        <div class="col-md-8">
                            {!! Form::password('password', ['class' => "form-control", 'autocomplete' => 'off']) !!}
                            <p class="error-block"></p>
                        </div>
                    </div>

                    {!! $client->getImagesView($client::IMAGE_TYPE_IMAGE) !!}

                </div>
            </div>
        </div>
        <div class="ibox-footer">
            {{ Form::panelButton() }}
        </div>
    {!! Form::close() !!}
@endsection