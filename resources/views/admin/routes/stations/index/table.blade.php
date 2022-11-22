@if($stations->count())
<div class="table-responsive">
    <table class="table table-condensed table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ trans('admin_labels.status') }}</th>
            <th>{{ trans('admin_labels.name') }}</th>
            <th>{{ trans('admin_labels.name_tr') }}</th>
            <th>{{ trans('admin_labels.street_id') }}</th>
            <th>{{ trans('admin_labels.city_id') }}</th>
            <th>{{ trans('admin_labels.cords') }}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($stations as $station)
            <tr>
                <td>{{ $station->id }}</td>
                <td>{!! trans('pretty.statuses.'. $station->status ) !!}</td>
                <td><a href="{{route ('admin.'. $entity . '.edit', $station)}}" class="pjax-link">{{ $station->name }}</a></td>
                <td>{{ $station->name_tr }}</td>
                <td>{{ $station->street->name }}</td>
                <td>{{ $station->city->name }}</td>
                <td>{{ $station->latitude }} <br> {{ $station->longitude }}</td>
                <td class="td-actions">
                    <a href="{{route ('admin.'. $entity . '.edit', $station)}}" class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip" title={{ trans('admin.'. $entity . '.edit_button') }}>
                        <i class="fa fa-edit"></i>
                    </a>
                </td>
                 <td class="td-actions">
                    <a href="{{route ('admin.'. $entity . '.copy', $station)}}" class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip" title={{ trans('admin.'. $entity . '.copy_button') }}>
                        <i class="fa fa-plus"></i>
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