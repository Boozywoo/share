@extends('panel::layouts.main')

@section('title', $notification->id ? trans('admin.'. $entity . '.edit') . $notification->id : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i> Вернуться назад</a>
@endsection

@section('main')
    <div class="ibox-content">
        <h2>{{ $notification->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}</h2>
        <div class="hr-line-dashed"></div>
        <div class="row">
            <div class="col-md-6">
                {{trans('admin_labels.noti.source')}} <span style="margin-left: 71px;">{{$notification->source}}</span>
                <br>
                {{trans('admin_labels.noti.user')}}<span style="margin-left: 71px;">{{$notification->user->first_name}}</span>
                <br>
                {{trans('admin_labels.noti.small_text')}}<span style="margin-left: 20px;">{{$notification->small_text}}</span>
                <br>
                {{trans('admin_labels.noti.created_at')}}<span style="margin-left: 43px;">{{$notification->created_at}}</span>
                <br>
                {{trans('admin_labels.noti.updated_at')}}<span style="margin-left: 35px;">{{$notification->updated_at}}</span>
                <br>
                {{trans('admin_labels.noti.text')}}<span style="margin-left: 20px;">{{ $notification->text }}</span>
            </div>
        </div>
    </div>
    <div class="ibox-footer">

        @if($notification->type->approved && (!$notification->approved && !$notification->denied))
        <a href="{{route ('admin.users.edit', [$notification->user, 'noti' => $notification])}}"
           class="btn btn-warning  pjax-link" data-toggle="tooltip" title=""
           data-success="{{ trans('admin_labels.success_save') }}">
            <i class=" ">{{trans('admin_labels.noti.btn_ok')}}</i></a>
        @endif
        @if($notification->type->denied && (!$notification->approved && !$notification->denied))
        <a href="{{route ('admin.' . $entity . '.noti-denied', $notification)}}"
           class="btn btn-danger  js_panel_ajax " data-toggle="tooltip" title=""
           data-success="{{ trans('admin_labels.success_save') }}">
            <i class=" ">{{trans('admin_labels.noti.btn_denied')}}</i></a>
        @endif
        @if($notification->type->read && (!$notification->read))
        <a href="{{route ('admin.' . $entity . '.noti-read', $notification)}}"
           class="btn btn-primary  js_panel_ajax " data-toggle="tooltip" title=""
           data-success="{{ trans('admin_labels.success_save') }}>
            <i class=" ">{{trans('admin_labels.noti.btn_read')}}</i></a>
        @endif
            <a href="{{ route('admin.notifications.noti-index', ['status' => '', 'create-date' => '', 'treatment-date'=>'']) }}" class="btn btn-success pjax-link">{{trans('admin_labels.noti.btn_close')}}</a>
     </div>

@endsection