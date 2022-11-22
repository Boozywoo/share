@if($notifications->count())
<div class="table-responsive">
    <table class="table table-condensed table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ trans('admin_labels.status') }}</th>
            <th>{{ trans('admin_labels.noti.source') }}</th>
            <th>{{ trans('admin_labels.noti.small_text') }}</th>
            <th>{{ trans('admin_labels.noti.type_notification') }}</th>
            <th>{{ trans('admin_labels.noti.user') }}</th>
            <th>{{ trans('admin_labels.noti.text') }}</th>
            <th>{{ trans('admin_labels.noti.created_at') }}</th>
            <th>{{ trans('admin_labels.noti.updated_at') }}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($notifications as $notification)
            @php
                $row = '';
                $notification->read ? $row = ['icon' => 'fa fa-check-circle', 'title' => trans('admin.notification.statuses.read')]: '';
                $notification->denied ? $row = ['icon' => 'fa fa-times', 'title' => trans('admin.notification.statuses.denied')] : '';
                $notification->approved ? $row = ['icon' => 'fa fa-check-square-o', 'title' => trans('admin.notification.statuses.approved')] : '';
                $notification->new ?  $row = ['icon' => 'fa fa-exclamation', 'title' => trans('admin.notification.statuses.new')] : '';
            @endphp
            <tr>
                <td>{{ $notification->id }}</td>
                <td> <i class="{{$row['icon']}}" title="{{$row['title']}}"></i></td>
                <td>
                    <a href="{{ $notification->source_url ?? '/' }}">{{ $notification->source }}</a>
                </td>
                <td>
                    {{$notification->small_text}}
                </td>
                <td>
                    {{$notification->type->name}}
                </td>
                <td>
                    {{$notification->user->first_name}}
                </td>
                <td>
                    {{$notification->text}}
                 </td>
                <td>
                    {{$notification->created_at}}
                 </td>
                <td>
                    {{$notification->updated_at}}
                 </td>
                <td class="td-actions">
                    @if($notification->type->view)
                        <a href="{{route ('admin.'. $entity . '.noti-edit', $notification)}}" class="btn btn-sm btn-warning pjax-link" data-toggle="tooltip" title="{{trans('admin_labels.noti.edit')}}">
                            <i class="fa fa-eye"></i>
                        </a>
                    @endif
                    @if($notification->type->approved && (!$notification->approved && !$notification->denied))
                        <a href="{{route ('admin.users.edit', [$notification->user, 'noti' => $notification])}}"
                           class="btn btn-sm btn-primary  pjax-link" data-toggle="tooltip" title="{{trans('admin_labels.noti.btn_ok')}}"
                           data-success="{{ trans('admin_labels.success_save') }}">
                            <i class="fa fa-check-square-o"></i></a>
                    @endif
                    @if($notification->type->read && (!$notification->read))
                    <div href="{{route ('admin.'. $entity . '.noti-read', $notification)}}" id="read_{{ $notification->id }}" onclick="read({{ $notification->id }})" data-reload="true" class="btn btn-sm btn-success js_panel_ajax" data-success="{{ trans('admin_labels.success_save') }}" data-toggle="tooltip" title="{{trans('admin_labels.noti.btn_read')}}">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    @endif
                    @if($notification->type->denied && (!$notification->approved && !$notification->denied))
                    <a href="{{route ('admin.'. $entity . '.noti-denied', $notification)}}" data-reload="true" class="btn btn-sm btn-danger js_panel_ajax" data-success="{{ trans('admin_labels.success_save') }}" data-toggle="tooltip" title="{{trans('admin_labels.noti.btn_denied')}}">
                        <i class="fa fa-times"></i>
                    </a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@else
    <p class="text-muted">{{ trans('admin.users.nothing') }}</p>
@endif

<script>

    function read(id) {
        $.ajax({
            type: 'POST',
            url: '/admin/notifications/' + id + '/read',
            success: function (data) {
                let el = $("#read_" + id);
                el.hide();
            }
        });
    }

</script>
