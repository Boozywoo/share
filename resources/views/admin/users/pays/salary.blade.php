@if($user->salaries)
    <div class="table-responsive">
        <h2>{{trans('admin.salary.payments')}}</h2>
        <table class="table table-condensed">
            <thead>
            <tr>
                <th>{{trans('admin.users.admin')}}</th>
                <th>{{trans('admin.users.sum')}}</th>
                <th>{{trans('admin.drivers.date')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($salaries as $salary)
                <tr>
                    <td>
                        {{ $salary->admin->fullName }}
                    </td>
                    <td>
                        {{ $salary->sum . ' ' . trans('admin_labels.currencies_short.' . $salary->currency->alfa) }}
                    </td>
                    <td style="text-align: left">
                        {{ $salary->created_at }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="text-muted">{{trans('admin.users.nothing')}}</p>
@endif
