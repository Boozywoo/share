@if($pages->count())
<div class="table-responsive">
    <table class="table table-condensed table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ trans('admin_labels.title') }}</th>
            <th>{{ trans('admin_labels.slug') }}</th>
            <th>{{ trans('admin_labels.created_at') }}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($pages as $page)
            <tr>
                <td>{{ $page->id }}</td>
                <td><a href="{{route ('admin.pages.edit', $page)}}" class="pjax-link">{{ $page->title }}</a></td>
                <td>{!! Form::goToUrl($page->url) !!}</td>
                <td>{{ $page->created_at }}</td>
                <td class="td-actions">
                    <a href="{{route ('admin.pages.edit', $page)}}" class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip" title="{{trans('admin.filter.edit')}}">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a href="{{route ('admin.pages.delete', $page)}}" class="btn btn-sm btn-danger js_panel_confirm" data-toggle="tooltip" title="{{trans('admin.filter.delete')}}">
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