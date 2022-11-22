@if($coupons->count())
<div class="table-responsive">
    <table class="table table-condensed table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ trans('admin_labels.status') }}</th>
            <th>{{ trans('admin_labels.name') }}</th>
            <th>{{ trans('admin_labels.date_start') }}</th>
            <th>{{ trans('admin_labels.date_finish') }}</th>
            <th>{{ trans('admin_labels.max_uses') }}</th>
            <th>{{ trans('admin_labels.uses') }}</th>
            <th>{{ trans('admin_labels.percent') }}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($coupons as $coupon)
            <tr>
                <td>{{ $coupon->id }}</td>
                <td>{!! trans('pretty.statuses.'. $coupon->status) !!}</td>
                <td><a href="{{route ('admin.'. $entity . '.edit', $coupon)}}" class="pjax-link">{{ $coupon->name }}</a></td>
                <td>@date($coupon->date_start)</td>
                <td>@date($coupon->date_finish)</td>
                <td>{{ $coupon->max_uses }}</td>
                <td>{{ $coupon->uses }}</td>
                <td>{{ $coupon->percent }}</td>
                <td class="td-actions">
                    <a href="{{route ('admin.'. $entity . '.edit', $coupon)}}" class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip" title="{{ trans('admin.filter.edit') }}">
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