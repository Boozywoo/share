@if($companies->count())
<div class="table-responsive">
    <table class="table table-condensed table-hover">
        <thead>
        <tr>
            <th class="text-center">{{ trans('admin_labels.status') }}</th>
            <th>{{ trans('admin_labels.name') }}</th>
            <th>{{ trans('admin_labels.cars_count') }}</th>
            <th>{{ trans('admin_labels.routes') }}</th>
            <th>{{ trans('admin_labels.tours') }}</th>
            <th>{{ trans('admin_labels.date_register') }}</th>
            <th class="text-center">{{ trans('admin_labels.reviews') }}</th>
            <th class="text-center">{{ trans('admin_labels.reputation') }}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @php($resultAll = 0)
        @foreach($companies as $company)
            <tr>
                <td class="text-center">{!! trans('pretty.statuses.'. $company->status ) !!}</td>
                <td>{{ $company->name }}</td>
                <td>{{ $company->buses->count() }}</td>
                <td></td>
                <td></td>
                <td>@date($company->created_at) / {{ $company->countRegister }} {{ trans_choice('dates.days', $company->countRegister) }}</td>
                <td class="text-center">
                    @if($positiveCount = $company->reviewsPositive->count())
                        <i class="fa fa-thumbs-up text-info">{{ $positiveCount }}</i>
                    @endif
                    @if($negativeCount = $company->reviewsNegative->count())
                        <i class="fa fa-thumbs-down text-danger">{{ $negativeCount }}</i>
                    @endif
                    @if($positiveCount && $negativeCount)
                        <br><span class="label">Итого:</span> {{ $positiveCount + $negativeCount }}
                    @endif
                </td>
                <td class="text-center">{!! trans('pretty.reputations.'. $company->reputation ) !!}</td>
            </tr>
            @php($result = 0)
            @foreach($company->routes as $route)
                @php($result += $route->tours->count())
                <tr>
                    <td colspan="3"></td>
                    <td>{{ $route->name }}</td>
                    <td>{{ $route->tours->count() }}</td>
                    <td colspan="3"></td>
                </tr>
            @endforeach
            @if($result)
                <tr>
                    <td colspan="3"></td>
                    <td><span class="label">{{trans('admin.buses.total')}}</span></td>
                    <td>{{ $result }}</td>
                    <td colspan="3"></td>
                </tr>
            @endif
            @php($resultAll += $result)
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3"></td>
            <td><span class="label label-primary">{{trans('admin.buses.total')}}</span></td>
            <td>{{ $resultAll }}</td>
            <td colspan="3"></td>
        </tr>
        </tfoot>
    </table>
</div>
@else
    <p class="text-muted">{{trans('admin.users.nothing')}}</p>
@endif