@if($diagnosticCards->count())
    <div class="table-responsive">
        <table class="table table-condensed table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>{{__('admin_labels.name') }}</th>
                <th>{{__('admin_labels.type')}}</th>
                <th>{{__('admin_labels.odometer')}}</th>
                <th>{{__('admin_labels.fuel')}}</th>
                <th>{{__('admin_labels.problem_count')}}</th>
                <th>{{__('admin_labels.examined') }}</th>
                <th>{{__('admin_labels.created_date')}}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($diagnosticCards as $diagnosticCard)
                <tr>
                    <td>{{ $diagnosticCard->id }}</td>
                    <td>{{ $diagnosticCard->template->name ? $diagnosticCard->template->name : '' }}</td>
                    <td>{{ $diagnosticCard->type }}</td>
                    <td>{{ $diagnosticCard->bus_variable ? $diagnosticCard->bus_variable->odometer: ''}}</td>
                    <td>{{ $diagnosticCard->bus_variable ? $diagnosticCard->bus_variable->fuel: ''}}</td>
                    <td>{{ $diagnosticCard->items ? $diagnosticCard->items->count(): 0}}</td>
                    <td>
                        @if($diagnosticCard->user)
                            {{$diagnosticCard->user->full_name}}
                        @else
                            {{trans('admin.buses.review_acts.not_assigned')}}
                        @endif
                    </td>

                    <td>{{$diagnosticCard->created_at->format('d.m.Y H:m')}}</td>
                    <td class="td-actions">
                        {{--                    @if($repair->status == App\Models\Repair::STATUS_REPAIR)--}}
                        <a href="{{route ('admin.'. $entity . '.edit', [$bus,$diagnosticCard])}}"
                           class="btn btn-sm btn-primary pjax-link" data-toggle="tooltip"
                           title="{{trans('admin.filter.edit')}}">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="{{route ('admin.' . $entity . '.destroy', [$bus,$diagnosticCard])}}"
                           method="delete"
                           class="btn btn-sm btn-danger js_panel_confirm" data-toggle="tooltip" title="{{ trans('admin.filter.delete') }}">
                            <i class="fa fa-trash-o "></i>
                        </a>

                        {{--                    @endif--}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="text-muted">{{trans('admin.users.nothing')}}</p>
@endif
