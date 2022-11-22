@if($cities->count())
<div class="table-responsive">
    <table class="table table-condensed table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ trans('admin_labels.status') }}</th>
            <th>{{ trans('admin_labels.city_name') }}</th>
            <th>{{ trans('admin_labels.name_tr') }}</th>
            <th>{{ trans('admin_labels.timezone') }}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($cities as $city)
            <tr >
                <td>{{ $city->id }}</td>
                <td>{!! trans('pretty.statuses.'. $city->status ) !!}</td>
                <td><a href="{{route ('admin.'. $entity . '.edit', $city)}}" class="pjax-link">{{ $city->name }}</a></td>
                <td>{{ $city->name_tr }}</td>
                <td>{{ $city->FullTimezone }}</td>
                <td class="td-actions">
                    <a href="{{route ('admin.'. $entity . '.edit', $city)}}" class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip" title="{{ trans('admin.filter.edit') }}">
                        <i class="fa fa-edit"></i>
                    </a>
                    @if(!Auth::user()->isAgent && !\Auth::user()->isMediator)
                        <a href="{{route ('admin.' . $entity . '.delete', $city)}}"
                           class="btn btn-sm btn-danger js_panel_confirm" data-toggle="tooltip" title="{{trans('admin.filter.delete')}}"><i class="fa fa-trash-o"></i>
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