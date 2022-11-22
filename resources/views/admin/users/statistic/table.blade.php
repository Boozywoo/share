<a href="{{$urlExcel}}">
    <h2>
        {{trans('admin.users.unload')}}
        <span data-toggle="modal" class="btn btn-sm btn-primary">
                <i class="fa fa-file-excel-o"></i>
            </span>
    </h2>

</a>
@if($users->count())
    <div class="table-responsive">
        <table class="table table-condensed">
            <thead>
            <tr>
                <th>{{ trans('admin_labels.first_name') }}</th>
                <th>{{ trans('admin_labels.company_name') }}</th>
                <th class="text-center">{{ trans('admin.users.num_of_book') }}</th>
                <th class="text-center">{{ trans('admin.users.per_of_book') }}</th>
                {{--<th>Кол-во броней на данный период</th>--}}
                <th class="text-center">{{ trans('admin.users.per_of_tur') }}</th>
                <th class="text-center">{{ trans('admin.users.per_of_abs') }}од</th>
            </tr>
            </thead>
            <tbody>
            @php($countOrders = $users->sum('cnt') + $onlineOrders['cnt'] + $mobileOrders['cnt'])
            @php($countApp = 0)
            @php($countNoApp = 0)
            @foreach($users->load('roles', 'companies') as $user)
                @php($countApp += $user->appearance)
                @php($countNoApp += $user->no_appearance)
                <tr>
                <tr>
                    <td><a href="{{route ('admin.' . $entity . '.pays', $user)}}"
                           class="pjax-link">{{ $user->FullName}}</a></td >
                    <td>{!! $user->companies->implode('name', ', ') !!} </td>
                    <td class="text-center">{{$user->cnt}} {{--{{ trans_choice(trans('chosen.orders'),  $user->cnt)}}--}} </td>
                    <td class="text-center">{{$countOrders ? round(100*$user->cnt/$countOrders, 2) : 0}} %</td>
                    {{--<td>{{$user->cntCompleted}}</td>--}}
                    <td class="text-center">{{$user->appearance}}</td>
                    <td class="text-center">{{$user->no_appearance}}</td>
                </tr>
            @endforeach
                
            <tr>
                <td>{{trans('admin.orders.booked_from_website')}}</td>
                <td></td>
                <td class="text-center">{{$onlineOrders['cnt']}}</td>
                <td class="text-center">{{$onlineOrders['cnt'] ? round(100*$onlineOrders['cnt']/$countOrders, 2) : 0}} %</td>
                <td class="text-center">{{$onlineOrders['appearance']}}</td>
                <td class="text-center">{{$onlineOrders['no_appearance']}}</td>
            </tr>
            <tr>
                <td>{{trans('admin.orders.booked_from_mobile')}}</td>
                <td></td>
                <td class="text-center">{{$mobileOrders['cnt']}}</td>
                <td class="text-center">{{$mobileOrders['cnt'] ? round(100*$mobileOrders['cnt']/$countOrders, 2) : 0}} %</td>
                <td class="text-center">{{$mobileOrders['appearance']}}</td>
                <td class="text-center">{{$mobileOrders['no_appearance']}}</td>
            </tr>
            <tr>
                <td>{{trans('admin.buses.total')}}</td>
                <td></td>
                <td class="text-center">{{$countOrders}}</td>
                <td class="text-center">{{100}} %</td>
                <td class="text-center">{{$mobileOrders['appearance'] + $onlineOrders['appearance'] + $countApp}}</td>
                <td class="text-center">{{$mobileOrders['no_appearance'] + $onlineOrders['no_appearance'] + $countNoApp}}</td>
            </tr>
            </tbody>
        </table>
    </div>
@else
    <p class="text-muted">{{ trans('admin.users.nothing') }}</p>
@endif