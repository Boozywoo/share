<div class="ibox-content">
    <h2>{{ trans('admin.'. $entity . '.car_colors.list') }}</h2>
    <div class="hr-line-dashed"></div>

    @if($colors->count())
        <div class="js_table-wrapper">

            <div class="table-responsive">
                <table class="table table-condensed table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ trans('admin_labels.name') }}</th>
                        <th>{{ trans('admin_labels.slug') }}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($colors as $color)
                        <tr>
                            <td>{{ $color->id }}</td>
                            <td>{{ $color->name }}</td>
                            <td>{{ $color->slug }}</td>
                            <td class="td-actions">
                                <a href="{{route ('admin.'. $entity . '.car_colors.edit', $color)}}"
                                   class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip"
                                   title="{{ trans('admin.filter.edit') }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <p class="text-muted">{{ trans('admin.users.nothing') }}</p>
    @endif
</div>
