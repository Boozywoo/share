@if($templates->count())
<div class="table-responsive">
    <table class="table table-condensed table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ trans('admin_labels.name') }}</th>
            <th>{{ trans('admin_labels.count_places') }}</th>
            <th>{{ trans('admin_labels.template_id') }}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($templates as $template)
            <tr>
                <td>{{ $template->id }}</td>
                <td>{{ $template->name }}</td>
                <td>{{ $template->count_places }}</td>
                <td>@include('admin.buses.templates.partials.template')</td>
                <td class="td-actions">
                    {{--<a href="{{route ('admin.'. $entity . '.edit', $template)}}" class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip" title="{{trans('admin.filter.edit')}}">--}}
                        {{--<i class="fa fa-edit"></i>--}}
                    {{--</a>--}}
                    <a href="{{route ('admin.' . $entity . '.delete', $template)}}" class="btn btn-sm btn-danger js_panel_confirm" data-toggle="tooltip" title="{{trans('admin.filter.delete')}}"
                        data-success="{{ trans('admin.'. $entity . '.delete') }}"data-question="{{ trans('admin.'. $entity . '.question_del') }}">
                        <i class="fa fa-trash-o "></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@else
    <p class="text-muted">{{trans('admin.users.nothing')}}</p>
@endif