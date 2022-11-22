@extends('panel::root')

@section('root')
    <div id="wrapper">
        @include('panel::layouts.partials.sidebar')
        <div id="page-wrapper" class="no-paddings">
            <div class="wrapper-spinner">
                <div class="spinner-example">
                    <div class="sk-spinner sk-spinner-three-bounce">
                        <div class="sk-bounce1"></div>
                        <div class="sk-bounce2"></div>
                        <div class="sk-bounce3"></div>
                    </div>
                </div>
            </div>
            <div id="page-wrapper-right">
                @include('admin.partials.navbar')
                <div id="pjax-container">
                    <div class="row wrapper border-bottom white-bg page-heading small-row row-width-correct">
                        <div class="col-sm-6 left">
                            <h2>
                                @yield('title')
                            </h2>
                            @include('panel::layouts.partials.breadcrumbs')
                        </div>
                        <div class="col-sm-6 right">
                            <div class="title-action">
                                @yield('actions')
                            </div>
                        </div>
                    </div>
                    <div class="wrapper wrapper-content">
                        @include('panel::helpers.alert')
                        @yield('main')
                    </div>
                </div>
                @include('panel::layouts.partials.footer')
            </div>
        </div>
        {{--<div id="small-chat">
            <a href="{{ route('admin.orders.create') }}" class="btn bd-r btn-lg btn-success pjax-link"  data-toggle="tooltip" title="{{trans('admin.orders.create')}}">
                <i class="fa fa-plus"></i>
            </a>
        </div>
        @include('admin.partials.notifications')--}}
    </div>
@stop