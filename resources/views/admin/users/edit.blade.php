@extends('panel::layouts.main')

@section('title', $user->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i> {{trans('admin.filter.back')}}</a>
@endsection
@section('main')
    {!! Form::model($user, ['route' => 'admin.'. $entity . '.store', 'class' => "ibox form-horizontal js_form-ajax js_form-ajax-reset"]) !!}
    {!! Form::hidden('id', $user->id) !!}
    <div class="ibox-content">
        <h2>{{ $user->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}</h2>
        <div class="hr-line-dashed"></div>
        @if(isset($user->id))
            <span data-url="{{route ('admin.' . $entity . '.set-buses-popup', [$user])}}" data-toggle="modal"
                  data-target="#popup_tour-edit" class="btn btn-sm btn-info">
                {{__('admin_labels.cars')}}
            </span>
        @endif
        <div class="row">
            <div class="col-md-6">
                {{ Form::panelText('first_name') }}

                @include('admin.users.index.select-template', $userCompanies)

                {{ Form::panelSelect('role_id', $rolesSelect, $user->roles ? $user->roles->pluck('id')->toArray() : '') }}
                <div class="form-group">
                    {!! Form::label('password', trans('admin_labels.password'), ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-8">
                        {!! Form::password('password', ['class' => "form-control", 'autocomplete' => 'off']) !!}
                        <p class="error-block"></p>
                    </div>
                </div>
                {{ Form::panelText('email') }}
                {{ Form::panelText('sip') }}

                {{ Form::panelSelect('status', trans('admin.users.statuses')) }}
                {{ Form::panelSelect('user_status', trans('admin.users.user_statuses')) }}

                @if(env('TIME_ZONE'))

                    {{ Form::panelSelect('timezone',$timezonelist,$user ? $user->timezone : null ) }}
                @endif
                <div class="form-group">
                    <label for="phone" class="col-md-4">{{trans('admin.auth.tel')}}</label>
                    <div class="col-md-8">
                        <input class="form-control" name="phone" type="text" value="{{$user->phone}}">
                        <p class="error-block"></p>
                    </div>
                </div>
                {{--{{ Form::panelSelect('currency_id',  $currencies) }} --}}
            </div>
            <div class="col-md-6">
            @if(!$user->hasRole('mediator'))

                <h3>{{trans('admin.companies.title')}}</h3>
                <div class="js_checkbox-search">
                    <input type="text" class="js_checkbox-search-input form-control" placeholder="{{trans('admin.companies.search')}}" />
                    <button type="button" class="js_checkbox-search-filter btn btn-sm btn-primary" data-filter="all">{{trans('admin.filter.all')}}</button>
                    <button type="button" class="js_checkbox-search-filter btn btn-sm btn-primary" data-filter="selected">{{trans('admin.filter.selected')}}</button>
                </div>
                <div class="js_checkbox-wrap">

                    @foreach($companies as $companyId => $companyName)
                        <div class="checkbox">
                            {{ Form::checkbox('companies['. $companyId .'][check]', $companyId, $user->companies->contains($companyId), ['class' => 'js_checkbox', 'id' => 'companies['. $companyId .']']) }}
                            {{ Form::label('companies['. $companyId .'][check]', $companyName) }}
                        </div>
                        {{-- {{ dd($user->hasRole('operator') or $user->hasRole('agent'))}} --}}
                        @if($user->hasRole('operator') or $user->hasRole('agent'))
                            <div class="row">
                                <p for="pay_order_percent" class="col-md-2">{{trans('admin.orders.percent')}}</p>
                                <div class="col-md-2">
                                    <input class="form-control" name="companies[{{$companyId}}][pay_order_percent]"
                                           type="text"
                                           value="{{isset($userCompanyPays[$companyId]) ? $userCompanyPays[$companyId]->pivot->pay_order_percent : ''}}">
                                    <p class="error-block"></p>
                                </div>
                                <p for="pay_order_percent" class="col-md-2">{{trans('admin.orders.fix_order')}}</p>
                                <div class="col-md-2">
                                    <input class="form-control" name="companies[{{$companyId}}][pay_order_fix]"
                                           type="text"
                                           value="{{isset($userCompanyPays[$companyId]) ? $userCompanyPays[$companyId]->pivot->pay_order_fix: ''}}">
                                    <p class="error-block"></p>
                                </div>
                                <p for="pay_order_percent" class="col-md-1">{{trans('admin.orders.salary')}}</p>
                                <div class="col-md-2">
                                    <input class="form-control" name="companies[{{$companyId}}][pay_month_fix]"
                                           type="text"
                                           value="{{isset($userCompanyPays[$companyId]) ? $userCompanyPays[$companyId]->pivot->pay_month_fix: ''}}">
                                    <p class="error-block"></p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    <div class="checkbox mt-5">
                        {{ Form::checkbox(null, null, $user->companies->count() == count($companies), ['class' => 'js_checkbox-all', 'id' => 'companies[all]']) }}
                        {{ Form::label('companies[all]', trans('admin.filter.all'), ['class' => 'text-weight text-warning']) }}
                    </div>
                </div>
                @endif

            </div>
            <div class="col-md-6">
                <h3>{{trans('admin.routes.title')}}</h3>
                <div class="js_checkbox-search">
                    <input type="text" class="js_checkbox-search-input form-control" placeholder="{{trans('admin.routes.search')}}" />
                    <button type="button" class="js_checkbox-search-filter btn btn-sm btn-primary" data-filter="all">{{trans('admin.filter.all')}}</button>
                    <button type="button" class="js_checkbox-search-filter btn btn-sm btn-primary" data-filter="selected">{{trans('admin.filter.selected')}}</button>
                </div>
                <div class="js_checkbox-wrap">
                    @foreach($routes as $routeId => $routeName)
                        <div class="checkbox">
                            {{ Form::checkbox('routes['. $routeId .'][check]', $routeId, $user->routes->contains($routeId), ['class' => 'js_checkbox', 'id' => 'routes['. $routeId .']']) }}
                            {{ Form::label('routes['. $routeId .'][check]', $routeName, ['class' => 'text-weight text-warning']) }}
                        </div>
                        @if($user->hasRole('operator') or $user->hasRole('agent'))
                            <div class="row">
                                <p for="pay_order_percent" class="col-md-2">{{trans('admin.orders.percent')}}</p>
                                <div class="col-md-2">
                                    <input class="form-control" name="routes[{{$routeId}}][pay_order_percent]"
                                           type="text"
                                           value="{{isset($userRoutes[$routeId]) ? $userRoutes[$routeId]->pivot->pay_order_percent : ''}}">
                                    <p class="error-block"></p>
                                </div>
                                <p for="pay_order_percent" class="col-md-2">{{trans('admin.orders.fix_order')}}</p>
                                <div class="col-md-2">
                                    <input class="form-control" name="routes[{{$routeId}}][pay_order_fix]" type="text"
                                           value="{{isset($userRoutes[$routeId]) ? $userRoutes[$routeId]->pivot->pay_order_fix: ''}}">
                                    <p class="error-block"></p>
                                </div>
                                <p for="pay_order_percent" class="col-md-1">{{trans('admin.orders.salary')}}</p>
                                <div class="col-md-2">
                                    <input class="form-control" name="routes[{{$routeId}}][pay_month_fix]" type="text"
                                           value="{{isset($userRoutes[$routeId]) ? $userRoutes[$routeId]->pivot->pay_month_fix : ''}}">
                                    <p class="error-block"></p>
                                </div>
                            </div>
                        @endif
                        @if($user->hasRole('mediator'))
                            <div class="row">
                                <p for="added_price" class="col-md-2">{{trans('admin.orders.fix_order')}}</p>
                                <div class="col-md-2">
                                    <input class="form-control" name="routes[{{$routeId}}][added_price]" type="text"
                                           value="{{isset($userRoutes[$routeId]) ? $userRoutes[$routeId]->pivot->added_price : ''}}">
                                    <p class="error-block"></p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    <div class="checkbox mt-5">
                        {{ Form::checkbox(null, null, $user->routes->count() == count($routes), ['class' => 'js_checkbox-all', 'id' => 'routes[all]']) }}
                        {{ Form::label('routes[all]', trans('admin.filter.all'), ['class' => 'text-weight text-warning']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ibox-footer">
        {{ Form::panelButton() }}
    </div>
    @if($notification && !$notification->approved && !$notification->denied)
        <div class="ibox-footer">
            <a href="{{route ('admin.notifications.noti-approved-user', $notification)}}"
               class="btn btn-warning  js_panel_ajax " data-toggle="tooltip" title=""
               data-success="{{ trans('admin_labels.success_save') }}">
                <i class=" ">{{trans('admin_labels.noti.btn_ok')}}</i></a>
            <a href="{{route ('admin.notifications.noti-denied', $notification)}}"
               class="btn btn-danger  js_panel_ajax " data-toggle="tooltip" title=""
               data-success="{{ trans('admin_labels.success_save') }}">
                <i class=" ">{{trans('admin_labels.noti.btn_denied')}}</i></a>
        </div>
    @endif
    {!! Form::close() !!}

    @if($user->id && $user->company_id)
        <script>
            $("#companySelect").val({{$user->company_id}}).change();
        </script>
    @endif
@endsection
