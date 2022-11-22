@if($roles->count())
    <div class="table-responsive">
        <table class="table table-condensed table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('admin_labels.name') }}</th>
                <th>{{ trans('admin_labels.slug') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->slug }}</td>
                    <td>{{ $role->company ? $role->company->name : trans('admin.settings.roles.not_assigned') }}</td>
                    <td class="td-actions">
                        @if($role->company)
                            @if(!$role->users->count())
                                <a href="{{route ('admin.' . $entity . '.delete', $role)}}"
                                   class="btn btn-sm btn-danger js_panel_confirm" data-toggle="tooltip"
                                   title="{{ trans('admin.filter.delete') }}">
                                    <i class="fa fa-trash-o "></i>
                                </a>

                            @endif
                            <a href="{{route ('admin.'. $entity . '.edit', $role)}}"
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
