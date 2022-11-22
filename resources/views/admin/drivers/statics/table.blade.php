@if($drivers->count())
    <div class="table-responsive">
        <table class="table table-condensed table-hover">
            <thead>
            <tr>
                <th class="text-center">{{ trans('admin_labels.status') }}</th>
                <th>{{ trans('admin_labels.full_name') }}</th>
                <th>{{ trans('admin_labels.routes') }}</th>
                <th>{{ trans('admin_labels.tours') }}</th>
                <th>{{ trans('admin_labels.work_days') }}</th>
                <th>{{ trans('admin_labels.reviews') }}</th>
                <th class="text-center">{{ trans('admin_labels.reputation') }}</th>
            </tr>
            </thead>
            <tbody>
            @php($resultTour = 0)
            @php($resultWorkDay = 0)
            @foreach($drivers as $driver)
                @php($trCountTour = 0)
                @php($trCountWorkDay = 0)
                <tr>
                    <td class="text-center">{!! trans('pretty.statuses.'. $driver->status ) !!}</td>
                    <td>
                        <a href="{{route ('admin.' . $entity . '.pays', $driver)}}" class="pjax-link">
                            {{ $driver->full_name }}
                        </a>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        @if($positiveCount = $driver->reviewsPositive->count())
                            <i class="fa fa-thumbs-up text-info">{{ $positiveCount }}</i>
                        @endif
                        @if($negativeCount = $driver->reviewsNegative->count())
                            <i class="fa fa-thumbs-down text-danger">{{ $negativeCount }}</i>
                        @endif
                        @if($positiveCount && $negativeCount)
                            <br><span class="label">{{ trans('admin.buses.total')}}:</span> {{ $positiveCount + $negativeCount }}
                        @endif
                    </td>
                    <td class="text-center">{!! trans('pretty.reputations.'. $driver->reputation ) !!}</td>
                </tr>
                @foreach($driver->routes as $route)
                    @php($countTour = $route->tours->count())
                    @php($trCountTour += $countTour)
                    @php($countWorkDay = $route->tours->groupBy(function($item) { return $item->date_start->format('d-m-Y');})->count())
                    @php($trCountWorkDay += $countWorkDay)
                    <tr>
                        <td colspan="2"></td>
                        <td>{{ $route->name }}</td>
                        <td>{{ $countTour }}</td>
                        <td class="text-center">{{ $countWorkDay }}</td>
                        <td colspan="2"></td>
                    </tr>
                @endforeach
                @if($trCountTour || $trCountWorkDay)
                    <tr>
                        <td colspan="2"></td>
                        <td><span class="label">{{ trans('admin.buses.total')}}</span></td>
                        <td>{{ $trCountTour }}</td>
                        <td class="text-center">{{ $trCountWorkDay }}</td>
                        <td colspan="2"></td>
                    </tr>
                    @php($resultTour += $trCountTour)
                    @php($resultWorkDay += $trCountTour)
                @endif
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="2"></td>
                <td><span class="label label-primary">{{trans('admin.buses.total')}}</span></td>
                <td>{{ $resultTour }}</td>
                <td class="text-center">{{ $resultWorkDay }}</td>
                <td colspan="2"></td>
            </tr>
            </tfoot>
        </table>
    </div>
@else
    <p class="text-muted">{{trans('admin.users.nothing')}}</p>
@endif
