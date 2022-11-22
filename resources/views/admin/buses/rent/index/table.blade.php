@if($rents->count())
<div class="table-responsive">
    <table class="table table-condensed table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ trans('admin_labels.from_hour') }}</th>
            <th>{{ trans('admin_labels.to_hour') }}</th>
            <th>{{ trans('admin_labels.cost') }}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($rents as $rent)
            <tr>
                <td>{{ $rent->id }}</td>
                <td>{{$rent->from_hour}}</td>
                <td>{{$rent->to_hour}}</td>
                <td>{{$rent->cost}}</td>
                <td class="td-actions">
                    <a href="{{route ('admin.'. $entity . '.edit', $rent)}}" class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip" title="{{ trans('admin.filter.edit') }}">
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