@if ($positions->count())
    <div class="table-responsive">
        <table class="table table-condensed table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('admin_labels.name') }}</th>
                <th>{{ trans('admin_labels.count') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($positions as  $position)
                <tr>
                    <td>{{ $position->id }}</td>
                    <td><a href="{{route ('admin.'. $entity . '.list', [$company, $position])}}"
                           class="pjax-link">{{ $position->name }}</a></td>
                    <td>{{ $position->users->count() }}</td>
                    <td>
                        <a href="{{route ('admin.'. $entity . '.edit', [$company, $position])}}"
                           class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip"
                           title="{{trans('admin.filter.edit')}}">
                            <i class="fa fa-edit"></i>
                        </a>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="text-muted">{{trans('admin.users.nothing')}}</p>
@endif
