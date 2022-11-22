@if($repairs->count())
<div class="table-responsive">
    <table class="table table-condensed table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ trans('admin_labels.status') }}</th>
            <th>{{ trans('admin_labels.type') }}</th>
            <th>{{ trans('admin_labels.date_from') }}</th>
            <th>{{ trans('admin_labels.date_to') }}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($repairs as $repair)
            <tr>
                <td>{{ $repair->id }}</td>
                <td>{!! trans('pretty.statuses.'. $repair->status) !!}</td>
                <td>{{ trans('admin.buses.repairs.types.'. $repair->type) }}</td>
                <td>
                    @if(!empty($repair->date_from))
                        @date($repair->date_from)
                    @endif
                </td>
                <td>
                    @if(!empty($repair->date_to))
                        @date($repair->date_to)
                    @endif
                </td>
                <td class="td-actions">
                    @if($repair->status == App\Models\Repair::STATUS_REPAIR)
                        <a href="{{route ('admin.'. $entity . '.edit', $repair)}}"
                           class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip"
                           title="{{trans('admin.filter.edit')}}">
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
    <p class="text-muted">{{trans('admin.users.nothing')}}</p>
@endif
