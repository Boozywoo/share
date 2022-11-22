@if($incidents->count())
    <div class="table-responsive">
        <table class="table table-condensed table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('admin_labels.name') }}</th>
                <th>{{ trans('admin_labels.department_id') }}</th>
                <th>{{ trans('admin_labels.creator') }}</th>
                <th>{{ trans('admin_labels.comment') }}</th>
                <th>{{ trans('admin_labels.incident_template_id') }}</th>
                <th>{{ trans('admin_labels.status') }}</th>
                <th>{{ trans('admin_labels.date_exec') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($incidents as $incident )
                <tr>
                    <td>{{ $incident->id }}</td>
                    <td>
                        {{$incident->name}}
                    </td>

                    <td>
                        {{$incident->department ? $incident->department->name : $incident->department_id}}
                    </td>
                    <td>
                        {{$incident->user ? $incident->user->full_name : $incident->user_id}}
                    </td>
                    <td>
                        {{ $incident->comment }}
                    </td>
                    <td>
                        {{$incident->template ? $incident->template->name : $incident->incident_template_id}}
                    </td>
                    <td>
                        {!! trans('admin.'. $entity .'.statuses.'. $incident->status) !!}
                    </td>

                    <td>
                        @if($incident->date_exec)
                            @date($incident->date_exec)
                        @endif
                    </td>
                    <td class="td-actions">
                        @if($incident->permission)
                            <a href="{{route ('admin.'. $entity . '.edit', $incident)}}"
                               class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip"
                               title="{{ trans('admin.filter.edit') }}">
                                <i class="fa fa-edit"></i>
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
