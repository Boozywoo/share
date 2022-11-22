@if($tariffs->count())
    <div class="table-responsive">
        <table class="table table-condensed table-hover">
            <thead>
            <tr>
                <th></th>
                <th>{{ trans('admin_labels.name') }}</th>
                <th>{{ trans('admin_labels.bus_type_id') }}</th>
                <th>{{ trans('admin_labels.type') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($tariffs as $tariff)
                <tr>
                    <td>{!! trans('pretty.statuses.'. $tariff->status ) !!}</td>
                    <td>{{ $tariff->name }}</td>
                    <td>{{ $tariff->busType ? $tariff->busType->name : 'Применяется ко всем' }}</td>
                    <td><b>{!! trans('admin.tariffs.types')[$tariff->type] !!}</b></td>
                    <td class="td-actions">
                        <a href="{{route ('admin.'. $entity . '.edit', $tariff)}}"
                           class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip" title="{{ trans('admin.filter.edit') }}">
                            <i class="fa fa-edit"></i>
                        </a>
                        {{--<a href="{{route ('admin.'. $entity . '.delete', $tariff)}}"
                           class="btn btn-sm btn-danger js_panel_confirm" data-toggle="tooltip" title="{{ trans('admin.filter.delete') }}"
                           data-success="Тариф успешно удалён!"
                           data-question="Вы действительно хотите удалить тариф?">
                            <i class="fa fa-trash-o"></i>
                        </a>--}}
                        <a href="{{route ('admin.'. $entity . '.rates', $tariff)}}"
                           class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip" title="Тарифные ставки">
                            <i class="fa fa-clock-o"></i>
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