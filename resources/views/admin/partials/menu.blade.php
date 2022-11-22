@permission('view.users')
<li class="{{ str_contains(request()->url(), route('admin.users.index')) ? 'active' : '' }}">
    <a href="{{ route('admin.users.index', ['status' => 'active']) }}" class="pjax-link">
        <i class="fa fa-user"></i>
        <span class="text">  {{ trans('admin.users.title') }}</span>
    </a>
</li>
@endpermission

<li>
    <a href="#">
        <i class="fa fa-level-up"></i>
        <span class="nav-label"> {{ trans('admin.dashboards.title') }}</span>
        <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level collapse">
        <li class="{{ str_contains(request()->url(), route('admin.dashboards.buses.index')) ? 'active' : '' }}">
            <a href="{{ route('admin.dashboards.buses.index') }}"
               class=""> {{ trans('admin.dashboards.buses.title') }} </a>
        </li>
    </ul>
</li>


@permission('view.companies')
<li class="{{ str_contains(request()->url(), route('admin.companies.index')) ? 'active' : '' }}">
    <a href="{{ route('admin.companies.index', ['status' => 'active']) }}" class="pjax-link">
        <i class="fa fa-briefcase"></i>
        <span class="text">  {{ trans('admin.companies.title') }}</span>
    </a>
</li>
@endpermission

@permission('view.notifications')
<li class="{{ str_contains(request()->url(), route('admin.notifications.noti-index')) ? 'active' : '' }}">
    <a href="{{ route('admin.notifications.noti-index', ['status' => '', 'create-date' => '', 'treatment-date'=>'']) }}" class="pjax-link">
        <i class="fa fa-bell-o"></i>
        <span class="text">  {{ trans('admin.notifications.title') }} <span class="js_noti-count"></span></span>
    </a>
</li>
@endpermission
@permission('view.wishes')
<li class="{{ str_contains(request()->url(), route('admin.wishes.index')) ? 'active' : '' }}">
    <a href="{{ route('admin.wishes.index', ['status' => '', 'create-date' => '', 'treatment-date'=>'']) }}" class="pjax-link">
        <i class="fa fa-exclamation-triangle"></i>
        <span class="text">  {{ trans('admin.wishes.title') }}</span>
    </a>
</li>
@endpermission

@permission('view.agreements')
<li class="{{ str_contains(request()->url(), route('admin.agreements.index')) ? 'active' : '' }}">
    <a href="{{ route('admin.agreements.index', ['status' => 'active']) }}" class="pjax-link">
        <i class="fa fa-file-word-o"></i>
        <span class="text">  {{ trans('admin.agreements.title') }}</span>
    </a>
</li>
@endpermission

@permission('view.tariffs')
<li class="{{ str_contains(request()->url(), route('admin.tariffs.index')) ? 'active' : '' }}">
    <a href="{{ route('admin.tariffs.index', ['status' => 'active']) }}" class="pjax-link">
        <i class="fa fa-tasks"></i>
        <span class="text">  {{ trans('admin.tariffs.title') }}</span>
    </a>
</li>
@endpermission

@permission('view.buses')
<li class="{{ str_contains(request()->url(), route('admin.buses.index')) ? 'active' : '' }}">
    <a href="{{ route('admin.buses.index') }}" class="pjax-link">
        <i class="fa fa-bus"></i>
        <span class="text">  {{ trans('admin.buses.title') }}</span>
    </a>
</li>
@endpermission

@permission('view.drivers')
<li class="{{ str_contains(request()->url(), route('admin.drivers.index')) ? 'active' : '' }}">
    <a href="{{ route('admin.drivers.index', ['status' => 'active']) }}" class="pjax-link">
        <i class="fa fa-tachometer"></i>
        <span class="text">  {{ trans('admin.drivers.title') }}</span>
    </a>
</li>
@endpermission

@permission('view.reviews')
<li class="{{ str_contains(request()->url(), route('admin.reviews.index')) ? 'active' : '' }}">
    <a href="{{ route('admin.reviews.index') }}" class="pjax-link">
        <i class="fa fa-comment"></i>
        <span class="text">  {{ trans('admin.reviews.title') }}</span>
    </a>
</li>
@endpermission

@permission('view.routes')
<li class="{{ str_contains(request()->url(), route('admin.routes.index')) ? 'active' : '' }}">
    <a href="{{ route('admin.routes.index', ['status' => 'active']) }}" class="pjax-link">
        <i class="fa fa-map-signs"></i>
        <span class="text">  {{ trans('admin.routes.title') }}</span>
    </a>
</li>
@endpermission

@permission('view.schedules')
<li class="{{ str_contains(request()->url(), route('admin.schedules.index')) ? 'active' : '' }}">
    <a href="{{ route('admin.schedules.index') }}" class="pjax-link">
        <i class="fa fa-calendar"></i>
        <span class="text">  {{ trans('admin.schedules.title') }}</span>
    </a>
</li>
@endpermission
@if (Auth::user() && !Auth::user()->isMethodist)
    @permission('view.tours')
    <li class="{{ str_contains(request()->url(), route('admin.tours.index')) ? 'active_all' : '' }}">
        <a href="{{ route('admin.tours.index')}}?status=active_all" class="pjax-link">
            <i class="fa fa-road"></i>
            <span class="text">  {{ trans('admin.tours.title') }}</span>
        </a>
    </li>
    @endpermission
@endif


@permission('view.rents')
<li class="{{ str_contains(request()->url(), route('admin.rents.index')) ? 'active' : '' }}">
    <a href="{{ route('admin.rents.index') }}" class="pjax-link">
        <i class="fa fa-car"></i>
        <span class="text">  {{ trans('admin.buses.rent.title') }}</span>
    </a>
</li>
@endpermission

@permission('view.pages')
<li class="{{ str_contains(request()->url(), route('admin.pages.index')) ? 'active' : '' }}">
    <a href="{{ route('admin.pages.index') }}" class="pjax-link">
        <i class="fa fa-file"></i>
        <span class="text">  {{ trans('admin.pages.title') }}</span>
    </a>
</li>
@endpermission

@permission('view.clients')
<li class="{{ str_contains(request()->url(), route('admin.clients.index')) ? 'active' : '' }}">
    <a href="{{ route('admin.clients.index', ['status' => 'active']) }}" class="pjax-link">
        <i class="fa fa-users"></i>
        <span class="text">  {{ trans('admin.clients.title') }}</span>
    </a>
</li>
@endpermission

@if (Auth::user() && !Auth::user()->isMethodist)
    @permission('view.orders')
    <li class="{{ str_contains(request()->url(), route('admin.orders.index')) ? 'active' : '' }}">
        @if(\App\Models\Setting::all()->pluck('is_pay_on')->first())
            <a href="{{ route('admin.orders.index', ['status' => 'active','type_pay' => \App\Models\Setting::all()->pluck('display_types_of_orders')->first()]) }}" class="pjax-link">
                <i class="fa fa-edit"></i>
                <span class="text">  {{ trans('admin.orders.title') }}</span>
            </a>
        @else
            <a href="{{ route('admin.orders.index', ['status' => 'active']) }}" class="pjax-link">
                <i class="fa fa-edit"></i>
                <span class="text">  {{ trans('admin.orders.title') }}</span>
            </a>
        @endif
    </li>
    @endpermission
@endif


@permission('view.monitoring')
<li class="{{ str_contains(request()->url(), route('admin.monitoring.index')) ? 'active' : '' }}">
    <a href="{{ route('admin.monitoring.index') }}" class="pjax-link">
        <i class="fa fa-map-marker"></i>
        <span class="text">  {{ trans('admin.monitoring.title') }}</span>
    </a>
</li>
@endpermission

@permission('view.garage')
<li class="{{ str_contains(request()->url(), route('admin.garage.cars.index')) ? 'active' : '' }}">
    <a href="{{ route('admin.garage.cars.index') }}" class="pjax-link">
    <i class="fa fa-map-marker"></i>
        <span class="text">  {{ trans('admin.garage.title') }}</span>
    </a>
</li>
@endpermission

@permission('view.repair')
<li class="{{ str_contains(request()->url(), route('admin.repair_orders.index')) ? 'active' : '' }}">
    <a href="{{ route('admin.repair_orders.index') }}" class="pjax-link">
        <i class="fa fa-map-marker"></i>
        <span class="text">  {{ trans('admin.repair_orders.title') }}</span>
    </a>
</li>
@endpermission

@permission('view.maintenance')
<li class="{{ str_contains(request()->url(), route('admin.incidents.index')) ? 'active' : '' }}">
    <a href="{{ route('admin.incidents.index') }}" class="pjax-link">
        <i class="fa fa-map-marker"></i>
        <span class="text">  {{ trans('admin.incidents.title') }}</span>

    </a>
</li>
@endpermission

@permission('view.operational')
    <li class="{{ str_contains(request()->url(), route('admin.operational_tasks.index')) ? 'active' : '' }}">
        <a href="{{ route('admin.operational_tasks.index') }}" class="pjax-link">
            <i class="fa fa-ticket"></i>
            <span class="text">  {{ trans('admin.operational_tasks.title') }}</span>
        </a>
    </li>
@endpermission

@permission('view.admininterfacesettings')
<li class="{{ str_contains(request()->url(), route('admin.settings.interface_settings.edit')) ? 'active' : '' }}">
    <a href="{{ route('admin.settings.interface_settings.edit') }}"
    class="pjax-link">
        <i class="fa fa-edit"></i> {{ trans('admin.settings.interfaceSettings.title') }}
    </a>
</li>
@endpermission
{{-- @permission('view.monitoring')
<li id="ch_img">
    <input type="file" name="file" accept="image/*" id="ch_img_upload" style="display: none"/>
    <a>
        <label for="ch_img_upload" class="pjax-link">
            <i class="fa fa-edit"></i>
            Загрузить файлы
        </label>
    </a>
    <div class="ajax-reply"></div>
</li>
@endpermission --}}

@permission('view.maintenance')
<li class="sub-menu {{ str_contains(request()->url(), route('admin.settings.edit')) ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-cog"></i>
        <span class="nav-label"> {{ trans('admin.settings.exploitation.title') }}</span>
        <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level collapse">

        <li class="{{ str_contains(request()->url(), route('admin.settings.exploitation.reviewTemplates')) ? 'active' : '' }}">
            <a href="{{ route('admin.settings.exploitation.reviewTemplates') }}"
               class="pjax-link"> {{ trans('admin.settings.exploitation.menu.review_templates') }} </a>
        </li>
        <li class="{{ str_contains(request()->url(), route('admin.settings.exploitation.incident.index')) ? 'active' : '' }}">
            <a href="{{ route('admin.settings.exploitation.incident.index') }}"
               class="pjax-link"> {{ trans('admin.incidents.title') }} </a>
        </li>
        <li class="{{ str_contains(request()->url(), route('admin.settings.exploitation.breakages.index')) ? 'active' : '' }}">
            <a href="{{ route('admin.settings.exploitation.breakages.index') }}"
               class="pjax-link"> {{ trans('admin.settings.exploitation.breakages.title') }} </a>
        </li>
        <li class="{{ str_contains(request()->url(), route('admin.settings.exploitation.repair_cards.index')) ? 'active' : '' }}">
            <a href="{{ route('admin.settings.exploitation.repair_cards.index') }}"
               class="pjax-link"> {{ trans('admin.settings.exploitation.repair_cards.title') }} </a>
        </li>
        <li class="{{ str_contains(request()->url(), route('admin.settings.exploitation.spare_parts.index')) ? 'active' : '' }}">
            <a href="{{ route('admin.settings.exploitation.spare_parts.index') }}"
               class="pjax-link"> {{ trans('admin.settings.exploitation.spare_parts.title') }} </a>
        </li>
    </ul>
</li>
@endpermission


@if (Auth::user() && !Auth::user()->isMethodist && !Auth::user()->isAgent && !Auth::user()->isMediator)
    @permission('view.statistics')
    <li>
        <a>
            <i class="fa fa-level-up"></i>
        <span class="nav-label"> {{ trans('admin_labels.statistics') }}</span>
        <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level collapse">
        <li class="{{ str_contains(request()->url(), route('admin.users.statistic')) ? 'active' : '' }}">
                <a href="{{ route('admin.users.statistic') }}" class="pjax-link"> {{ trans('admin.users.statistic') }} </a>
        </li>
        <li class="{{ str_contains(request()->url(), route('admin.companies.statics')) ? 'active' : '' }}">
            <a href="{{ route('admin.companies.statics') }}" class="pjax-link"> {{ trans('admin.companies.statics') }} </a>
        </li>
        @if(\App\Models\Setting::all()->pluck('is_client_statistic')->first() == 0)
        <li class="{{ str_contains(request()->url(), route('admin.buses.statics')) ? 'active' : '' }}">
            <a href="{{ route('admin.buses.statics').'?status=active' }}" class="pjax-link"> {{ trans('admin.buses.statics') }} </a>
        </li>
        @endif
        <li class="{{ str_contains(request()->url(), route('admin.drivers.statics')) ? 'active' : '' }}">
            <a href="{{ route('admin.drivers.statics') }}" class="pjax-link"> {{ trans('admin.drivers.statics') }} </a>
        </li>
        @if(\App\Models\Setting::all()->pluck('is_client_statistic')->first() == 0)
        <li class="{{ str_contains(request()->url(), route('admin.clients.statics')) ? 'active' : '' }}">
            <a href="{{ route('admin.clients.statics') }}" class="pjax-link"> {{ trans('admin.clients.statics') }} </a>
        </li>
        @endif
        <li class="{{ str_contains(request()->url(), route('admin.tours.statistic')) ? 'active' : '' }}">
            <a href="{{ route('admin.tours.statistic').'?status=active' }}" class="pjax-link"> {{ trans('admin.tours.statistics') }} </a>
        </li>
    </ul>
</li>
    @endpermission
@endif

@permission('view.settings')
<li class="sub-menu {{ str_contains(request()->url(), route('admin.settings.edit')) ? 'active' : '' }}">
    <a href="{{ route('admin.settings.edit') }}">
        <i class="fa fa-cog"></i>
        <span class="nav-label"> {{ trans('admin.settings.title') }}</span>
        <span class="fa arrow"></span>
    </a>
    <ul class="nav nav-second-level collapse">
        <li class="{{ str_contains(request()->url(), route('admin.settings.edit')) ? 'active' : '' }}">
                <a href="{{ route('admin.settings.edit') }}" class="pjax-link"> {{ trans('admin.settings.general') }} </a>
        </li>
        <li class="{{ str_contains(request()->url(), route('admin.settings.smsconfig.edit')) ? 'active' : '' }}">
            <a href="{{ route('admin.settings.smsconfig.edit') }}" class="pjax-link"> {{ trans('admin.settings.smsconfig.title') }} </a>
        </li>
        <li class="{{ str_contains(request()->url(), route('admin.settings.driverapp.edit')) ? 'active' : '' }}">
            <a href="{{ route('admin.settings.driverapp.edit') }}" class="pjax-link"> {{ trans('admin.settings.driverapp.title') }} </a>
        </li>
        <li class="{{ str_contains(request()->url(), route('admin.settings.mobile_app.edit')) ? 'active' : '' }}">
            <a href="{{ route('admin.settings.mobile_app.edit') }}" class="pjax-link"> {{ trans('admin.settings.mobile_app.title') }} </a>
        </li>
        <li class="{{ str_contains(request()->url(), route('admin.settings.statuses.index')) ? 'active' : '' }}">
            <a href="{{ route('admin.settings.statuses.index') }}"
               class="pjax-link"> {{ trans('admin.settings.statuses.title') }} </a>
        </li>
        <li class="{{ str_contains(request()->url(), route('admin.settings.add_services.index')) ? 'active' : '' }}">
            <a href="{{ route('admin.settings.add_services.index') }}"
               class="pjax-link"> {{ trans('admin.settings.add_services.title') }} </a>
        </li>
        <li class="{{ str_contains(request()->url(), route('admin.settings.sales.index')) ? 'active' : '' }}">
            <a href="{{ route('admin.settings.sales.index') }}"
               class="pjax-link"> {{ trans('admin.settings.sales.title') }} </a>
        </li>
        <li class="{{ str_contains(request()->url(), route('admin.settings.coupons.index')) ? 'active' : '' }}">
            <a href="{{ route('admin.settings.coupons.index') }}"
               class="pjax-link"> {{ trans('admin.settings.coupons.title') }} </a>
        </li>
        <li class="{{ str_contains(request()->url(), route('admin.settings.clients_interface_settings.edit')) ? 'active' : '' }}">
            <a href="{{ route('admin.settings.clients_interface_settings.edit') }}"
               class="pjax-link"> {{ trans('admin.settings.clientsInterfaceSettings.title') }} </a>
        </li>
        <li class="{{ str_contains(request()->url(), route('admin.bus_type.index')) ? 'active' : '' }}">
            <a href="{{ route('admin.bus_type.index') }}" class="pjax-link"> {{ trans('admin.bus_type.title') }} </a>
        </li>
        @permission('view.maintenance')
        <li class="{{ str_contains(request()->url(), route('admin.settings.roles.index')) ? 'active' : '' }}">
            <a href="{{ route('admin.settings.roles.index') }}" class="pjax-link"> {{ trans('admin.settings.roles.title') }} </a>
        </li>
        <li class="{{ str_contains(request()->url(), route('admin.settings.amenities.index')) ? 'active' : '' }}">
            <a href="{{ route('admin.settings.amenities.index') }}"
               class="pjax-link"> {{ trans('admin.settings.amenities.title') }} </a>
        </li>
        @endpermission
        <li class="{{ str_contains(request()->url(), route('admin.settings.car_settings.index')) ? 'active' : '' }}">
            <a href="{{ route('admin.settings.car_settings.index') }}"
               class="pjax-link"> {{ trans('admin.settings.car_settings.title') }} </a>
        </li>

        @permission('view.notification.types')
        <li class="{{ str_contains(request()->url(), route('admin.settings.notifications.index')) ? 'active' : '' }}">
            <a href="{{ route('admin.settings.notifications.index') }}" class="pjax-link"> {{ trans('admin.notifications.title') }} </a>
        </li>
        @endpermission
        @permission('view.wishes.types')
        <li class="{{ str_contains(request()->url(), route('admin.settings.wishes.index')) ? 'active' : '' }}">
            <a href="{{ route('admin.settings.wishes.index') }}" class="pjax-link"> {{ trans('admin.wishes.title') }} </a>
        </li>
        @endpermission
    </ul>
</li>
@endpermission

@permission('view.cron')
<!--<li class="{{ str_contains(request()->url(), route('admin.cron.index')) ? 'active' : '' }}">
    <a href="{{ route('admin.cron.index', ['status' => 'active']) }}" class="pjax-link">
        <i class="fa fa-briefcase"></i>
        <span class="text">  {{ trans('admin.cron.title') }}</span>
    </a>
</li>-->
@endpermission
