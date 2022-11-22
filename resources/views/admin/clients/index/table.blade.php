@if($clients->count())
    <div class="table-responsive">
        <table class="table table-condensed">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('admin_labels.status') }}</th>
                <th class="text-center">{{ trans('admin_labels.register') }}</th>
                <th>{{ trans('admin_labels.last_name') }}</th>
                <th>{{ trans('admin_labels.first_name') }}</th>
                <th>{{ trans('admin_labels.passport') }}</th>
                <th>{{ trans('admin_labels.cli_email') }}</th>
                <th>{{ trans('admin_labels.phone') }}</th>
                <th class="text-center">{{ trans('admin_labels.reputation') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($clients as $client)
                <tr>
                    <td>{{ $client->id }}</td>
                    <td>{!! trans('pretty.statuses.'. $client->status ) !!}</td>
                    <td class="text-center">{{ $client->register ? trans('admin.home.yes') : trans('admin.home.no')}}</td>
                    <td><a href="{{route ('admin.' . $entity . '.edit', $client)}}" class="pjax-link">{{ $client->last_name}}</a></td>
                    <td><a href="{{route ('admin.' . $entity . '.edit', $client)}}" class="pjax-link">{{ $client->first_name}}</a></td>
                    <td>{{ $client->passport}}</td>
                    <td><i class="fa fa-email"></i> {{ $client->email }}</td>
                    <td>
                        <div class="dropdown">
                          <button class="btn btn-default text-success" style="border:0px;" 
                                data-toggle="dropdown">
                                <i class="text-success fa fa-phone"></i>{{ $client->phone ? $client->phone : '' }}
                          </button>
                          <ul class="dropdown-menu">
                            <li><a href="{{ route('admin.calls.out') }}/{{$client->phone}}">{{ trans('admin.users.call')}}</a></li>
                          </ul>
                        </div>
                    </td>
                    <td class="text-center">{!! trans('pretty.reputations.'. $client->reputation ) !!}</td>
                    <td class="td-actions">
                        <a href="{{route ('admin.' . $entity . '.edit', $client)}}"
                           class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip" title="{{ trans('admin.filter.edit')}}">
                            <i class="fa fa-edit"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="text-muted">{{ trans('admin.users.nothing')}}</p>
@endif