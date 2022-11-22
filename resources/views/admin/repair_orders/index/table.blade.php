@if($repairs->count())
    <div class="table-responsive">
        <table class="table table-condensed table-hover">
            <thead>
            <tr>
                <th>{{__('admin.repair_orders.fields.order_number')}}</th>
                <th>{{__('admin.repair_orders.fields.type')}}</th>
                <th>{{__('admin.repair_orders.fields.status')}}</th>
                <th>{{__('admin.repair_orders.fields.repair_name')}}</th>
                <th>{{__('admin.repair_orders.fields.bus_name')}}</th>
                <th>{{__('admin.repair_orders.fields.bus_number')}}</th>
                <th>{{__('admin.repair_orders.fields.garage_number')}}</th>
                <th>{{__('admin.repair_orders.fields.repair_order')}}</th>
                <th>{{__('admin.repair_orders.fields.order_outfit')}}</th>
                <th>{{__('admin.repair_orders.fields.repair_map')}}</th>
                <th>{{__('admin.repair_orders.fields.parts_list')}}</th>
{{--                <th>{{__('admin.repair_orders.fields.parts_received')}}</th>--}}
{{--                <th>{{__('admin.repair_orders.fields.parts_order')}}</th>--}}
{{--                <th>{{__('admin.repair_orders.fields.parts_installed')}}</th>--}}
{{--                <th>{{__('admin.repair_orders.fields.parts_return')}}</th>--}}
                <th>{{__('admin.repair_orders.fields.repairs_completed')}}</th>
                <th></th>
            </tr>
            </thead>

            <tbody>
            @foreach($repairs as $repair)

                <tr>
                    <td>{{$repair->id}}</td>
                    <td>{{__('admin.repair_orders.types.'. $repair->type)}}</td>
                    <td>{{__('admin.repair_orders.statuses.'. $repair->status)}}</td>
                    <td>{{$repair->name}}</td>
                    <td>{{$repair->bus->name}}</td>
                    <td>{{$repair->bus->number}}</td>
                    <td>{{$repair->bus->garage_number}}</td>
                    <td><a class="underline-text" href="{{route('admin.'.$entity.'.edit',$repair->id)}}">{{$repair->created_at->format('d.m.Y')}}</a></td>
                    <td>@if($repair->order_outfit)
                            <a class="underline-text"
                               href="{{route('admin.'.$entity.'.order_outfits.edit',[$repair->id,$repair->order_outfit->id])}}">{{$repair->order_outfit->created_at->format('d.m.Y')}}</a>
                        @endif
                    </td>
                    <td>
                        @if($repair->diagnostic_card)
                            <a class="underline-text"
                               href="{{route('admin.'.$entity.'.diagnostic_cards.edit',[$repair->id,$repair->diagnostic_card->id])}}">{{$repair->diagnostic_card->created_at->format('d.m.Y')}}</a>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($repair->spare_parts()->count() > 0)
                            <a class="underline-text" style=""
                               href="{{route('admin.'.$entity.'.spare_parts.index',$repair->id)}}">{{$repair->spare_parts()->oldest()->first()->created_at->format('d.m.Y')}}
                                @if($repair->spare_part_in_stock == 'all')
                                    <span class="fa fa-exclamation-circle" style="color: #ed5565; font-size: 17px"
                                          data-toggle="tooltip"
                                          title="{{__('admin.repair_orders.spare_parts.take_the_parts')}}"></span>
                                @elseif($repair->spare_part_in_stock == 'in')
                                    <span class="fa fa-exclamation-circle" style="color: #f8ac59; font-size: 17px"
                                          data-toggle="tooltip"
                                          title="{{__('admin.repair_orders.spare_parts.take_the_parts')}}"></span>
                                @elseif($repair->spare_part_in_stock == 'out')
                                    <span class="fa fa-exclamation-circle" style="color: grey; font-size: 17px"
                                          data-toggle="tooltip"
                                          title="{{__('admin.repair_orders.spare_parts.no_parts')}}"></span>
                                @endif
                            </a>

                        @endif

                    </td>
                    <td>
                        @if(in_array($repair->status, \App\Models\Repair::CLOSED_STATUSES) && !empty($repair->date_end))
                            <div class="underline-text">{{$repair->date_end->format('d.m.Y')}}</div>

                        @endif
                    </td>
                    <td>
                        <a href="{{route ('admin.'. $entity . '.show', $repair)}}"
                           class="btn btn-sm btn-warning pjax-link" data-toggle="tooltip"
                           title="{{ trans('admin.repair_orders.title') }}">
                            <i class="fa fa-eye"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="text-muted">{{ trans('admin.users.nothing') }}</p>
@endif
