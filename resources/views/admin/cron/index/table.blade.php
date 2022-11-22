@if($cron->count())
    <div class="table-responsive">
        <table class="table table-condensed table-hover">
            <thead>
            <tr class="{{ $fonColor }}">
                <th>#</th>
                <th>{{ trans('admin_labels.type') }}</th>
                <th>{{ trans('admin_labels.object_name') }}</th>
                <th>{{ trans('admin_labels.params') }}</th>
                <th>{{ trans('admin_labels.user_id') }}</th>
                <th>{{ trans('admin_labels.status') }}</th>
                <th>{{ trans('admin_labels.created_at') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($cron as $bus)
                <tr>
                    <td>
                        {{ $bus->id }}
                    </td>

                    <td>
                        {{ $bus->type }}
                    </td>
                    <td>
                        {{ $bus->object_name }}
                    </td>
                    <td>
                        {{ $bus->params }}
                    </td>
                    <td>
                        {{  $bus->user_id }}
                    </td>
                    <td>
                        @if ($bus->is_active==1)
                            Активное
                        @else
                            Не активное
                        @endif
                    </td>
                    <td>
                        {{ $bus->created_at }}
                    </td>

                    <td class="td-actions">
                        <a href="{{route ('admin.'. $entity . '.delete', $bus)}}"
                           class="btn btn-sm btn-danger js_panel_confirm " data-toggle="tooltip"
                           title="{{ trans('admin.cron.delete') }}">
                            <i class="fa fa-trash-o "></i>
                        </a>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="text-muted">{{ trans('admin.users.nothing') }}</p>
@endif