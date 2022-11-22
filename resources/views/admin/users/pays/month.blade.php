@if($pays['month'])
    <div class="table-responsive">
        <h2>{{trans('admin.users.accrual_for_the_month')}}</h2>
        <table class="table table-condensed">
            <thead>
            <tr>
                <th>{{trans('admin.users.month')}}</th>
                <th>{{trans('admin.users.year')}}</th>
                <th>{{trans('admin.users.sum')}}</th>
                <th>{{trans('admin.users.company')}}</th>
                <th>{{trans('admin.users.route')}}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($pays['month'] as $pay)
                <tr>
                    <td>
                        {{$pay->month}}
                    </td>
                    <td>
                        {{$pay->year}}
                    </td>
                    <td>
                        {{$pay->sum}}
                    </td>
                    <td>
                        {{$pay->company_id ? $monthCompany[$pay->company_id]->name: ''}}
                    </td>
                    <td style="text-align: left">
                        {{$pay->route_id ? $monthRoute[$pay->route_id]->name: ''}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="text-muted">{{trans('admin.users.nothing')}}</p>
@endif