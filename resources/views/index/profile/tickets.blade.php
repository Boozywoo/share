@extends('index.layouts.main')

@section('title', trans('index.profile.my_tickets'))

@section('content')

    <div class="item-page personalCabinet personalCabinetSM myTickets mainWidth backg">
        <ul class="breadCrumbs">
            <li><a href="{{ route('index.home') }}">{{ trans('admin.home.title') }}</a></li>
            <li><a href="{{ route('index.profile.settings.index') }}">{{ trans('index.profile.personal_account')}}</a>
            </li>
            <li><a class="thisPage">{{ trans('index.profile.my_tickets')}}</a></li>
        </ul>
        <div class="personalCabinet myTickets">
            <div class="mainWidth">
                <div class="left">
                    {{--<p class="title">{{ trans('index.profile.my_tickets')}}</p>--}}
                    @include('index.profile.partials.menu')
                </div>
                <div class="right">
                    <ul class="switchMinibusesButtonsList">
                        @if($type == 'upcoming')
                            <li><a class="upcoming active">{{ trans('index.profile.upcoming')}}</a></li>
                            <li><a class="done">{{ trans('index.profile.completed')}}</a></li>
                        @else
                            <li><a class="upcoming">{{ trans('index.profile.upcoming')}}</a></li>
                            <li><a class="done activer">{{ trans('index.profile.completed')}}</a></li>
                        @endif
                        <li><a class="canceled">{{ trans('index.profile.canceled')}}</a></li>
                        <br style="clear: both">
                    </ul>
                    @if (!auth()->user()->client->email)
                        <div>
                            <div class="small_warning"><a href="{{ route('index.profile.settings.index') }}">
                                {{ trans('index.profile.warning_email_sent')}}
                            </a></div>
                        </div>
                    @endif


                    @include('index.profile.tickets.table', ['orders' => $futureOrders, 'type' => 'upcoming'])
                    @include('index.profile.tickets.table', ['orders' => $completedOrders, 'type' => 'done'])
                    @include('index.profile.tickets.table', ['orders' => $disabledOrders, 'type' => 'canceled'])
                    <div class='reviewPopup' order="" style='display: none'>
                        <form class="form-review" >
                            <p class='title'>{{ trans('index.profile.leave_feedback')}}</p>
                            <textarea class="text-review"></textarea>
                            <div class="stars">
                                <input class="star star-5" id="star-5" type="radio" name="star" value="5"/>
                                <label class="star star-5" for="star-5"></label>
                                <input class="star star-4" id="star-4" type="radio" name="star" value="4"/>
                                <label class="star star-4" for="star-4"></label>
                                <input class="star star-3" id="star-3" type="radio" name="star" value="3"/>
                                <label class="star star-3" for="star-3"></label>
                                <input class="star star-2" id="star-2" type="radio" name="star" value="2"/>
                                <label class="star star-2" for="star-2"></label>
                                <input class="star star-1" id="star-1" type="radio" name="star" value="1"/>
                                <label class="star star-1" for="star-1"></label>
                            </div>
                            <a class='sendReviewButt'>{{ trans('index.profile.send')}} </a>
                            <a class='closeButt'>x</a>
                            <input type='text' class='techHiddenInput' value='1' style='display: none'>
                        </form>
                    </div>
                </div>
                <br style="clear: both"/>
            </div>
            <div class="popupWrapper js_tickets-popup">
                <div class="popup">
                    <p class="question">{{ trans('index.profile.canceled_route')}}</p>
                    <div class="buttWrapp">
                        <a class="butt yes js_tickets-cancel">{{ trans('admin.home.yes')}}</a>
                        <a class="butt no js_tickets-close_popup">{{ trans('admin.home.no')}}</a>
                    </div>
                </div>
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

        div.stars {
            display: inline-block;
        }

        input.star { display: none; }

        label.star {
            float: right;
            font-size: 36px;
            color: #444;
            transition: all .2s;
        }

        input.star:checked ~ label.star:before {
            content: '\2605';
            color: #FD4;
            transition: all .25s;
        }

        input.star-5:checked ~ label.star:before {
            color: #FE7;
            text-shadow: 0 0 20px #952;
        }

        input.star-1:checked ~ label.star:before { color: #F62; }

        label.star:hover { transform: rotate(-15deg) scale(1.3); }

        label.star:before {
            content: '\2605';
        }
    </style>
@endsection