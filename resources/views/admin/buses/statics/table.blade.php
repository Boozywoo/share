<a href="{{route('admin.buses.export')."?name=".request('name')."&status=".request('status')."&company_id=".request('company_id')."&date_from=".request('date_from')."&date_to=".request('date_to')}}">
    <span data-toggle="modal" class="btn btn-sm btn-primary">{{ trans('admin.'. $entity . '.export_stat') }}</span>
</a>
@if($buses->count())
    <div class="table-responsive">
        <table class="table table-condensed table-hover">
            <thead>
            <tr>
                <th class="text-center">{{ trans('admin_labels.status') }}</th>
                <th>{{ trans('admin_labels.name') }}</th>
                <th>{{ trans('admin_labels.number') }}</th>
                <th>{{ trans('admin_labels.company_id') }}</th>
                <th>{{ trans('admin_labels.routes') }}</th>
                <th>{{ trans('admin_labels.appearance') }}</th>
                <th>{{ trans('admin_labels.no_appearance') }}</th>
                <th>{{ trans('admin_labels.sum_cash') }}</th>
                <th>{{ trans('admin_labels.sum_cashless_payments') }}</th>
                <th>{{ trans('admin_labels.sum_payment_to_ca') }}</th>
                <th>{{ trans('admin_labels.sum_online_pay') }}</th>
                <th>{{ trans('admin_labels.sum') }}</th>
                <th>{{ trans('admin_labels.tours') }}</th>
                <th></th>

                {{--<th class="text-center">{{ trans('admin_labels.work_days') }}</th>
                <th class="text-center">{{ trans('admin_labels.repair_days') }}</th>--}}
            </tr>
            </thead>
            <tbody>
            @php($resultTour = 0)
            @php($resultTypePay = '')
            @php($resultWorkDay = 0)
            @php($resultRepairDay = 0)

            @php($resultcashpayment = 0)
            @php($resultcashlesspayment = 0)
            @php($resultcheckingaccount = 0)
            @php($resultsuccess = 0)
            @php($resultPrice = 0)
            @php($resultAppearance = 0)
            @php($resultNoAppearance = 0)
            @foreach($buses as $bus)

                @php($trCountTour = 0)
                @php($trCountWorkDay = 0)
                @php($trPrice = 0)
                @php($trPrice_cashpayment = 0)
                @php($trPrice_cashlesspayment = 0)
                @php($trPrice_checkingaccount = 0)
                @php($trPrice_success = 0)
                @php($trTypePay = '')
                @php($trAppearance = 0)
                @php($trNoAppearance = 0)
                @php($trCountRepairDay = $bus->repairs->count())
                <tr>
                    <td class="text-center">{!! trans('pretty.statuses.'. $bus->status ) !!}</td>
                    <td>{{ $bus->name }}</td>
                    <td>{{ $bus->number }}</td>
                    <td>{{ $bus->company->name }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <a href="{{route('admin.buses.export')."?bus_id=".$bus->id."&name=".request('name')."&status=".request('status')."&company_id=".request('company_id')."&date_from=".request('date_from')."&date_to=".request('date_to')}}">
                           <span data-toggle="modal" class="btn btn-sm btn-primary"><i class="fa fa-file-excel-o"></i></span>
                        </a>
                    </td>
                    {{--<td></td>--}}
                    {{--<td class="text-center">{{ $trCountRepairDay }}</td>--}}
                </tr>

                @foreach($bus->routes as $route)
                    @if(auth()->user()->routeIds->contains($route->id))

                        @php($orders = $route->tours->where('status', \App\Models\Tour::STATUS_COMPLETED)->pluck('orders')->collapse()->where('status', \App\Models\Order::STATUS_ACTIVE))


                        @php($cashpayment = $orders->whereIn('type_pay', ['cash-payment',''])->pluck('orderPlaces')->collapse()->where('appearance', true)->sum('price') )
                        @php($cashlesspayment =$orders->where('type_pay', 'cashless_payment')->pluck('orderPlaces')->collapse()->where('appearance', true)->sum('price') )
                        @php($checkingaccount = $orders->where('type_pay', 'checking_account')->pluck('orderPlaces')->collapse()->where('appearance', true)->sum('price') )
                        @php($success = $orders->where('type_pay', 'success')->pluck('orderPlaces')->collapse()->where('appearance', true)->sum('price') )


                        @php($priceTour = $orders->pluck('orderPlaces')->collapse()->where('appearance', true)->sum('price'))
                        @php($countTour = $route->tours->count())
                        @php($countAppearance = $orders->sum('count_places_appearance'))
                        @php($countNoAppearance = $orders->sum('count_places_no_appearance'))
                        @php($trCountTour += $countTour)
                        @php($trPrice += $priceTour)
                        @php($trPrice_cashpayment += $cashpayment)
                        @php($trPrice_cashlesspayment += $cashlesspayment)
                        @php($trPrice_checkingaccount += $checkingaccount)
                        @php($trPrice_success += $success)
                        @php($trAppearance += $countAppearance)
                        @php($trNoAppearance += $countNoAppearance)
                        @php($countWorkDay = $route->tours->groupBy(function($item) { return $item->date_start->format('d-m-Y');})->count())
                        @php($trCountWorkDay += $countWorkDay)
                        <tr>
                            <td colspan="4"></td>
                            <td>{{ $route->name }}</td>
                            <td>{{$countAppearance}}</td>
                            <td>{{$countNoAppearance}}</td>
                            <td>{{$cashpayment}}</td>
                            <td>{{$cashlesspayment}}</td>
                            <td>{{$checkingaccount}}</td>
                            <td>{{$success}}</td>
                            <td>{{$priceTour}}</td>
                            <td>{{ $countTour }}</td>
                            <td></td>
                            {{--<td class="text-center">{{ $countWorkDay }}</td>
                            <td></td>--}}
                        </tr>
                        @if($loop->last)
                            <tr>
                                <td colspan="4"></td>
                                <td><span class="label">{{trans('admin.buses.total')}}</span></td>
                                <td>{{$trAppearance}}</td>
                                <td>{{$trNoAppearance}}</td>
                                <td>{{$trPrice_cashpayment}}</td>
                                <td>{{$trPrice_cashlesspayment}}</td>
                                <td>{{$trPrice_checkingaccount}}</td>
                                <td>{{$trPrice_success}}</td>
                                <td>{{$trPrice}}</td>
                                <td>{{ $trCountTour }}</td>
                                {{--<td class="text-center">{{ $trCountWorkDay }}</td>--}}
                                {{--<td></td>--}}
                            </tr>
                            @php($resultTour += $trCountTour)
                            @php($resultWorkDay += $trCountWorkDay)
                            @php($resultAppearance += $trAppearance)
                            @php($resultNoAppearance += $trNoAppearance)
                            @php($resultPrice += $trPrice)

                            @php($resultcashpayment += $trPrice_cashpayment)
                            @php($resultcashlesspayment += $trPrice_cashlesspayment)
                            @php($resultcheckingaccount += $trPrice_checkingaccount)
                            @php($resultsuccess += $trPrice_success)
                        @endif
                    @endif
                @endforeach
                @php($resultRepairDay += $trCountRepairDay)
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="4"></td>
                <td><span class="label label-primary">{{ trans('admin.buses.total')}}</span></td>
                <td>{{$resultAppearance}}</td>
                <td>{{$resultNoAppearance}}</td>
                <td>{{$resultcashpayment}}</td>
                <td>{{$resultcashlesspayment}}</td>
                <td>{{$resultcheckingaccount}}</td>
                <td>{{$resultsuccess}}</td>
                <td>{{$resultPrice}}</td>
                <td>{{ $resultTour }}</td>
                {{--<td class="text-center">{{ $resultWorkDay }}</td>
                <td class="text-center">{{ $resultRepairDay }}</td>--}}
            </tr>
            </tfoot>
        </table>
    </div>
@else
    <p class="text-muted">{{trans('admin.users.nothing')}}</p>
@endif