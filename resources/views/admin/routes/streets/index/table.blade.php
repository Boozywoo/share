@if($streets->count())
<div class="table-responsive">
    <table class="table table-condensed table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ trans('admin_labels.name') }}</th>
            <th>{{ trans('admin_labels.city_id') }}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($streets as $street)
            <tr>
                <td>{{ $street->id }}</td>
                <td><a href="{{route ('admin.'. $entity . '.edit', $street)}}" class="pjax-link">{{ $street->name }}</a></td>
                <td>{{ $street->city->name }}</td>
                <td class="td-actions">
                    <a href="{{route ('admin.'. $entity . '.edit', $street)}}" class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip" title="{{ trans('admin.filter.edit') }}">
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