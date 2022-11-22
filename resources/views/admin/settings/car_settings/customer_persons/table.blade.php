<div class="ibox-content">

    <h2>{{ trans('admin.'. $entity . '.customer_persons.list') }}</h2>
    <div class="hr-line-dashed"></div>

    @if($customerPersonalities->count())
        <div class="js_table-wrapper">

            <div class="table-responsive">
                <table class="table table-condensed table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ trans('admin_labels.name') }}</th>
{{--                        <th>{{ trans('admin_labels.slug') }}</th>--}}
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($customerPersonalities as $customerPersonality)
                        <tr>
                            <td>{{ $customerPersonality->id }}</td>
                            <td>{{ $customerPersonality->name }}</td>
{{--                            <td>{{ $customerPersonality->slug }}</td>--}}
                            <td class="td-actions">
                                <a href="{{route ('admin.'. $entity . '.customer_persons.edit', $customerPersonality)}}"
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
