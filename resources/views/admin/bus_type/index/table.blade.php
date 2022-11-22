@if($busTypes->count())
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
            @foreach($busTypes as $busType)
                <tr>
                    <td>{{ $busType->id }}</td>
                    <td><a href="{{route ('admin.'. $entity . '.edit', $busType)}}" class="pjax-link">{{ $busType->name }}</a></td>
                    <td class="td-actions">
                        <a href="{{route ('admin.'. $entity . '.edit', $busType)}}" class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip" title="{{ trans('admin.filter.edit') }}">
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