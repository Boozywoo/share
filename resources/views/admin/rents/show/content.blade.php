@if ($tour->bus)
<div class="row">
    <div class="col-md-12 js_tour-show-wrapper js_checkbox-wrap">
        <h3 class="edit">{{trans('admin.tours.active')}}</h3>
        {!! Form::open(['route' => ['admin.'. $entity . '.toPull', $tour], 'class' => 'form-horizontal js_form-ajax']) !!}
        <button type="submit" class="btn btn-xs btn-warning pull-left">
            <i class="fa fa-angle-double-right"></i>
            {{trans('admin.buses.rent.sent_pool')}}
        </button>
        <div class="checkbox m-l-lg pull-left no-paddings">
            {{ Form::checkbox(null, null, false, ['class' => 'js_checkbox-all', 'id' => 'routes[all]1']) }}
            {{ Form::label('routes[all]1', trans('admin.buses.rent.all'), ['class' => 'text-weight text-warning']) }}
        </div>
        <div class="clearfix"></div>
        @include('admin.'. $entity . '.show.partials.table', ['orders' => $tour->ordersReady])
        {!! Form::close() !!}
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="hr-line-dashed"></div>
            <h3 class="edit">{{trans('admin.buses.bus')}}</h3>
            <b>{{trans('admin.buses.rent.total')}}:</b> {{ $tour->bus->places }} <br>
            <b>{{trans('admin.buses.rent.empty')}}:</b> {{ $tour->freePlacesCount }}
            @include('admin.'. $entity . '.show.partials.bus')
    </div>
    <div class="col-md-12 js_tour-show-wrapper js_checkbox-wrap">
        <h3 class="edit">Пул</h3>
        {!! Form::open(['route' => ['admin.'. $entity . '.fromPull', $tour], 'class' => 'form-horizontal js_form-ajax']) !!}
        <button type="submit" class="btn btn-xs btn-success pull-left">
            <i class="fa fa-angle-double-left"></i>
            {{trans('admin.buses.rent.clean')}}
        </button>

        <div class="checkbox m-l-lg pull-left no-paddings">
            {{ Form::checkbox(null, null, false, ['class' => 'js_checkbox-all', 'id' => 'routes[all]2']) }}
            {{ Form::label('routes[all]2', trans('admin.buses.rent.all'), ['class' => 'text-weight text-warning']) }}
        </div>
        <div class="clearfix"></div>
        @include('admin.'. $entity . '.show.partials.table', ['orders' => $tour->ordersPull])
        {!! Form::close() !!}
    </div>
</div>
@endif