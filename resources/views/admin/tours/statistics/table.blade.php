<a href="{{ route('admin.tours.statistic.excel', 
    ['status' => request('status'), 'date_from' => request('date_from'), 'date_to' => request('date_to')]) }}">
    <span data-toggle="modal" class="btn btn-sm btn-primary">{{ trans('admin.buses.export_stat') }}</span>
</a>
@if($tours->count())
<div class="table-responsive">
    <table class="table table-condensed table-hover">
        <thead>
        <tr>
            <th class="text-center">{{ trans('admin_labels.status') }}</th>
            <th>{{ trans('admin_labels.date') }}</th>
            <th>{{ trans('admin_labels.day_of_week') }}</th>
            <th>{{ trans('admin_labels.holiday') }}/{{ trans('admin_labels.work_day') }}</th>
            <th>{{ trans('admin_labels.planned_time') }}</th>
            <th>{{ trans('admin_labels.actual_time') }}</th>
            <th>{{ trans('admin.routes.single') }}</th>
            <th>{{ trans('admin_labels.bus_id') }}</th>
            <th>{{ trans('admin_labels.driver_id') }}</th>
            <th>{{ trans('admin_labels.count_places') }}</th>
            <th>{{ trans('admin_labels.company_id') }}</th>
            <th>{{ trans('admin_labels.cnt_passengers') }}</th>
            <th>{{ trans('admin_labels.sum_cash') }}</th>
            <th>{{ trans('admin_labels.cash_payment_office') }}</th>
            <th>{{ trans('admin_labels.cash_payment_child') }}</th>
            <th>{{ trans('admin_labels.cashless_payment_child') }}</th>
            <th>{{ trans('admin_labels.sum_cashless_payments') }}</th>
            <th>{{ trans('admin_labels.sum_payment_to_ca') }}</th>
            <th>{{ trans('admin_labels.sum_online_pay') }}</th>
            <th>{{ trans('admin_labels.sum_pass') }}</th>
            <th>{{ trans('admin_labels.mileage') }}</th>
            <th>{{ trans('admin_labels.actual_mileage') }}</th>
            <th>{{ trans('admin_labels.comment') }}</th>
        </tr>
        </thead>
        <tbody>

        @foreach($tours as $tour)
            <tr>
                <td class="text-center">{!! trans('pretty.statuses.'. $tour->status ) !!}</td>
                <td>{{ $tour->date_start->format('d.m.Y') ?? '' }}</td>
                <td>{{ strftime('%A', strtotime($tour->date_start->format('d.m.Y'))) ?? '' }}</td>
                <td>{{ $tour->date->isWeekday() ? trans('admin_labels.work_day') : trans('admin_labels.holiday') }}</td>
                <td>{{ $tour->date->format('H:i') ?? '' }}</td>
                <td></td>
                <td>{{ $tour->route->name ?? '' }}</td>
                <td>{{ $tour->bus->number ?? '' }}</td>
                <td>{{ $tour->driver->initials ?? '' }}</td>
                <td>{{ $tour->bus->places ?? '' }}</td>
                <td>{{ $tour->bus->company->name ?? '' }}</td>
                <td>{{ $tour->busyPlacesCount ?? '' }}</td>
                <td>{{ $tour->cashpayment ?? '' }}</td>
                <td>{{ $tour->cashpaymentoffice ?? '' }}</td>
                <td>{{ $tour->cashpaymentchild ?? '' }}</td>
                <td>{{ $tour->cashlessmentchild ?? '' }}</td>
                <td>{{ $tour->cashlesspayment ?? '' }}</td>
                <td>{{ $tour->checkingaccount ?? '' }}</td>
                <td>{{ $tour->success ?? '' }}</td>
                <td>{{ $tour->pass ?? '' }}</td>
                <td>{{ $tour->route->mileage ?? '' }}</td>
                <td>{{ $tour->comment ?? '' }}</td>

                <td></td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td><span class="label label-primary">{{trans('admin.buses.total')}}</span></td>
                <td>{{ $tours->count() }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ $resultcashpayment }}</td>
                <td>{{ $resultcashpaymentoffice }}</td>
                <td>{{ $resultcashpaymentchild }}</td>
                <td>{{ $resultcashlessmentchild }}</td>
                <td>{{ $resultcashlesspayment }}</td>
                <td>{{ $resultcheckingaccount }}</td>
                <td>{{ $resultsuccess }}</td>
                <td>{{ $resultpass }}</td>
                <td></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    
        
    <div class="ibox-footer js_table-pagination">
        @include('admin.partials.pagination', ['paginator' => $tours])
    </div>
</div>
@else
    <p class="text-muted">{{trans('admin.users.nothing')}}</p>
@endif