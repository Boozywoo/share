@extends('panel::layouts.main')

@section('title', $wishesType->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i>{{
    trans('admin.filter.back') }}</a>
@endsection
@section('main')
    {!! Form::model($wishesType, ['route' => 'admin.'. $entity . '.store', 'class' => 'ibox form-horizontal js_form-ajax js_form-ajax-redirect'])  !!}
    {!! Form::hidden('id') !!}
    <div class="ibox-content">
        <h2>
            {{ $wishesType->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}
        </h2>
        <div class="hr-line-dashed"></div>
        <div class="row">
            <div class="col-md-6">

                <div class="row">
                    <div class="col-md-12">
                        {{ Form::panelText('name') }}

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {{ Form::panelSelect('notification', $notifications, $wishesType->notification_type_id, $readonly ? ['disabled'=>'disabled'] : []) }}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-xs-3">
                        {{Form::onOffCheckbox("status", $wishesType->status ? 1 : 0 )}}
                    </div>
                    <div class="col-xs-9">
                        <label for="status">{{trans('admin_labels.wishes.status')}}</label>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-3">
                        {{Form::onOffCheckbox("notifi_supervisor", $wishesType->notifi_supervisor ? 1 : 0 )}}
                    </div>
                    <div class="col-xs-9">
                        <label for="notifi_supervisor">{{trans('admin_labels.notifi_supervisor')}}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-3">
                        {{Form::onOffCheckbox("denied", $wishesType->denied ? 1 : 0 )}}
                    </div>
                    <div class="col-xs-9">
                        <label for="denied">{{trans('admin_labels.noti.btn_denied')}}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-3">
                        {{Form::onOffCheckbox("view", $wishesType->view ? 1 : 0 )}}
                    </div>
                    <div class="col-xs-9">
                        <label for="view">{{trans('admin_labels.noti.edit')}}</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-md-1">
                            </div>
                            <div style="margin-left: 0; " class="col-xs-11">
                                {{ trans('admin.settings.wishes.check_on_departments') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @foreach($departments as $department)
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-1">

                        </div>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-xs-1">
                                        {{Form::onOffCheckbox("departments_users[".$department->id."]",  in_array($department->id, $departmentsNotifiCheck) ? 1 : 0 )}}
                                    </div>
                                    <div class="col-xs-10">
                                        <input {{ in_array($department->id, $departmentsWishesType) ? 'checked="checked"' : '' }} name="departments[{{$department->id}}]"
                                               type="checkbox" value="{{$department->id}}">
                                        <label for="departments[{{$department->id}}]">{{ $department->name }}</label>                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
    <div class="ibox-footer">
        {{ Form::panelButton() }}
    </div>
    {!! Form::close() !!}

@endsection
