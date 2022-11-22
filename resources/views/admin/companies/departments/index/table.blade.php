@if($departments->count())
    <div class="table-responsive">
        <table class="table table-condensed table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('admin_labels.name') }}</th>
                <th>{{ trans('admin_labels.responsible') }}</th>
                <th>{{ trans('admin.companies.departments.index.count_of_users') }}</th>
                <th>{{ trans('admin.companies.departments.index.count_of_cars') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($departments as  $department)
                <tr>
                    <td>{{ $department->id }}</td>
                    <td><a href="{{route ('admin.'. $entity . '.list', [$company, $department])}}"
                           class="pjax-link">{{ $department->name }}</a>
                    </td>
                    <td>
                        @if ($department->director)
                            <a href="{{route ('admin.users.edit', $department->director)}}" class="pjax-link">{{ $department->director->full_name }}</a>
                        @endif
                    </td>
                    <td>{{ $department->users->count() }}</td>
                    <td>{{ $department->buses->count() }}</td>
                    <td>
                        <a href="{{route ('admin.'. $entity . '.edit', [$company, $department])}}"
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
