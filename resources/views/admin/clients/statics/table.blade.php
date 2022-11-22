@if($clients->count())
    <div class="table-responsive">
        <table class="table table-condensed table-hover">
            <thead>
            <tr>
                <th class="text-center">{{ trans('admin_labels.status') }}</th>
                <th>{{ trans('admin_labels.first_name') }}</th>
                <th>{{ trans('admin_labels.routes') }}</th>
                <th>{{ trans('admin_labels.tours') }}</th>
                <th>{{ trans('admin_labels.tours_future') }}</th>
                <th>{{ trans('admin_labels.tours_success') }}</th>
                <th>{{ trans('admin_labels.tours_cancel') }}</th>
                <th>{{ trans('admin_labels.tours_free') }}</th>
                <th class="text-center">{{ trans('admin_labels.reviews') }}</th>
                <th class="text-center">{{ trans('admin_labels.reputation') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($clients as $client)
                <tr>
                    <td class="text-center">{!! trans('pretty.statuses.'. $client->status ) !!}</td>
                    <td>{{ $client->first_name }}</td>
                    <td colspan="6"></td>
                    <td class="text-center">
                        @if($positiveCount = $client->reviewsPositive->count())
                            <i class="fa fa-thumbs-up text-info">{{ $positiveCount }}</i>
                        @endif
                        @if($negativeCount = $client->reviewsNegative->count())
                            <i class="fa fa-thumbs-down text-danger">{{ $negativeCount }}</i>
                        @endif
                        @if($positiveCount && $negativeCount)
                            <br><span class="label">{{ trans('admin.buses.total')}}:</span> {{ $positiveCount + $negativeCount }}
                        @endif
                    </td>
                    <td class="text-center">{!! trans('pretty.reputations.'. $client->reputation ) !!}</td>
                </tr>
                @foreach($client->orders as $order)
                    @if(auth()->user()->routeIds->contains($order->route_id))
                        <tr>
                            <td colspan="2"></td>
                            <td>{{ $order->route_name }}</td>
                            <td>{{ $order->active }}</td>
                            @if(\App\Models\Setting::all()->pluck('is_client_statistic')->first() == 0)
                            <td>{{ $order->waiting }}</td>
                            <td>{{ $order->completed }}</td>
                            <td>{{ $order->disable }}</td>
                            @else
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            @endif
                            
                            <td></td>
                            <td colspan="2"></td>
                        </tr>
                        @if($loop->last)
                            <tr>
                                <td colspan="2"></td>
                                <td><span class="label">{{ trans('admin.buses.total')}}</span></td>
                                <td>{{ $client->orders->sum('active') }}</td>
                                @if(\App\Models\Setting::all()->pluck('is_client_statistic')->first() == 0)
                                <td>{{ $client->orders->sum('waiting') }}</td>
                                <td>{{ $client->orders->sum('completed') }}</td>
                                <td>{{ $client->orders->sum('disable') }}</td>
                                @else
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                @endif
                                <td></td>
                                <td colspan="2"></td>
                            </tr>
                        @endif
                    @endif
                @endforeach
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="text-muted">{{ trans('admin.users.nothing')}}</p>
@endif