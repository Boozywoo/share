
@extends('panel::layouts.main')

@section('title', trans('admin.'. $entity . '.title'))

@section('main')
    <div class="col-md-4">
        <div class="ibox">
            <div class="ibox-content text-center">
                <a href="{{ route('admin.tours.index') }}" class="pjax-link"><h2><i class="fa fa-road"></i> {{ trans('admin.tours.title') }}</h2></a><br />
                Активных:<br />
                Ближайший:<br />
            </div>
        </div>
        <div class="ibox">
            <div class="ibox-content text-center">
                <a href="{{ route('admin.buses.index') }}" class="pjax-link"><h2><i class="fa fa-bus"></i> {{ trans('admin.buses.title') }}</h2></a><br />
                В рейсе:<br />
                Всего:<br />
            </div>
        </div>
        <div class="ibox">
            <div class="ibox-content text-center">
                <a href="{{ route('admin.settings.edit') }}" class="pjax-link"><h2><i class="fa fa-cog"></i> {{ trans('admin.settings.title') }}</h2></a>
                <br />
                Общие<br />
                Мобильное приложение<br />
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="ibox">
            <div class="ibox-content text-center">
                <a href="{{ route('admin.orders.index') }}" class="pjax-link"><h2><i class="fa fa-edit"></i> {{ trans('admin.orders.title') }}</h2></a>
                <br />
                На сегодня:<br /><br />
            </div>
        </div>
        <div class="ibox">
            <div class="ibox-content text-center">
                <a href="{{ route('admin.clients.index') }}" class="pjax-link"><h2><i class="fa fa-users"></i> {{ trans('admin.clients.title') }}</h2></a>
                <br />
                Всего:<br />
                Новых:<br />
            </div>
        </div>
        <div class="ibox">
            <div class="ibox-content text-center">
                <a href="{{ route('admin.routes.index') }}" class="pjax-link"><h2><i class="fa fa-map-signs"></i> {{ trans('admin.routes.title') }}</h2></a>
                <br />
                Всего:<br /><br />
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="ibox">
            <div class="ibox-content text-center">
                <a href="{{ route('admin.schedules.index') }}" class="pjax-link"><h2><i class="fa fa-calendar"></i> {{ trans('admin.schedules.title') }}</h2></a>
                <br />
                Активных:<br /><br />
            </div>
        </div>
        <div class="ibox">
            <div class="ibox-content text-center">
                <a href="{{ route('admin.drivers.index') }}" class="pjax-link"><h2><i class="fa fa-tachometer"></i> {{ trans('admin.drivers.title') }}</h2></a>
                <br />
                В рейсе:<br />
                Всего:<br />
            </div>
        </div>
        <div class="ibox">
            <div class="ibox-content text-center">
                <a href="{{ route('admin.users.statistic') }}" class="pjax-link"><h2><i class="fa fa-level-up"></i> {{ trans('admin_labels.statistics') }}</h2></a>
                <br />
                Всего:<br />
                <br />
            </div>
        </div>
    </div>
@endsection

