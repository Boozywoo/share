@if($amenities->count())
    <div class="table-responsive">
        <table class="table table-condensed table-hover">
            <thead>
            <tr>
                <th>{{ trans('admin_labels.name') }}</th>
                <th>{{ trans('admin_labels.status') }}</th>
                <th>{{ trans('admin_labels.company_id') }}</th>
                <th>{{ trans('admin_labels.icon') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($amenities as $amenity)
                <tr class="{{$bgHoverColor}}">
                    <td>{{ $amenity->name }}</td>
                    <td>{{ $amenity->status }}</td>
                    <td>{{ $amenity->company ? $amenity->company->name : trans('admin.settings.roles.not_assigned') }}</td>
                    <td>
                        @if(!empty($amenity->mainImage))
                            <img src="/{{ $amenity->getImagePath('image', 'original',  $amenity->mainImage->path) }}"
                                 alt="" width="24" height="24">
                        @endif
                    </td>
                    <td class="td-actions">
                        @if($amenity->company)
                            <a href="{{route ('admin.'. $entity . '.edit', $amenity)}}"
                               class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip"
                               title="{{ trans('admin.filter.edit') }}">
                                <i class="fa fa-edit"></i>
                            </a>
                            {{--                            @if($amenity->buses()->count() == 0)--}}
                            <a href="{{route ('admin.' . $entity . '.delete', $amenity)}}"
                               class="btn btn-sm btn-danger js_panel_confirm" data-toggle="tooltip"
                               title="{{ trans('admin.filter.delete') }}">
                                <i class="fa fa-trash-o "></i>
                            </a>
                            {{--                            @endif--}}
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="text-muted">{{ trans('admin.users.nothing') }}</p>
@endif
