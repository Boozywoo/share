@if($agreements->count())
<div class="table-responsive">
    <table class="table table-condensed table-hover">
        <thead>
        <tr>
            <th></th>
            <th>{{ trans('admin_labels.number') }}</th>
            <th>{{ trans('admin_labels.name') }}</th>
            <th>{{ trans('admin_labels.date') }}</th>
            {{--<th>{{ trans('admin_labels.date_start') }}</th>
            <th>{{ trans('admin_labels.date_finish') }}</th--}}>
            <th>{{ trans('admin_labels.customer_company_id') }}</th>
            <th>{{ trans('admin_labels.service_company_id') }}</th>
            <th>{{ trans('admin_labels.description') }}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        @foreach($agreements as $agreement)
            @php($status = $agreement->status ? 'active' : 'disable')
            <tr>
                <td>{!! trans('pretty.statuses.'.$status ) !!}</td>
                <td><a href="{{route ('admin.'. $entity . '.edit', $agreement)}}" class="pjax-link">{{ $agreement->number }}</a></td>
                <td>{{$agreement->name}}</td>
                <td>{{$agreement->date ? $agreement->date->format('Y-m-d') : null}}</td>
                {{--<td>{{$agreement->date_start->format('Y-m-d')}}</td>
                <td>{{$agreement->date_end->format('Y-m-d')}}</td>--}}
                <td>{{$agreement->customerCompany->name ?? ''}}</td>
                <td>{{$agreement->serviceCompany->name ?? ''}}</td>
                <td>@textarea($agreement->description)</td>
                <td class="td-actions">
                    <a href="{{route ('admin.'. $entity . '.edit', $agreement)}}" class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip" title="{{ trans('admin.filter.edit') }}">
                        <i class="fa fa-edit"></i>
                    </a>
                    @if(!$agreement->enabled)
                    <a href="{{route ('admin.' . $entity . '.delete', $agreement)}}"
                       class="btn btn-sm btn-danger js_panel_confirm" data-toggle="tooltip" title="{{ trans('admin.filter.delete') }}">
                        <i class="fa fa-trash-o "></i>
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