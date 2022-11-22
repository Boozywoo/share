@if($types->count())
    <div class="table-responsive">
        <table class="table table-condensed table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('admin_labels.name') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($types as $type)
                <tr>
                    <td>{{ $type->id }}</td>
                    <td>{{ $type->name }}</td>
                     <td class="td-actions">
                                <a href="{{route ('admin.' . $entity . '.delete', $type)}}"
                                   class="btn btn-sm btn-danger js_panel_confirm" data-toggle="tooltip"
                                   title="{{ trans('admin.filter.delete') }}">
                                    <i class="fa fa-trash-o "></i>
                                </a>
                            <a href="{{route ('admin.'. $entity . '.edit', $type)}}"
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
