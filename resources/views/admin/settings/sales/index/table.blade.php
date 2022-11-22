@if($sales->count())
<div class="table-responsive">
    <table class="table table-condensed table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ trans('admin_labels.status') }}</th>
            <th>{{ trans('admin_labels.name') }}</th>
            <th>{{ trans('admin_labels.date_start') }}</th>
            <th>{{ trans('admin_labels.date_finish') }}</th>
            <th>{{ trans('admin_labels.type') }}</th>
            <th>{{ trans('admin_labels.count') }}</th>
            <th>{{ trans('admin_labels.value') }}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($sales as $sale)
            <tr>
                <td>{{ $sale->id }}</td>
                <td>{!! trans('pretty.statuses.'. $sale->status) !!}</td>
                <td><a href="{{route ('admin.'. $entity . '.edit', $sale)}}" class="pjax-link">{{ $sale->name }}</a></td>
                <td>@date($sale->date_start)</td>
                <td>@date($sale->date_finish)</td>
                <td>{{ trans('admin.settings.sales.types.'. $sale->type) }}</td>
                <td>{{ $sale->count }}</td>
                <td>{{ $sale->value }}</td>
                <td class="td-actions">
                    <a href="{{route ('admin.'. $entity . '.edit', $sale)}}" class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip" title="{{ trans('admin.filter.edit') }}">
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