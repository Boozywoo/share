<?php

//--Admin--//

Breadcrumbs::register('admin.home', function($breadcrumbs) {
    $breadcrumbs->push(trans('admin.home.title'), route('admin.home'));
});

//Users
Breadcrumbs::register('admin.users.index', function($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.users.title'), route('admin.users.index'));
});
Breadcrumbs::register('admin.users.create', function($breadcrumbs) {
    $breadcrumbs->parent('admin.users.index');
    $breadcrumbs->push(trans('admin.users.create'), route('admin.users.create'));
});
Breadcrumbs::register('admin.users.edit', function($breadcrumbs, $user) {
    $breadcrumbs->parent('admin.users.index');
    $breadcrumbs->push(trans('admin.users.edit'). ' '.  $user->first_name, null);
});

//Permissions
Breadcrumbs::register('admin.users.permissions.index', function($breadcrumbs) {
    $breadcrumbs->parent('admin.users.index');
    $breadcrumbs->push(trans('admin.users.permissions.title'), null);
});

//Notifications
Breadcrumbs::register('admin.notifications.noti-index', function($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.notifications.title'), route('admin.notifications.noti-index'));
});

Breadcrumbs::register('admin.notifications.noti-edit', function($breadcrumbs, $notification) {
    $breadcrumbs->parent('admin.notifications.noti-index');
    $breadcrumbs->push(trans('admin.notifications.edit'). ' '.  $notification->id, null);
});

//Companies
Breadcrumbs::register('admin.companies.index', function($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.companies.title'), route('admin.companies.index'));
});
Breadcrumbs::register('admin.companies.statics', function($breadcrumbs) {
    $breadcrumbs->parent('admin.companies.index');
    $breadcrumbs->push(trans('admin.companies.statics'), route('admin.companies.statics'));
});
Breadcrumbs::register('admin.companies.create', function($breadcrumbs) {
    $breadcrumbs->parent('admin.companies.index');
    $breadcrumbs->push(trans('admin.companies.create'), route('admin.companies.create'));
});
Breadcrumbs::register('admin.companies.edit', function($breadcrumbs, $company) {
    $breadcrumbs->parent('admin.companies.index');
    $breadcrumbs->push(trans('admin.companies.edit'). ' '.  $company->first_name, null);
});

// Departments
Breadcrumbs::register('admin.companies.departments.index', function($breadcrumbs, $company) {
    $breadcrumbs->parent('admin.companies.index');
    $breadcrumbs->push(trans('admin.companies.departments.title'). ' '.  $company->name, null);
});
Breadcrumbs::register('admin.companies.departments.create', function($breadcrumbs, $company) {
    $breadcrumbs->parent('admin.companies.index');
    $breadcrumbs->push(trans('admin.companies.single'). ' '.  $company->name, route('admin.companies.departments.index', $company));
    $breadcrumbs->push(trans('admin.companies.departments.create'));
});
Breadcrumbs::register('admin.companies.departments.edit', function($breadcrumbs, $company, $department) {
    $breadcrumbs->parent('admin.companies.index');
    $breadcrumbs->push(trans('admin.companies.single'). ' '.  $company->name, route('admin.companies.departments.index', $company));
    $breadcrumbs->push(trans('admin.companies.departments.edit').' '. $department->name);
});
Breadcrumbs::register('admin.companies.departments.list', function($breadcrumbs, $company, $department) {
    $breadcrumbs->parent('admin.companies.index');
    $breadcrumbs->push(trans('admin.companies.single'). ' '.  $company->name, route('admin.companies.departments.index', $company));
    $breadcrumbs->push(trans('admin.companies.departments.single').' '. $department->name);
    $breadcrumbs->push(trans('admin.companies.departments.index.list'));
});

//Buses
Breadcrumbs::register('admin.buses.index', function($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.buses.title'), route('admin.buses.index'));
});
Breadcrumbs::register('admin.buses.statics', function($breadcrumbs) {
    $breadcrumbs->parent('admin.buses.index');
    $breadcrumbs->push(trans('admin.buses.statics'), route('admin.buses.statics'));
});
Breadcrumbs::register('admin.buses.create', function($breadcrumbs) {
    $breadcrumbs->parent('admin.buses.index');
    $breadcrumbs->push(trans('admin.buses.create'), route('admin.buses.create'));
});
Breadcrumbs::register('admin.buses.edit', function($breadcrumbs, $bus) {
    $breadcrumbs->parent('admin.buses.index');
    $breadcrumbs->push(trans('admin.buses.edit'). ' '.  $bus->first_name, null);
});

//Repairs
Breadcrumbs::register('admin.buses.repairs.index', function($breadcrumbs) {
    $breadcrumbs->parent('admin.buses.index');
    $breadcrumbs->push(trans('admin.buses.repairs.title'), route('admin.buses.repairs.index', ['bus_id' => request('bus_id')]));
});
Breadcrumbs::register('admin.buses.repairs.create', function($breadcrumbs) {
    $breadcrumbs->parent('admin.buses.repairs.index');
    $breadcrumbs->push(trans('admin.buses.repairs.create'), route('admin.buses.repairs.create'));
});
Breadcrumbs::register('admin.buses.repairs.edit', function($breadcrumbs, $repair) {
    $breadcrumbs->parent('admin.buses.repairs.index');
    $breadcrumbs->push(trans('admin.buses.repairs.edit'), null);
});

//Templates
Breadcrumbs::register('admin.buses.templates.index', function($breadcrumbs) {
    $breadcrumbs->parent('admin.buses.index');
    $breadcrumbs->push(trans('admin.buses.templates.title'), route('admin.buses.templates.index'));
});
Breadcrumbs::register('admin.buses.templates.create', function($breadcrumbs) {
    $breadcrumbs->parent('admin.buses.templates.index');
    $breadcrumbs->push(trans('admin.buses.templates.create'), route('admin.buses.templates.create'));
});
Breadcrumbs::register('admin.buses.templates.edit', function($breadcrumbs, $template) {
    $breadcrumbs->parent('admin.buses.templates.index');
    $breadcrumbs->push(trans('admin.buses.templates.edit'), null);
});

//Drivers
Breadcrumbs::register('admin.drivers.index', function($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.drivers.title'), route('admin.drivers.index'));
});
Breadcrumbs::register('admin.drivers.statics', function($breadcrumbs) {
    $breadcrumbs->parent('admin.drivers.index');
    $breadcrumbs->push(trans('admin.drivers.statics'), route('admin.drivers.statics'));
});
Breadcrumbs::register('admin.drivers.create', function($breadcrumbs) {
    $breadcrumbs->parent('admin.drivers.index');
    $breadcrumbs->push(trans('admin.drivers.create'), route('admin.drivers.create'));
});
Breadcrumbs::register('admin.drivers.edit', function($breadcrumbs, $driver) {
    $breadcrumbs->parent('admin.drivers.index');
    $breadcrumbs->push(trans('admin.drivers.edit'). ' '.  $driver->full_name, null);
});

//Routes
Breadcrumbs::register('admin.routes.index', function($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.routes.title'), route('admin.routes.index'));
});
Breadcrumbs::register('admin.routes.create', function($breadcrumbs) {
    $breadcrumbs->parent('admin.routes.index');
    $breadcrumbs->push(trans('admin.routes.create'), route('admin.routes.create'));
});
Breadcrumbs::register('admin.routes.edit', function($breadcrumbs, $city) {
    $breadcrumbs->parent('admin.routes.index');
    $breadcrumbs->push(trans('admin.routes.edit'). ' '.  $city->name, null);
});

//Cities
Breadcrumbs::register('admin.routes.cities.index', function($breadcrumbs) {
    $breadcrumbs->parent('admin.routes.index');
    $breadcrumbs->push(trans('admin.routes.cities.title'), route('admin.routes.cities.index'));
});
Breadcrumbs::register('admin.routes.cities.create', function($breadcrumbs) {
    $breadcrumbs->parent('admin.routes.cities.index');
    $breadcrumbs->push(trans('admin.routes.cities.create'), route('admin.routes.cities.create'));
});
Breadcrumbs::register('admin.routes.cities.edit', function($breadcrumbs, $city) {
    $breadcrumbs->parent('admin.routes.cities.index');
    $breadcrumbs->push(trans('admin.routes.cities.edit'). ' '.  $city->name, null);
});

//Stations
Breadcrumbs::register('admin.routes.stations.index', function($breadcrumbs) {
    $breadcrumbs->parent('admin.routes.index');
    $breadcrumbs->push(trans('admin.routes.stations.title'), route('admin.routes.stations.index'));
});
Breadcrumbs::register('admin.routes.stations.create', function($breadcrumbs) {
    $breadcrumbs->parent('admin.routes.stations.index');
    $breadcrumbs->push(trans('admin.routes.stations.create'), route('admin.routes.stations.create'));
});
Breadcrumbs::register('admin.routes.stations.edit', function($breadcrumbs, $station) {
    $breadcrumbs->parent('admin.routes.stations.index');
    $breadcrumbs->push(trans('admin.routes.stations.edit'). ' '.  $station->name, null);
});

//Schedules
Breadcrumbs::register('admin.schedules.index', function($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.schedules.title'), route('admin.schedules.index'));
});
Breadcrumbs::register('admin.schedules.create', function($breadcrumbs) {
    $breadcrumbs->parent('admin.schedules.index');
    $breadcrumbs->push(trans('admin.schedules.create'), route('admin.schedules.create'));
});
Breadcrumbs::register('admin.schedules.edit', function($breadcrumbs) {
    $breadcrumbs->parent('admin.schedules.index');
    $breadcrumbs->push(trans('admin.schedules.edit'), null);
});

//Clients
Breadcrumbs::register('admin.clients.index', function($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.clients.title'), route('admin.clients.index'));
});
Breadcrumbs::register('admin.clients.statics', function($breadcrumbs) {
    $breadcrumbs->parent('admin.clients.index');
    $breadcrumbs->push(trans('admin.clients.statics'), route('admin.clients.statics'));
});
Breadcrumbs::register('admin.clients.create', function($breadcrumbs) {
    $breadcrumbs->parent('admin.clients.index');
    $breadcrumbs->push(trans('admin.clients.create'), route('admin.clients.create'));
});
Breadcrumbs::register('admin.clients.edit', function($breadcrumbs, $client) {
    $breadcrumbs->parent('admin.clients.index');
    $breadcrumbs->push(trans('admin.clients.edit'). ' '. $client->first_name, null);
});

//Tours
Breadcrumbs::register('admin.tours.index', function($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.tours.title'), route('admin.tours.index'));
});

//Orders
Breadcrumbs::register('admin.orders.index', function($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.orders.title'), route('admin.orders.index'));
});
Breadcrumbs::register('admin.orders.create', function($breadcrumbs) {
    $breadcrumbs->parent('admin.orders.index');
    $breadcrumbs->push(trans('admin.orders.create'), route('admin.orders.create'));
});
Breadcrumbs::register('admin.orders.edit', function($breadcrumbs, $order) {
    $breadcrumbs->parent('admin.orders.index');
    $breadcrumbs->push(trans('admin.orders.edit'). ' №'. $order->id, null);
});

//Pulls
Breadcrumbs::register('admin.pulls.orders', function($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.pulls.title'), route('admin.pulls.orders'));
});
Breadcrumbs::register('admin.pulls.tours', function($breadcrumbs) {
    $breadcrumbs->parent('admin.pulls.orders');
    $breadcrumbs->push(trans('admin.tours.list'), route('admin.pulls.tours'));
});

//Reviews
Breadcrumbs::register('admin.reviews.index', function($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.reviews.title'), route('admin.reviews.index'));
});

//Repair Area, Repair Orders
Breadcrumbs::register('admin.repair_orders.index', function($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.repair_orders.title'), route('admin.repair_orders.index'));
});
Breadcrumbs::register('admin.repair_orders.create', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.repair_orders.index');
    $breadcrumbs->push(trans('admin.repair_orders.create'), route('admin.repair_orders.create'));
});
Breadcrumbs::register('admin.repair_orders.edit', function ($breadcrumbs, $repairOrder) {
    $breadcrumbs->parent('admin.repair_orders.index');
    $breadcrumbs->push(trans('admin.repair_orders.show') . ' №' . $repairOrder->id, route('admin.repair_orders.show', $repairOrder->id));
    $breadcrumbs->push(trans('admin.repair_orders.edit') . ' №' . $repairOrder->id, route('admin.repair_orders.edit', $repairOrder->id));
});
Breadcrumbs::register('admin.repair_orders.show', function ($breadcrumbs, $repairOrder) {
    $breadcrumbs->parent('admin.repair_orders.index');
    $breadcrumbs->push(trans('admin.repair_orders.show') . ' №' . $repairOrder->id, route('admin.repair_orders.show', $repairOrder->id));
});
Breadcrumbs::register('admin.repair_orders.order_outfits.create', function ($breadcrumbs, $repairOrder) {
    $breadcrumbs->parent('admin.repair_orders.index');
    $breadcrumbs->push(trans('admin.repair_orders.show') . ' №' . $repairOrder->id, route('admin.repair_orders.show', $repairOrder->id));
    $breadcrumbs->push(trans('admin.repair_orders.order_outfits.create'), route('admin.repair_orders.order_outfits.create', $repairOrder->id));
});
Breadcrumbs::register('admin.repair_orders.order_outfits.edit', function ($breadcrumbs, $repairOrder, $orderOutfit) {
    $breadcrumbs->parent('admin.repair_orders.index');
    $breadcrumbs->push(trans('admin.repair_orders.show') . ' №' . $repairOrder->id, route('admin.repair_orders.show', $repairOrder->id));
    $breadcrumbs->push(trans('admin.repair_orders.order_outfits.edit') . ' №' . $orderOutfit->id, route('admin.repair_orders.order_outfits.edit', [$repairOrder->id, $orderOutfit->id]));
});
Breadcrumbs::register('admin.repair_orders.spare_parts.index', function ($breadcrumbs, $repairOrder) {
    $breadcrumbs->parent('admin.repair_orders.index');
    $breadcrumbs->push(trans('admin.repair_orders.show') . ' №' . $repairOrder->id, route('admin.repair_orders.show', $repairOrder->id));
    $breadcrumbs->push(trans('admin.repair_orders.spare_parts.title') . $repairOrder->order_outfit->id, route('admin.repair_orders.spare_parts.index', [$repairOrder->id]));
});

//Settings
Breadcrumbs::register('admin.settings.edit', function($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.general'), route('admin.settings.edit', 0));
});

//Settings SMS
Breadcrumbs::register('admin.settings.smsconfig.edit', function($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.smsconfig.title'), route('admin.settings.smsconfig.edit'));
});

//Settings Driver app
Breadcrumbs::register('admin.settings.driverapp.edit', function($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.driverapp.title'), route('admin.settings.driverapp.edit'));
});

//Settings Mobile app
Breadcrumbs::register('admin.settings.mobile_app.edit', function($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.mobile_app.title'), route('admin.settings.mobile_app.edit'));
});

//Settings Roles
Breadcrumbs::register('admin.settings.roles.edit', function($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.roles.title'), route('admin.settings.roles.index'));
    $breadcrumbs->push(trans('admin.settings.roles.edit'));
});
Breadcrumbs::register('admin.settings.roles.create', function($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.roles.title'), route('admin.settings.roles.index'));
    $breadcrumbs->push(trans('admin.settings.roles.create'));
});

//Statuses
Breadcrumbs::register('admin.settings.statuses.index', function($breadcrumbs) {
    $breadcrumbs->parent('admin.settings.edit');
    $breadcrumbs->push(trans('admin.settings.statuses.title'), route('admin.settings.statuses.index'));
});
Breadcrumbs::register('admin.settings.statuses.create', function($breadcrumbs) {
    $breadcrumbs->parent('admin.settings.statuses.index');
    $breadcrumbs->push(trans('admin.settings.statuses.create'), route('admin.settings.statuses.create'));
});
Breadcrumbs::register('admin.settings.statuses.edit', function($breadcrumbs, $status) {
    $breadcrumbs->parent('admin.settings.statuses.index');
    $breadcrumbs->push(trans('admin.settings.statuses.edit'). ' ' . $status->name, null);
});

//Sales
Breadcrumbs::register('admin.settings.sales.index', function($breadcrumbs) {
    $breadcrumbs->parent('admin.settings.edit');
    $breadcrumbs->push(trans('admin.settings.sales.title'), route('admin.settings.sales.index'));
});
Breadcrumbs::register('admin.settings.sales.create', function($breadcrumbs) {
    $breadcrumbs->parent('admin.settings.sales.index');
    $breadcrumbs->push(trans('admin.settings.sales.create'), route('admin.settings.sales.create'));
});
Breadcrumbs::register('admin.settings.sales.edit', function($breadcrumbs, $status) {
    $breadcrumbs->parent('admin.settings.sales.index');
    $breadcrumbs->push(trans('admin.settings.sales.edit'). ' ' . $status->name, null);
});

//Coupons
Breadcrumbs::register('admin.settings.coupons.index', function($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.coupons.title'), route('admin.settings.coupons.index'));
});
Breadcrumbs::register('admin.settings.coupons.create', function($breadcrumbs) {
    $breadcrumbs->parent('admin.settings.coupons.index');
    $breadcrumbs->push(trans('admin.settings.coupons.create'), route('admin.settings.coupons.create'));
});
Breadcrumbs::register('admin.settings.coupons.edit', function($breadcrumbs, $coupon) {
    $breadcrumbs->parent('admin.settings.coupons.index');
    $breadcrumbs->push(trans('admin.settings.coupons.edit'). ' ' . $coupon->name, null);
});

//Pages
Breadcrumbs::register('admin.pages.index', function($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.pages.title'), route('admin.pages.index'));
});
Breadcrumbs::register('admin.pages.create', function($breadcrumbs) {
    $breadcrumbs->parent('admin.pages.index');
    $breadcrumbs->push(trans('admin.pages.create'), route('admin.pages.create'));
});
Breadcrumbs::register('admin.pages.edit', function($breadcrumbs, $page) {
    $breadcrumbs->parent('admin.pages.index');
    $breadcrumbs->push(trans('admin.pages.edit') . ' ' . $page->title, route('admin.pages.edit', $page));
});

//Incidents
Breadcrumbs::register('admin.incidents.index', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.incidents.title'), route('admin.incidents.index'));
});

// Car breakages
Breadcrumbs::register('admin.settings.exploitation.breakages.index', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.breakages.title'), route('admin.settings.exploitation.breakages.index'));
});
Breadcrumbs::register('admin.settings.exploitation.breakages.edit', function ($breadcrumbs, $breakage) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.breakages.title'), route('admin.settings.exploitation.breakages.index'));
    $breadcrumbs->push(trans('admin.settings.exploitation.breakages.edit'), route('admin.settings.exploitation.breakages.edit', $breakage->id));
});
Breadcrumbs::register('admin.settings.exploitation.breakages.create', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.breakages.title'), route('admin.settings.exploitation.breakages.index'));
    $breadcrumbs->push(trans('admin.settings.exploitation.breakages.create'), route('admin.settings.exploitation.breakages.create'));
});
Breadcrumbs::register('admin.settings.exploitation.breakages.show', function ($breadcrumbs, $breakage) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.breakages.title'), route('admin.settings.exploitation.breakages.index'));
    $breadcrumbs->push(trans('admin.settings.exploitation.breakages.items.list'), route('admin.settings.exploitation.breakages.show', $breakage->id));
});
// Review Acts
Breadcrumbs::register('admin.settings.exploitation.review.index', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.review.review_act_list'), route('admin.settings.exploitation.review.index'));
});
Breadcrumbs::register('admin.settings.exploitation.review.create', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.menu.review_templates'), route('admin.settings.exploitation.reviewTemplates'));
    $breadcrumbs->push(trans('admin.settings.exploitation.review.create_template'), route('admin.settings.exploitation.review.create'));
});
Breadcrumbs::register('admin.settings.exploitation.review.edit', function ($breadcrumbs, $reviewActTemplate) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.menu.review_templates'), route('admin.settings.exploitation.reviewTemplates'));
    $breadcrumbs->push(trans('admin.settings.exploitation.review.edit_template')." \"$reviewActTemplate->name\"", route('admin.settings.exploitation.review.edit', $reviewActTemplate));
});

Breadcrumbs::register('admin.settings.exploitation.review.items.index', function ($breadcrumbs,$reviewActTemplate) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.menu.review_templates'), route('admin.settings.exploitation.reviewTemplates'));
    $breadcrumbs->push(trans('admin.settings.exploitation.review.items.title')." \"$reviewActTemplate->name\"", route('admin.settings.exploitation.review.items.index',$reviewActTemplate));
});

// Templates Diagnostic Cards
Breadcrumbs::register('admin.settings.exploitation.diagnostic.index', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.diagnostic.list'), route('admin.settings.exploitation.diagnostic.index'));
});
Breadcrumbs::register('admin.settings.exploitation.diagnostic.create', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.menu.review_templates'), route('admin.settings.exploitation.reviewTemplates'));
    $breadcrumbs->push(trans('admin.settings.exploitation.diagnostic.create_template'), route('admin.settings.exploitation.diagnostic.create'));
});
Breadcrumbs::register('admin.settings.exploitation.diagnostic.edit', function ($breadcrumbs, $diagnosticCardTemplate) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.menu.review_templates'), route('admin.settings.exploitation.reviewTemplates'));
    $breadcrumbs->push(trans('admin.settings.exploitation.diagnostic.edit_template')." \"$diagnosticCardTemplate->name\"", route('admin.settings.exploitation.diagnostic.edit', $diagnosticCardTemplate));
});

Breadcrumbs::register('admin.settings.exploitation.diagnostic.items.index', function ($breadcrumbs, $diagnosticCardTemplate) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.menu.review_templates'), route('admin.settings.exploitation.reviewTemplates'));
    $breadcrumbs->push(trans('admin.settings.exploitation.diagnostic.items.title')." \"$diagnosticCardTemplate->name\"", route('admin.settings.exploitation.diagnostic.items.index',$diagnosticCardTemplate));
});

// Repair Cards
Breadcrumbs::register('admin.settings.exploitation.repair_cards.index', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.repair_cards.title'), route('admin.settings.exploitation.repair_cards.index'));
});
Breadcrumbs::register('admin.settings.exploitation.repair_cards.create', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.repair_cards.title'), route('admin.settings.exploitation.repair_cards.index'));
    $breadcrumbs->push(trans('admin.settings.exploitation.repair_cards.create_template'), route('admin.settings.exploitation.repair_cards.create'));
});
Breadcrumbs::register('admin.settings.exploitation.repair_cards.edit', function ($breadcrumbs, $repairCard) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.repair_cards.title'), route('admin.settings.exploitation.repair_cards.index'));
    $breadcrumbs->push(trans('admin.settings.exploitation.repair_cards.edit_template'), route('admin.settings.exploitation.repair_cards.edit', $repairCard));
});
Breadcrumbs::register('admin.settings.exploitation.repair_cards.show', function ($breadcrumbs, $repairCard) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.repair_cards.title'), route('admin.settings.exploitation.repair_cards.index'));
    $breadcrumbs->push(trans('admin.settings.exploitation.repair_cards.items.title'), route('admin.settings.exploitation.repair_cards.show', $repairCard));
});
Breadcrumbs::register('admin.settings.exploitation.repair_cards.items.create', function ($breadcrumbs, $repairCard) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.repair_cards.title'), route('admin.settings.exploitation.repair_cards.index'));
    $breadcrumbs->push(trans('admin.settings.exploitation.repair_cards.items.title'), route('admin.settings.exploitation.repair_cards.show', $repairCard));
    $breadcrumbs->push(trans('admin.settings.exploitation.repair_cards.items.create'), route('admin.settings.exploitation.repair_cards.items.create', $repairCard));
});
Breadcrumbs::register('admin.settings.exploitation.repair_cards.items.edit', function ($breadcrumbs, $repairCard, $item) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.repair_cards.title'), route('admin.settings.exploitation.repair_cards.index'));
    $breadcrumbs->push(trans('admin.settings.exploitation.repair_cards.items.title'), route('admin.settings.exploitation.repair_cards.show', $repairCard));
    $breadcrumbs->push(trans('admin.settings.exploitation.repair_cards.items.edit'), route('admin.settings.exploitation.repair_cards.items.edit', [$repairCard, $item]));
});
Breadcrumbs::register('admin.settings.exploitation.repair_card_types.show', function ($breadcrumbs, $repairCardType) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.repair_cards.title'), route('admin.settings.exploitation.repair_cards.index'));
    $breadcrumbs->push(trans('admin.settings.exploitation.repair_card_types.items.title') . " \"$repairCardType->name\"", route('admin.settings.exploitation.repair_card_types.show', $repairCardType));

});
Breadcrumbs::register('admin.settings.exploitation.repair_card_types.items.create', function ($breadcrumbs, $repairCardType) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.repair_cards.title'), route('admin.settings.exploitation.repair_cards.index'));
    $breadcrumbs->push(trans('admin.settings.exploitation.repair_card_types.items.title') . " \"$repairCardType->name\"", route('admin.settings.exploitation.repair_card_types.show', $repairCardType));
    $breadcrumbs->push(trans('admin.settings.exploitation.repair_card_types.items.create_title') . " \"$repairCardType->name\"", route('admin.settings.exploitation.repair_card_types.items.create', $repairCardType));
});

Breadcrumbs::register('admin.settings.exploitation.spare_parts.index', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.spare_parts.title'), route('admin.settings.exploitation.spare_parts.index'));
});
Breadcrumbs::register('admin.settings.exploitation.spare_parts.create', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.spare_parts.title'), route('admin.settings.exploitation.spare_parts.index'));
    $breadcrumbs->push(trans('admin.settings.exploitation.spare_parts.create'), route('admin.settings.exploitation.spare_parts.create'));
});
Breadcrumbs::register('admin.settings.exploitation.spare_parts.edit', function ($breadcrumbs, $sparePart) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.spare_parts.title'), route('admin.settings.exploitation.spare_parts.index'));
    $breadcrumbs->push(trans('admin.settings.exploitation.spare_parts.edit'), route('admin.settings.exploitation.spare_parts.edit', $sparePart));
});
Breadcrumbs::register('admin.settings.exploitation.spare_parts.items.create', function ($breadcrumbs, $sparePart) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.spare_parts.title'), route('admin.settings.exploitation.spare_parts.index'));
    $breadcrumbs->push(trans('admin.settings.exploitation.spare_parts.items.list') . " \"$sparePart->name\"", route('admin.settings.exploitation.spare_parts.items.index', $sparePart));
    $breadcrumbs->push(trans('admin.settings.exploitation.spare_parts.items.create'), route('admin.settings.exploitation.spare_parts.items.create', $sparePart));
});
Breadcrumbs::register('admin.settings.exploitation.spare_parts.items.index', function ($breadcrumbs, $sparePart) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.spare_parts.title'), route('admin.settings.exploitation.spare_parts.index'));
    $breadcrumbs->push(trans('admin.settings.exploitation.spare_parts.items.list') . " \"$sparePart->name\"", route('admin.settings.exploitation.spare_parts.items.index', $sparePart));
});
Breadcrumbs::register('admin.settings.exploitation.spare_parts.items.edit', function ($breadcrumbs, $sparePart, $item) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.spare_parts.title'), route('admin.settings.exploitation.spare_parts.index'));
    $breadcrumbs->push(trans('admin.settings.exploitation.spare_parts.items.list') . " \"$sparePart->name\"", route('admin.settings.exploitation.spare_parts.items.index', $sparePart));
    $breadcrumbs->push(trans('admin.settings.exploitation.spare_parts.items.edit') . " \"$item->name\"", route('admin.settings.exploitation.spare_parts.items.create', [$sparePart, $item]));
});
Breadcrumbs::register('admin.settings.exploitation.reviewTemplates', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.home');
    $breadcrumbs->push(trans('admin.settings.exploitation.menu.review_templates'), route('admin.settings.exploitation.reviewTemplates'));
});
