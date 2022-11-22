@if($rates->count())
    <div class="table-responsive">
        <table class="table table-condensed table-hover">
            <thead>
            <tr>
                <th>{{ trans('admin_labels.min') }}</th>
                <th>{{ trans('admin_labels.max') }}</th>
                <th>{{ trans('admin_labels.cost') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($rates as $rate)
                <tr>
                    <td>{{ $rate->min }}</td>
                    <td>{{ $rate->max }}</td>
                    <td>{{ $rate->cost }}</td>
                    <td class="td-actions">
                        <a href="{{route ('admin.'. $entity . '.edit', $rate)}}"
                           class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip" title="{{ trans('admin.filter.edit') }}">
                            <i class="fa fa-edit"></i>
                        </a>
                        @if ($loop->last)
                            <a href="{{route ('admin.'. $entity . '.delete', $rate)}}"
                               class="btn btn-sm btn-danger pjax-link js_panel_confirm" data-toggle="tooltip"
                               title="{{trans('admin.filter.delete')}}"
                               data-success="{{ trans('admin.'. $entity . '.delete') }}"
                               data-question="{{ trans('admin.'. $entity . '.question_del') }}">
                                <i class="fa fa-trash-o"></i>
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