@extends('panel::layouts.main')

@section('title', $driver->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i> {{
    trans('admin.filter.back') }}</a>
@endsection

@section('main')
    {!! Form::model($driver, ['route' => 'admin.'. $entity . '.store', 'class' => "ibox form-horizontal js_form-ajax js_form-ajax-reset"])  !!}
    {!! Form::hidden('id') !!}
    <div class="ibox-content">
        <h2>{{ $driver->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}</h2>
        <div class="hr-line-dashed"></div>
        @if(isset($driver->id))
            <span data-url="{{route ('admin.' . $entity . '.set-buses-popup', $driver)}}" data-toggle="modal"
                  data-target="#popup_tour-edit" class="btn btn-sm btn-info">
                {{__('admin_labels.cars')}}
            </span>
        @endif
        <div class="row">
            <div class="col-md-6">
                {{ Form::panelText('full_name') }}
                {{ Form::panelText('middle_name') }}
                {{ Form::panelText('last_name') }}
                {{ Form::panelSelect('company_id',  $companies) }}
                <div class="form-group">
                    <label for="email" class="col-md-4">{{trans('admin.auth.tel')}}</label>
                    <div class="col-md-8">
                        <input class="form-control" name="phone" type="text" value="{{$driver->phone}}">
                            <p class="error-block"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-md-4">{{trans('admin.auth.work_tel')}}</label>
                        <div class="col-md-8">
                            <input class="form-control" name="work_phone" type="text" value="{{$driver->work_phone}}">
                            <p class="error-block"></p>
                        </div>
                    </div>

                    {{--{{ Form::panelText('phone', $driver->editPhone, 'js_panel_input-phone') }}
                    {{ Form::panelText('work_phone', $driver->work_phone, 'js_panel_input-phone') }}--}}
                    {{ Form::panelText('end_visa', $driver->end_visa ? date('d.m.Y', strtotime($driver->end_visa)) : null,'js_datepicker') }}

                    <div class="form-group">
                        <label for="email" class="col-md-4">{{trans('admin.auth.day_before_end_visa')}}</label>
                        <div class="col-md-8">
                            <input class="form-control" name="day_before_end_visa" type="text" value="{{$driver->day_before_end_visa}}">
                            <p class="error-block"></p>
                        </div>
                    </div>

                    {{ Form::panelText('med_day', $driver->med_day ? date('d.m.Y', strtotime($driver->med_day)) : null,'js_datepicker') }}
                    <div class="form-group">
                        <label for="day_before_med_day" class="col-md-4">{{trans('admin.auth.day_before_med_day')}}</label>
                        <div class="col-md-8">
                            <input class="form-control" name="day_before_med_day" type="text" value="{{$driver->day_before_med_day}}">
                            <p class="error-block"></p>
                        </div>
                    </div>
                    {{ Form::panelText('driver_license', $driver->driver_license ? date('d.m.Y', strtotime($driver->driver_license)) : null,'js_datepicker') }}
                    <div class="form-group">
                        <label for="email" class="col-md-4">{{trans('admin.auth.day_before_dl')}}</label>
                        <div class="col-md-8">
                            <input class="form-control" name="day_before_driver_license" type="text" value="{{$driver->day_before_driver_license}}">
                            <p class="error-block"></p>
                        </div>
                    {{ Form::panelText('birth_day', $driver->birth_day ? $driver->birth_day->format('d.m.Y') : Carbon\Carbon::now()->format('d.m.Y'),'js_datepicker') }}
                    {{ Form::panelSelect('doc_type', trans('admin_labels.doc_types')) }}
                    {{ Form::panelText('doc_number') }}
                    {{ Form::panelSelect('country_id', trans('admin_labels.countries')) }}
                    {{ Form::panelSelect('gender', trans('admin_labels.genders')) }}
                    @if($driver->id)
                        {{ Form::panelSelect('status', trans('admin.drivers.statuses')) }}
                        {{ Form::panelSelect('reputation', trans('admin.companies.reputations')) }}
                    @endif
                    {{ Form::panelSelect('is_admin_driver', trans('admin.drivers.is_admin_driver')) }}
                    <div class="form-group">
                        {!! Form::label('password', trans('admin.auth.pass'), ['class' => "col-md-4 control-label"]) !!}
                        <div class="col-md-8">
                            {!! Form::password('password', ['class' => "form-control", 'autocomplete' => 'off']) !!}
                            <p class="error-block"></p>
                        </div>
                    </div>
                    {!! $driver->getImagesView($driver::IMAGE_TYPE_IMAGE) !!}
                </div>

                </div>
                <div class="col-md-6">
                    <h3>{{trans('admin.routes.title')}}</h3>
                    <div class="js_checkbox-wrap">
                        @foreach($routes as $routeId => $routeName)
                            <div class="checkbox">
                                {{ Form::checkbox('routes['. $routeId .'][check]', $routeId, $driver->routes->contains($routeId), ['class' => "js_checkbox", 'id' => 'routes['. $routeId .']']) }}
                                {{ Form::label('routes['. $routeId .'][check]', $routeName) }}
                            </div>
                            <div class="row">
                                <p for="pay_order_percent" class="col-md-2">{{trans('admin.orders.percent')}}</p>
                                <div class="col-md-2">
                                    <input class="form-control" name="routes[{{$routeId}}][pay_order_percent]"
                                           type="text"
                                           value="{{isset($driverRoutes[$routeId]) ? $driverRoutes[$routeId]->pivot->pay_order_percent : ''}}">
                                    <p class="error-block"></p>
                                </div>
                                <p for="pay_order_percent" class="col-md-2">{{trans('admin.orders.fix_order')}}</p>
                                <div class="col-md-2">
                                    <input class="form-control" name="routes[{{$routeId}}][pay_order_fix]" type="text"
                                           value="{{isset($driverRoutes[$routeId]) ? $driverRoutes[$routeId]->pivot->pay_order_fix: ''}}">
                                    <p class="error-block"></p>
                                </div>
                                <p for="pay_order_percent" class="col-md-1">{{trans('admin.orders.salary')}}</p>
                                <div class="col-md-2">
                                    <input class="form-control" name="routes[{{$routeId}}][pay_month_fix]" type="text"
                                           value="{{isset($driverRoutes[$routeId]) ? $driverRoutes[$routeId]->pivot->pay_month_fix : ''}}">
                                    <p class="error-block"></p>
                                </div>
                            </div>
                        @endforeach
                        <div class="checkbox mt-5">
                            {{ Form::checkbox(null, null, $driver->routes->count() === count($routes), ['class' => 'js_checkbox-all', 'id' => 'routes[all]']) }}
                            {{ Form::label('routes[all]', trans('admin.filter.all'), ['class' => 'text-weight text-warning']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ibox-footer">
            {{ Form::panelButton() }}
        </div>
    {!! Form::close() !!}
@endsection
