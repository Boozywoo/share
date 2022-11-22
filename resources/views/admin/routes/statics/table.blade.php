@if($buses->count())
<div class="table-responsive">
    <table class="table table-condensed table-hover">
        <thead>
            <tr>
                <th class="text-center">{{ trans('admin_labels.status') }}</th>
                <th>{{ trans('admin_labels.name') }}</th>
                <th>{{ trans('admin_labels.number') }}</th>
                <th>{{ trans('admin_labels.company_id') }}</th>
                <th>{{ trans('admin_labels.appearance') }}</th>
                <th>{{ trans('admin_labels.no_appearance') }}</th>
                <th>{{ trans('admin_labels.sum') }}</th>
                <th>{{ trans('admin_labels.tours') }}</th>
            </tr>
        </thead>
        <tbody>
            @php($resultTour = 0)
            @php($resultPrice = 0)
            @php($resultAppearance = 0)
            @php($resultNoAppearance = 0)
            @foreach($buses as $bus)
            @php($route = $bus->routes->first())
            @php($orders = $route->tours->where('status', \App\Models\Tour::STATUS_COMPLETED)->pluck('orders')->collapse()->where('status', \App\Models\Order::STATUS_ACTIVE))
            @php($priceTour = $orders->pluck('orderPlaces')->collapse()->where('appearance', true)->sum('price'))
            @php($countTour = $route->tours->count())
            @php($countAppearance = $orders->sum('count_places_appearance'))
            @php($countNoAppearance = $orders->sum('count_places_no_appearance'))
            <tr>
                <td class="text-center">{!! trans('pretty.statuses.'. $bus->status ) !!}</td>
                <td>{{ $bus->name }}</td>
                <td>{{ $bus->number }}</td>
                <td>{{ $bus->company->name }}</td>
                @if(\App\Models\Setting::all()->pluck('is_client_statistic')->first() == 0)
                <td>{{$countAppearance}}</td>
                <td>{{$countNoAppearance}}</td>
                <td>{{$priceTour}}</td>
                @else
                <td>0</td>
                <td>0</td>
                <td>0</td>
                @endif
                
                <td>{{ $countTour }}</td>
            </tr>
            @php($resultTour += $countTour)
            @php($resultPrice += $priceTour)
            @php($resultAppearance += $countAppearance)
            @php($resultNoAppearance += $countNoAppearance)
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"></td>
                <td><span class="label label-primary">Итого</span></td>
                @if(\App\Models\Setting::all()->pluck('is_client_statistic')->first() == 0)
                <td>{{$resultAppearance}}</td>
                <td>{{$resultNoAppearance}}</td>
                @else
                <td>0</td>
                <td>0</td>
                @endif
                <td>{{$resultPrice}}</td>
                <td>{{ $resultTour }}</td>
            </tr>
        </tfoot>
    </table>
</div>
@else
<p class="text-muted">{{ trans('admin.users.nothing') }}</p>
@endif