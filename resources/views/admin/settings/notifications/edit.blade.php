@extends('panel::layouts.main')

@section('title', $notification->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i>{{
    trans('admin.filter.back') }}</a>
@endsection
@section('main')
    {!! Form::model($notification, ['route' => 'admin.'. $entity . '.store', 'class' => 'ibox form-horizontal js_form-ajax js_form-ajax-reset'])  !!}
    {!! Form::hidden('id') !!}
    <div class="ibox-content">
        <h2>
            {{ $notification->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}
        </h2>
        <div class="hr-line-dashed"></div>
        <div class="row">
            <div class="col-md-6">

                <div class="row">
                    <div class="col-md-12">
                        {{ Form::panelText('name', null, '', $readonly ? ['readonly'=>'readonly'] : []) }}

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {{ Form::panelText('slug', $notification->slug , 'name', $readonly ? ['readonly'=>'readonly'] : []) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {{ Form::panelSelect('notification_role_id', $roles, $notification->role_id, $readonly ? ['disabled'=>'disabled'] : []) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="notification_without_role" class="col-md-4 text-right">{{trans('admin_labels.notification_without_role')}}</label>
                            <div class="col-md-8">
                                {{Form::panelCheckbox('notification_without_role', $notification->id && $notification->role_id === null)}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-xs-3">
                        {{Form::onOffCheckbox("approved", $notification->approved ? 1 : 0 )}}
                    </div>
                    <div class="col-xs-9">
                        <label for="approved">{{trans('admin_labels.noti.btn_ok')}}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-3">
                        {{Form::onOffCheckbox("denied", $notification->denied ? 1 : 0 )}}
                    </div>
                    <div class="col-xs-9">
                        <label for="denied">{{trans('admin_labels.noti.btn_denied')}}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-3">
                        {{Form::onOffCheckbox("view", $notification->view ? 1 : 0 )}}
                    </div>
                    <div class="col-xs-9">
                        <label for="view">{{trans('admin_labels.noti.edit')}}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-3">
                        {{Form::onOffCheckbox("read", $notification->read ? 1 : 0 )}}
                    </div>
                    <div class="col-xs-9">
                        <label for="read">{{trans('admin_labels.noti.btn_read')}}</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="col-md-10 col-md-offset-1">
                    <div class="row" style="margin-bottom: 15px;">
                        <div class="col-xs-1" style="padding-left: 0;">
                            Всему отделу
                        </div>
                        <div class="col-xs-10" style="padding-left: 0;">
                            Только руководителю
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
                                    {{Form::onOffCheckbox("departments_notification[".$department->id."]",  in_array($department->id, $departmentsNotificationSelected, true) )}}
                                </div>
                                <div class="col-xs-10">
                                    <input {{ in_array($department->id, $departmentsNotificationType, true) ? 'checked="checked"' : '' }} name="departments[{{$department->id}}]"
                                           type="checkbox" value="{{$department->id}}">
                                    <label for="departments[{{$department->id}}]">{{ $department->name }}</label>                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

{{--        <div class="row">--}}
{{--            <div class="col-md-12">--}}
{{--                @foreach($departments as $department)--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-md-12">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="role_id" class="col-md-2"></label>--}}
{{--                                <div class="col-md-10">--}}
{{--                                    <div class="col-md-10">--}}
{{--                                        <input {{ in_array($department->id, $departmentsNotificationType) ? 'checked="checked"' : '' }} name="departments[{{$department->id}}]" type="checkbox" value="{{$department->id}}">--}}
{{--                                        <label for="departments[{{$department->id}}]">{{ $department->name }}</label>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endforeach--}}
{{--            </div>--}}

{{--        </div>--}}

    </div>
    <div class="ibox-footer">
        {{ Form::panelButton() }}
    </div>
    {!! Form::close() !!}

@endsection
