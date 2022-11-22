@if($tours->count())
    <div class="table-responsive">
        <table class="table table-condensed table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('admin_labels.status') }}</th>
                <th>{{ trans('admin_labels.date_start') }}</th>
                <th>{{ trans('admin_labels.time_start') }}</th>
                <th>{{ trans('admin_labels.time_finish') }}</th>
                <th>{{ trans('admin_labels.route_id') }}</th>
                <th>{{ trans('admin_labels.bus_id') }}</th>
                <th class="text-center">{{ trans('admin_labels.free_places') }}</th>
                <th>{{ trans('admin_labels.pool') }}</th>
                <th>{{ trans('admin_labels.price') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($tours as $tour)
                <tr>
                    <td>
                        {{ $tour->id }}
                    </td>
                    <td>
                        {!! trans('pretty.statuses.'. $tour->status ) !!}
                        @if($tour->comment)
                            <i class="fa fa-comment" data-toggle="tooltip"  title="{{ $tour->comment }}"></i>
                        @endif
                    </td>
                    <td>@date($tour->date_start)</td>
                    <td>{{ $tour->prettyTimeStart }}</td>
                    <td>{{ $tour->prettyTimeFinish }}</td>
                    <td>{{ $tour->route->name }}</td>
                    <td>{{ $tour->bus->number }} <span class="font-bold">{{ $tour->bus->places }} мест</span></td>
                    <td class="text-center">{{ $tour->freePlacesCount }}</td>
                    <td>{{ $tour->ordersPull->sum('count_places') }}</td>
                    @if($isMediator)
                        <td>{{ $tour->price + (isset($userRoutes[$tour->route->id]) ? $userRoutes[$tour->route->id]->pivot->added_price : 0)}}</td>
                    @else
                        <td>{{ $tour->price }}</td>
                    @endif
                    <td class="td-actions">
                        <span data-url="{{route ('admin.tours.showPopup', $tour)}}"  data-toggle="modal" data-target="#popup_tour-edit"
                              class="btn btn-sm btn-primary" >
                            <i class="fa fa-edit"></i>
                        </span>
                        <a href="{{route ('admin.tours.show', $tour)}}" class="btn btn-sm btn-warning pjax-link" >
                            <i class="fa fa-eye"></i>
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