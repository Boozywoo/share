@extends('index.layouts.main')

@section('title', trans('index.profile.ticket') . $order->id)

@section('content')

    <div class="item-page personalCabinet personalCabinetSM myTickets mainWidth backg">
        <ul class="breadCrumbs">
            <li><a href="{{ route('index.home') }}">{{ trans('admin.home.title') }}</a></li>
            <li><a href="{{ route('index.profile.settings.index') }}">{{ trans('index.profile.personal_account')}}</a></li>
            <li><a href="{{ route('index.profile.tickets.index') }}">{{ trans('index.profile.my_tickets')}}</a></li>
            <li><a class="thisPage">{{ trans('index.profile.ticket')}} №{{ $order->number }}</a></li>
        </ul>
        <div class="personalCabinet myTickets" style="margin-top: 20px;">
            <div class="mainWidth">
                <div class="left">
                    @include('index.profile.partials.menu')
                </div>
                    <div class="right">
                        @include('index.order.partials.ticket')
                        @if ((env('PRINT') && !\App\Models\Setting::first()->is_pay_on) || (env('PRINT') && $order->StatusPay == \App\Models\Order::TYPE_PAY_SUCCESS))
                        
                        @endif
                    </div>
            <br style="clear: both"/>
            </div>
        </div>
    </div>
    <style>
        .small_warning {
            margin: 20px 0;
            padding: 6px;
            background-color: #a83e3e;
            color:white;
            border-radius: 10px;
            font-size: 0.8em;
        }

        .small_warning a {
            text-decoration: underline;
        }
    </style>
@endsection