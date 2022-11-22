<?php

use App\Models\Role;
use Bican\Roles\Models\Permission;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $superAdminRole = Role::firstOrCreate(['name' => trans('admin.users.roles.' . Role::ROLE_SUPER_ADMIN), 'slug' => Role::ROLE_SUPER_ADMIN]);
        $adminRole = Role::firstOrCreate(['name' => trans('admin.users.roles.' . Role::ROLE_ADMIN), 'slug' => Role::ROLE_ADMIN]);
        $operatorRole = Role::firstOrCreate(['name' => trans('admin.users.roles.' . Role::ROLE_OPERATOR), 'slug' => Role::ROLE_OPERATOR]);
        $agentRole = Role::firstOrCreate(['name' => trans('admin.users.roles.' . Role::ROLE_AGENT), 'slug' => Role::ROLE_AGENT]);
        $methodistRole = Role::firstOrCreate(['name' => trans('admin.users.roles.' . Role::ROLE_METHODIST), 'slug' => Role::ROLE_METHODIST]);

        $viewUsers = Permission::firstOrCreate([
            'name' => trans('admin.users.title'),
            'slug' => 'view.users',
        ]);

        $viewCompanies = Permission::firstOrCreate([
            'name' => trans('admin.companies.title'),
            'slug' => 'view.companies',
        ]);

        $viewAgreements = Permission::firstOrCreate([
            'name' => trans('admin.agreements.title'),
            'slug' => 'view.agreements',
        ]);

        $viewTariffs = Permission::firstOrCreate([
            'name' => trans('admin.tariffs.title'),
            'slug' => 'view.tariffs',
        ]);

        $viewBuses = Permission::firstOrCreate([
            'name' => trans('admin.buses.title'),
            'slug' => 'view.buses',
        ]);

        $viewPages = Permission::firstOrCreate([
            'name' => trans('admin.pages.title'),
            'slug' => 'view.pages',
        ]);

        $viewTemplates = Permission::firstOrCreate([
            'name' => trans('admin.buses.templates.title'),
            'slug' => 'view.templates',
        ]);

        $viewDiagnosticCard = Permission::firstOrCreate([
            'name' => trans('admin.buses.diagnostic_cards.title'),
            'slug' => 'view.diagnosticcards',
        ]);

        $viewReviewAct = Permission::firstOrCreate([
            'name' => trans('admin.buses.review_acts.title'),
            'slug' => 'view.reviewacts',
        ]);

        $viewDrivers = Permission::firstOrCreate([
            'name' => trans('admin.drivers.title'),
            'slug' => 'view.drivers',
        ]);

        $viewReviews = Permission::firstOrCreate([
            'name' => trans('admin.reviews.title'),
            'slug' => 'view.reviews',
        ]);

        $viewRoutes = Permission::firstOrCreate([
            'name' => trans('admin.routes.title'),
            'slug' => 'view.routes',
        ]);

        $viewCities = Permission::firstOrCreate([
            'name' => trans('admin.routes.cities.title'),
            'slug' => 'view.cities',
        ]);

        $viewSchedules = Permission::firstOrCreate([
            'name' => trans('admin.schedules.title'),
            'slug' => 'view.schedules',
        ]);

        $viewTours = Permission::firstOrCreate([
            'name' => trans('admin.tours.title'),
            'slug' => 'view.tours',
        ]);

        $viewClients = Permission::firstOrCreate([
            'name' => trans('admin.clients.title'),
            'slug' => 'view.clients',
        ]);

        $viewOrders = Permission::firstOrCreate([
            'name' => trans('admin.orders.title'),
            'slug' => 'view.orders',
        ]);

        $viewSetting = Permission::firstOrCreate([
            'name' => trans('admin.settings.title'),
            'slug' => 'view.settings',
        ]);

        $viewMonitoring = Permission::firstOrCreate([
            'name' => trans('admin.monitoring.title'),
            'slug' => 'view.monitoring',
        ]);

        $viewRent = Permission::firstOrCreate([
            'name' => trans('admin.rent.title'),
            'slug' => 'view.rents',
        ]);

        $viewRepairs = Permission::firstOrCreate([      // Старые ремонт и добавление автобусов
            'name' => trans('admin.buses.repairs.title'),
            'slug' => 'view.repairs',
        ]);
        
        $viewMaintenance = Permission::firstOrCreate([
            'name' => trans('admin.exploitation.title'),
            'slug' => 'view.maintenance',
        ]);

        $viewNotification = Permission::firstOrCreate([
            'name' => trans('admin.notifications.title'),
            'slug' => 'view.notifications',
        ]);

        $viewGarage = Permission::firstOrCreate([
            'name' => trans('admin.garage.title'),
            'slug' => 'view.garage',
        ]);

        $viewOperational = Permission::firstOrCreate([
            'name' => trans('admin.operational_tasks.title'),
            'slug' => 'view.operational',
        ]);

        $viewRepair = Permission::firstOrCreate([
            'name' => trans('admin.repair_orders.title'),
            'slug' => 'view.repair',
        ]);

        $viewRepairOrder = Permission::firstOrCreate([
            'name' => trans('admin.repair_orders.repair_order'),
            'slug' => 'view.repair.order',
        ]);

        $viewRepairOrderOutfit = Permission::firstOrCreate([
            'name' => trans('admin.repair_orders.order_outfit'),
            'slug' => 'view.repair.orderoutfit',
        ]);
        $viewRepairCard = Permission::firstOrCreate([
            'name' => trans('admin.repair_orders.diagnostic_cards.card_list'),
            'slug' => 'view.repair.card',
        ]);
        $viewRepairSparePart = Permission::firstOrCreate([
            'name' => trans('admin.repair_orders.spare_parts.parts'),
            'slug' => 'view.repair.parts',
        ]);

        $viewNotifications = Permission::firstOrCreate([
            'name' => trans('admin.notifications.role_hr'),
            'slug' => 'view.notifications.hr',
        ]);
        $viewWishes = Permission::firstOrCreate([
            'name' => trans('admin.wishes.title'),
            'slug' => 'view.wishes',
        ]);
        $viewWishesType = Permission::firstOrCreate([
            'name' => trans('admin.wishes.title'),
            'slug' => 'view.wishes.types',
        ]);

        $viewWishesNotification = Permission::firstOrCreate([
            'name' => trans('admin.wishes.notify'),
            'slug' => 'view.wishes.notify',
        ]);

        $viewNotificationTypes = Permission::firstOrCreate([
            'name' => trans('admin.settings.notifications.title'),
            'slug' => 'view.notification.types',
        ]);
        
        $adminRole->attachPermission($viewCompanies);
        $adminRole->attachPermission($viewBuses);
        $adminRole->attachPermission($viewDrivers);
        $adminRole->attachPermission($viewReviews);
        $adminRole->attachPermission($viewRoutes);
        $adminRole->attachPermission($viewSchedules);
        $adminRole->attachPermission($viewTours);
        $adminRole->attachPermission($viewClients);
        $adminRole->attachPermission($viewOrders);
        $adminRole->attachPermission($viewTemplates);
        $adminRole->attachPermission($viewRepairs);
        $adminRole->attachPermission($viewCities);
        $adminRole->attachPermission($viewRepair);
        $adminRole->attachPermission($viewSetting);
        $adminRole->attachPermission($viewPages);
        $adminRole->attachPermission($viewRent);
        $adminRole->attachPermission($viewDiagnosticCard);
        $adminRole->attachPermission($viewReviewAct);
        $adminRole->attachPermission($viewWishes);
        $adminRole->attachPermission($viewRepair);
        $adminRole->attachPermission($viewRepairOrder);
        $adminRole->attachPermission($viewRepairOrderOutfit);
        $adminRole->attachPermission($viewRepairCard);
        $adminRole->attachPermission($viewRepairSparePart);
        $adminRole->attachPermission($viewWishesNotification);

        $superAdminRole->attachPermission($viewUsers);
        $superAdminRole->attachPermission($viewCompanies);
        $superAdminRole->attachPermission($viewBuses);
        $superAdminRole->attachPermission($viewDrivers);
        $superAdminRole->attachPermission($viewReviews);
        $superAdminRole->attachPermission($viewRoutes);
        $superAdminRole->attachPermission($viewSchedules);
        $superAdminRole->attachPermission($viewTours);
        $superAdminRole->attachPermission($viewClients);
        $superAdminRole->attachPermission($viewOrders);
        $superAdminRole->attachPermission($viewTemplates);
        $superAdminRole->attachPermission($viewRepairs);
        $superAdminRole->attachPermission($viewCities);
        $superAdminRole->attachPermission($viewRepair);
        $superAdminRole->attachPermission($viewSetting);
        $superAdminRole->attachPermission($viewPages);
        $superAdminRole->attachPermission($viewMonitoring);
        $superAdminRole->attachPermission($viewRent);
        $superAdminRole->attachPermission($viewAgreements);
        $superAdminRole->attachPermission($viewTariffs);
        $superAdminRole->attachPermission($viewDiagnosticCard);
        $superAdminRole->attachPermission($viewReviewAct);
        $superAdminRole->attachPermission($viewMaintenance);
        $superAdminRole->attachPermission($viewRepair);
        $superAdminRole->attachPermission($viewRepairOrder);
        $superAdminRole->attachPermission($viewRepairOrderOutfit);
        $superAdminRole->attachPermission($viewRepairCard);
        $superAdminRole->attachPermission($viewRepairSparePart);
        $superAdminRole->attachPermission($viewNotification);
        $superAdminRole->attachPermission($viewNotificationTypes);
        $superAdminRole->attachPermission($viewWishes);
        $superAdminRole->attachPermission($viewWishesType);
        $superAdminRole->attachPermission($viewGarage);
        $superAdminRole->attachPermission($viewOperational);
        $superAdminRole->attachPermission($viewWishesNotification);


        $operatorRole->attachPermission($viewBuses);
        $operatorRole->attachPermission($viewDrivers);
        $operatorRole->attachPermission($viewReviews);
        $operatorRole->attachPermission($viewRoutes);
        $operatorRole->attachPermission($viewSchedules);
        $operatorRole->attachPermission($viewTours);
        $operatorRole->attachPermission($viewClients);
        $operatorRole->attachPermission($viewOrders);
        $operatorRole->attachPermission($viewTemplates);
        $operatorRole->attachPermission($viewCities);
//        $operatorRole->attachPermission($viewRepairs);
        $operatorRole->attachPermission($viewDiagnosticCard);
        $operatorRole->attachPermission($viewReviewAct);
        $operatorRole->attachPermission($viewWishes);

        $operatorRole->attachPermission($viewRepair);

        $agentRole->attachPermission($viewBuses);
        $agentRole->attachPermission($viewDrivers);
        $agentRole->attachPermission($viewReviews);
        $agentRole->attachPermission($viewRoutes);
        $agentRole->attachPermission($viewSchedules);
        $agentRole->attachPermission($viewTours);
        $agentRole->attachPermission($viewClients);
        $agentRole->attachPermission($viewOrders);
        $agentRole->attachPermission($viewTemplates);
        $agentRole->attachPermission($viewCities);
//        $agentRole->attachPermission($viewRepairs);
        $agentRole->attachPermission($viewDiagnosticCard);
        $agentRole->attachPermission($viewReviewAct);
        $agentRole->attachPermission($viewNotifications);
        $agentRole->attachPermission($viewWishes);
        $agentRole->attachPermission($viewRepair);


        $methodistRole->attachPermission($viewRent);
    }
}
