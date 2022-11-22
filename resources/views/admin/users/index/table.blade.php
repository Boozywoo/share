@if($users->count())
    <div class="table-responsive">
        <table class="table table-condensed">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('admin_labels.first_name') }}</th>
                <th>{{ trans('admin_labels.cli_email') }}</th>
                <th>{{ trans('admin_labels.phone') }}</th>
                <th class="text-center">{{ trans('admin_labels.status') }}</th>
                <th>{{ trans('admin_labels.permission') }}</th>
                <th>{{ trans('admin_labels.companies') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            @foreach($users->load('roles', 'companies') as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td><a href="{{route ('admin.' . $entity . '.edit', $user)}}" class="pjax-link">{{ $user->first_name}}</a></td>
                    <td><i class="fa fa-email"></i> {{ $user->email }}</td>
                    <td>
                        <div class="dropdown">
                          <button class="btn btn-default text-success admin-panel_item-transparent-bg" style="border:0px;" 
                                data-toggle="dropdown">
                                <i class="text-success fa fa-phone"></i>@phone($user->phone)
                          </button>
                          <ul class="dropdown-menu">
                            <li><a href="{{ route('admin.calls.out') }}/{{$user->phone}}">{{trans('admin.users.call')}}</a></li>
                          </ul>
                        </div>
                    </td>
                    <td class="text-center">{!! trans('pretty.statuses.'. $user->status ) !!}</td>
                    <td>{{ $user->roles->first() ? $user->roles->first()->name : '' }}</td>
                    <td>{{ isset($user->company) ? $user->company->name : '' }}{{ isset($user->department) ? ', ' . $user->department->name : '' }}{{ isset($user->position) ? ', ' . mb_strtolower($user->position->name) : '' }}</td>
                    <td class="td-actions">
                        <a href="{{route ('admin.' . $entity . '.edit', $user)}}"
                           class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip" title="{{trans('admin.filter.edit')}}">
                            <i class="fa fa-edit"></i>
                        </a>
                        @if($user->hasRole('operator') or $user->hasRole('agent'))
                            <a href="{{route ('admin.' . $entity . '.pays', $user)}}"
                               class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip" title="{{trans('admin.salary.payments')}}">
                                <i class="fa fa-money"></i>
                            </a>
                        @endif
                        @if(Auth::user()->roles->first()->slug == 'admin' or Auth::user()->roles->first()->slug == 'superadmin')
                            <a href="{{route ('admin.' . $entity . '.delete', $user)}}"
                                class="btn btn-sm btn-danger js_panel_confirm" data-toggle="tooltip" title="{{ trans('admin.filter.delete') }}"
                                data-success="{{trans('admin.users.delete')}}" data-question="{{trans('admin.users.question_del')}}">
                            <i class="fa fa-trash-o"></i>
                        @endif
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

<!-- <script type="text/javascript">
    $( ".call_out").click(function() {
        var number = $(this).parents(".dropup").children(".number").text();
        number = number.replace(/\D/g, '');
        alert(number);
    });
</script> -->