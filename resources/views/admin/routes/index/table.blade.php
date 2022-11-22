@if($routes->count())
    <div class="table-responsive">
        <table class="table table-condensed table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('admin_labels.status') }}</th>
                @if(env('EGIS'))
                    <th>{{ trans('admin_labels.egis') }}</th>
                @endif
                <th>{{ trans('admin_labels.name') }}</th>
                <th>{{ trans('admin_labels.stations') }}</th>
                <th>{{ trans('admin_labels.interval') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($routes as $route)
                <tr>
                    <td>{{ $route->id }}</td>
                    <td>{!! trans('pretty.statuses.'. $route->status ) !!}
                        @if ($route->is_taxi || $route->is_route_taxi)
                            <span class="text-warning" data-toggle="tooltip" title="Taxi"><i class="fa-warning fa fa-taxi"></i></span>
                        @endif
                    </td>
                    @if(env('EGIS'))
                        <td>{!! trans('pretty.egis.'. $route->is_egis ) !!}</td>
                    @endif
                    <td><a href="{{route ('admin.'. $entity . '.edit', $route)}}"
                           class="pjax-link">{{ $route->name }}</a></td>
                    <td>
                        @php($city = null)
                        @foreach ($route->stations as $station)
                            @if(($route->is_taxi && $station->status != \App\Models\Station::STATUS_ACTIVE) )@continue  @endif
                            @if($city != $station->city->name)
                                @php($city = $station->city->name)
                                <h3><b>{{$city}}</b></h3>
                            @endif
                            @if ($station->status == 'active')
                                <b>{{$station->name}} ({{$station->street->name ?? 'n/a'}})</b><br>
                            @elseif(!$route->is_taxi)
                                <p>{{$station->name}} ({{$station->street->name ?? 'n/a'}})</p>
                            @endif
                        @endforeach
                        <br>Всего остановок: {{ $route->stations->count() }}
                    </td>
                    <td>{{ $route->getIntervalActive() }} {{ trans('admin.routes.min') }}</td>
                    {{--<td>{{ $route->interval }} {{ trans('admin.routes.min') }}</td>--}}
                    <td class="td-actions">
                        @if(!$route->is_line_price)
                            <a href="{{route ('admin.'. $entity . '.prices', $route)}}"
                               class="btn btn-sm btn-primary" data-toggle="tooltip" title="{{ trans('admin.routes.price') }}">
                                <i class="fa fa-money"></i>
                            </a>
                        @endif
                        @if($route->is_route_taxi)
                            <a href="{{route ('admin.'. $entity . '.intervals', $route )}}"
                               class="btn btn-sm btn-primary" data-toggle="tooltip" title="{{ trans('admin.routes.interval') }}">
                                <i class="fa fa-clock-o"></i>
                            </a>
                        @endif
                        @if(\App\Models\Setting::all()->pluck('is_client_statistic')->first() == 0)
                        <a href="{{route ('admin.'. $entity . '.statics', $route)}}"
                           class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip" title="{{ trans('admin.buses.statics') }}">
                            <i class="fa fa-bus"></i>
                        </a>
                        @endif
                        <a href="{{route ('admin.'. $entity . '.edit', $route)}}"
                           class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip" title="{{ trans('admin.filter.edit') }}">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="javascript:alert('Удаление направлений запрещено. Вместо этого установите статус Неактивный.')" class="btn btn-sm btn-danger" data-toggle="tooltip" title="{{ trans('admin.filter.delete') }}">
                            <i class="fa fa-trash-o "></i>
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