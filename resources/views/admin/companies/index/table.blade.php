@if($companies->count())
<div class="table-responsive">
    <table class="table table-condensed table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ trans('admin_labels.name') }}</th>
            <th>{{ trans('admin_labels.responsible') }}</th>
            <th>{{ trans('admin_labels.position') }}</th>
            <th>{{ trans('admin_labels.phones') }}</th>
            <th class="text-center">{{ trans('admin_labels.status') }}</th>
            <th class="text-center">{{ trans('admin_labels.reputation') }}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($companies as $company)
            <tr>
                <td>{{ $company->id }}</td>
                <td><a href="{{route ('admin.'. $entity . '.edit', $company)}}" class="pjax-link">{{ $company->name }}</a></td>
                <td>{{ $company->responsible }}</td>
                <td>{{ $company->position }}</td>
                <td>
                    @phone($company->phone) <br>
                    @phone($company->phone_sub)<br>
                    @phone($company->phone_resp)
                </td>
                <td class="text-center">{!! trans('pretty.statuses.'. $company->status ) !!}</td>
                <td class="text-center">{!! trans('pretty.reputations.'. $company->reputation ) !!}</td>
                <td class="td-actions">
                    <a href="{{route ('admin.'. $entity . '.positions.index', $company)}}" class="btn btn-sm btn-warning pjax-link" data-toggle="tooltip" title="{{trans('admin_labels.positions')}}">
                        <i class="fa fa-list"></i>
                    </a>
                    <a href="{{route ('admin.'. $entity . '.departments.index', $company)}}" class="btn btn-sm btn-warning pjax-link" data-toggle="tooltip" title="{{trans('admin_labels.departments')}}">
                        <i class="fa fa-users"></i>
                    </a>
                    <a href="{{route ('admin.'. $entity . '.edit', $company)}}" class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip" title="{{trans('admin.filter.edit')}}">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a href="{{route ('admin.'. $entity . '.companyStatics', $company)}}" class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip" title="{{trans('admin.users.statistic')}}">
                        <i class="fa fa-dashboard"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@else
    <p class="text-muted">{{trans('admin.users.nothing')}}</p>
@endif
