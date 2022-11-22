

<div class="ibox">
    <div class="ibox-content">
        <h3>{{trans('admin.filter.filter')}}</h3>
        {!! Form::open(['route' => ['admin.orders.create', $order],'class' => 'js_table-search text-center', 'method' => 'get']) !!}
        <div class="form-group dib">
            <div class="js_datepicker_without_previous" data-date-start-date="-" data-date="{{ request('date') ? request('date') : Carbon\Carbon::now()->format('d.m.Y') }}"></div>
            {!! Form::hidden('date', request('date') ? request('date') : Carbon\Carbon::now()->format('d.m.Y'), ['class' => 'form-control js_table-reset-no']) !!}
        </div>
        <div  class="form-group">
            <div  class="col-xs-6">
                {!! Form::select('city_from_id', $cities, request('city_from_id'),
                [
                'placeholder' => trans('admin.orders.from'),
                'class' => "form-control js_city_from_id",
                'data-url' => route('admin.tours.get_cities')
                ]) !!}
            </div>
            <div style="padding-bottom: 5%" class="col-xs-6">
                {!! Form::select('city_to_id', [], request('city_to_id'),
                [
                'placeholder' => trans('admin.orders.to'),
                'class' => "form-control js_city_to_id",
                'disabled' => "disabled"
                ]) !!}
            </div>
        </div>
        @php($routes->prepend(trans('admin.filter.all'),0))
        {!! Form::panelRadios('route_id', $routes) !!}
        {!! Form::close() !!}
    </div>
</div>