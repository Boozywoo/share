<style>
    .tooltip-inner{
        width: 350px;
        max-width: 350px;
    }
</style>
@if($wishesList->count())
    <div class="table-responsive">
        <table class="table table-condensed table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('admin_labels.wishes_type') }}</th>
                <th>{{ trans('admin_labels.wishes.status') }}</th>
                <th>{{ trans('admin_labels.wishes.name') }}</th>
                <th>{{ trans('admin_labels.wishes.short_description') }}</th>
                <th>{{ trans('admin_labels.wishes.applicant') }}</th>
                <th>{{ trans('admin_labels.wishes.delegate') }}</th>
                <th>{{ trans('admin_labels.wishes.department_delegate') }}</th>
                <th>{{ trans('admin_labels.wishes.last_comment') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($wishesList as $obj)
                <tr>
                    <td>
                        {{ $obj->id }}
                    </td>
                    <td>
                        {{ $obj->wishesType->name }}
                    </td>
                    <td>
                        {!! $obj->status !!}
                    </td>
                    <td>
                        <a href="{{route ('admin.'. $entity . '.edit', $obj)}}" class="pjax-link">
                            {{ $obj->subject }}
                        </a>
                    </td>
                    <td>
                        {{ $obj->comment }}
                    </td>
                    <td>
                        <a href="{{route ('admin.'. $entity . '.edit', $obj)}}" class="pjax-link">
                            {{ $obj->applicant ? $obj->applicant->fullName : ''}}
                        </a>
                    </td>
                    <td>
                        {{ $obj->delegateName }}
                    </td>
                    <td>
                        {{ $obj->departmentDelegate }}
                    </td>
                    <td nowrap="">
                        <span data-toggle="tooltip"
                              data-original-title="{{ $obj->lastComments }}"
                              data-html="true" data-delay="350" data-placement="auto"
                              aria-describedby="tooltip{{ $obj->id }}">
                            {{ $obj->lastComment }}
                        </span>
                    </td>
                    <td class="td-actions">
                        <a href="{{route ('admin.'. $entity . '.edit', $obj)}}" class="btn btn-sm btn-primary pjax-link"
                           data-toggle="tooltip" title="{{ trans('admin.filter.edit') }}">
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
