@if($statuses->count())
    <div class="table-responsive">
        <table class="table table-condensed table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('admin_labels.status') }}</th>
                <th>{{ trans('admin_labels.name') }}</th>
                <th>{{ trans('admin_labels.cost') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($statuses as $status)
                <tr>
                    <td>{{ $status->id }}</td>
                    <td>{!! trans('pretty.statuses.'. $status->status) !!}</td>
                    <td><a href="{{route ('admin.'. $entity . '.edit', $status)}}"
                           class="pjax-link">{{ $status->name }}</a></td>
                    <td>{{ $status->value }}</td>
                    <td class="td-actions">
                        <a href="{{route ('admin.'. $entity . '.edit', $status)}}"
                           class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip"
                           title="{{ trans('admin.filter.edit') }}">
                            <i class="fa fa-edit"></i>
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