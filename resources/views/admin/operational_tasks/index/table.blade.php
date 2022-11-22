<style>
  .tooltip-inner{
    width: 350px;
    max-width: 350px;
  }
</style>
@if($tasks->count())
    <div class="table-responsive">
        <table class="table table-condensed table-hover">
            <thead>
            <tr>
                <th>#</th>
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
            @foreach($tasks as $obj)
                <tr>
                    <td>
                        {{ $obj->id }}
                    </td>
                    <td>
                        {!! $obj->status !!}
                    </td>
                    <td>
                        <a href="{{route ('admin.operational_tasks.show', $obj)}}" class="pjax-link">
                            {{ $obj->subject }}
                        </a>
                    </td>
                    <td>
                        {{ $obj->description }}
                    </td>
                    <td>
                        <a href="{{route ('admin.operational_tasks.show', $obj)}}" class="pjax-link">
                            {{$obj->applicant->full_name}}
                        </a>
                    </td>
                    <td>
                        {{$obj->responsible->full_name}}
                    </td>
                    <td>
                        {{ $obj->responsible->departament->name }}
                    </td>
                    <td nowrap="">
                        @if($obj->lastComment)
                            <span data-toggle="tooltip"
                                  data-original-title="
                                  <br>{{$obj->lastComment->comment}}
                                  <br>{{$obj->lastComment->user->full_name}}
                                  <br>{{$obj->lastComment->created_at->format('d.m.Y H:i')}}
                                  <br>
                                  "
                                  data-html="true" data-delay="350" data-placement="auto">
                                {{ $obj->lastComment->comment }}
                            </span>
                        @endif
                    </td>
                    <td class="td-actions">
                        <a href="{{route ('admin.operational_tasks.edit', $obj)}}" class="btn btn-sm btn-primary pjax-link"
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
