<ul class="nav nav-tabs">
{{--    <li><a data-toggle="tab" href="#pay_month">{{trans('admin.users.accrual_for_the_month')}}</a></li>--}}
    <li class="active"><a data-toggle="tab" href="#pay_order">{{trans('admin.users.charges')}}</a></li>
    <li><a data-toggle="tab" href="#salary">{{trans('admin.salary.payments')}}</a></li>
    <li><a data-toggle="tab" href="#summary">{{trans('admin.buses.total')}}</a></li>
</ul>
<div class="tab-content">
{{--    <div id="pay_month" class="tab-pane fade">--}}
{{--        @include('admin.users.pays.month')--}}
{{--    </div>--}}
    <div id="pay_order" class="tab-pane fade in active">
        @include('admin.users.pays.order')
    </div>
    <div id="salary" class="tab-pane fade">
        @include('admin.users.pays.salary')
    </div>
    <div id="summary" class="tab-pane fade">
        <h3>{{trans('admin.salary.sum_of_orders')}}: {{$orders->count()}} / {{$orders->where('status', \App\Models\Order::STATUS_DISABLE)->count()}}</h3>
        <h3>{{trans('admin.users.sum')}}:
            @foreach($resultPriceActive as $alfa => $price)
                {{ $price . ' ' . trans('admin_labels.currencies_short.' . $alfa) }}
            @endforeach
        </h3>
        <h3>{{trans('admin.salary.num_of_appearances')}}: {{$orders->sum('count_places_appearance')}}</h3>
        <h3>{{trans('admin.salary.num_of_absenteeism')}}: {{$orders->sum('count_places_no_appearance')}}</h3>
        <br>
        <h3>{{trans('admin.salary.title')}}:
            @foreach($payMonth as $alfa => $price)
                {{ $price . ' ' . trans('admin_labels.currencies_short.' . $alfa) }}
            @endforeach</h3>
        <h3>{{trans('admin.salary.bonuses')}}:
            @foreach($resultSum as $alfa => $price)
                {{ $price . ' ' . trans('admin_labels.currencies_short.' . $alfa) }}
            @endforeach
        </h3>
        <h3>{{trans('admin.salary.total')}}:
            @foreach($totalPay as $alfa => $price)
                {{ $price . ' ' . trans('admin_labels.currencies_short.' . $alfa) }}
            @endforeach
        </h3>
        <h3>{{trans('admin.salary.paid')}}:
            @foreach($salariesSum as $alfa => $price)
                {{ $price . ' ' . trans('admin_labels.currencies_short.' . $alfa) }}
            @endforeach
        </h3>
        <h3>{{trans('admin.salary.must')}}:
            @foreach(array_where($balance, function ($value) {return (float)$value > 0.0;}) as $alfa => $price)
                {{ $price . ' ' . trans('admin_labels.currencies_short.' . $alfa) }}
            @endforeach
        </h3>
        <h3>{{trans('admin.salary.excess')}}:
            @foreach(array_where($balance, function ($value) {return (float)$value < 0.0;}) as $alfa => $price)
                {{ $price . ' ' . trans('admin_labels.currencies_short.' . $alfa) }}
            @endforeach
        </h3>
    </div>
</div>
