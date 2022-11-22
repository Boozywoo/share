@if($drivers->count())
<div class="table-responsive">
    <table class="table table-condensed table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ trans('admin_labels.status') }}</th>
            <th>{{ trans('admin_labels.last_name') }} {{ trans('admin_labels.first_name') }} {{ trans('admin_labels.patronymic') }}</th>
            <th>{{ trans('admin_labels.company_id') }}</th>
            <th>{{ trans('admin_labels.phone') }}</th>
            <th class="text-center">{{ trans('admin_labels.img') }}</th>
            <th class="text-center">{{ trans('admin_labels.reputation') }}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($drivers as $driver)
            <tr>
                <td>{{ $driver->id }}</td>
                <td>{!! trans('pretty.statuses.'. $driver->status ) !!}</td>
                <td><a href="{{route ('admin.'. $entity . '.edit', $driver)}}" class="pjax-link">{{ $driver->last_name ?? ''}} {{ $driver->full_name }} {{ $driver->middle_name ?? ''}}</a></td>
                <td>{{ $driver->company->name }}</td>
                <td>
                    <a href="tel:{{ $driver->phone }}">@phone($driver->phone)</a>
                </td>
                <td class="text-center">
                    @if($driver->mainImage)
                        <div class="product__figure">
                            <img src="{{ $driver->mainImage->load('admin', $driver) }}">
                        </div>
                    @endif
                </td>
                <td class="text-center">{!! trans('pretty.reputations.'. $driver->reputation ) !!}</td>
                <td class="td-actions">
                    <a href="{{route ('admin.'. $entity . '.edit', $driver)}}" class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip" title="{{trans('admin.filter.edit')}}">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a href="{{route ('admin.'. $entity . '.fines', $driver)}}" class="btn btn-sm btn-danger pjax-link" data-toggle="tooltip" title="{{trans('admin.drivers.fines')}}">
                        <i class="fa fa-exclamation-triangle"></i>
                    </a>
                    @if(Auth::user()->roles->first()->slug == 'admin' or Auth::user()->roles->first()->slug == 'superadmin')
                        <a href="{{route ('admin.' . $entity . '.delete', $driver)}}"
                            class="btn btn-sm btn-danger js_panel_confirm {{ $textLink }}" data-toggle="tooltip" title="{{ trans('admin.filter.delete') }}"
                            data-success="{{ trans('admin.'. $entity . '.delete') }}"data-question="{{ trans('admin.'. $entity . '.question_del') }}">
                        <i class="fa fa-trash-o "></i>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@else
    <p class="text-muted">{{trans('admin.users.nothing')}}</p>
@endif