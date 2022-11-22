@extends('index.layouts.main')

@section('title', trans('index.profile.personal_account'))

@section('content')
@php($currency = (auth()->user()->currency) ? auth()->user()->currency->alfa : (!empty($curr_back->alfa)?$curr_back->alfa:'BYN'))

    <div class="item-page personalCabinet personalCabinetSM mainWidth backg">
        <ul class="breadCrumbs">
            <li><a href="{{ route('index.home') }}">{{ trans('admin.home.title') }}</a></li>
            <li><a class="thisPage">{{ trans('index.profile.personal_account')}}</a></li>
        </ul>
        <div class="presonalCabinetMainBlock personalCabinet">
            <div class="mainWidth">
                <p class="title">{{ trans('index.profile.personal_account')}}</p>
                <div class="left">
                    @include('index.profile.partials.menu')
                </div>
                <div class="right customerData">
                    <form action="{{ route('index.profile.settings.store') }}" method="POST"
                          class="userData js_ajax-form js_settings-form">
                        <label for="userName">{{ trans('index.profile.name')}}:</label><input class="js_settings-input" name="first_name" type="text"
                                                                 value="{{ auth()->user()->client->first_name }}"
                                                                 id="userName" disabled>
                        <label for="MiddleName">{{ trans('index.profile.patronymic')}}:</label><input class="js_settings-input" name="middle_name" type="text"
                                                                 value="{{ auth()->user()->client->middle_name }}"
                                                                 id="MiddleName" disabled>
                        <label for="LastName">{{ trans('index.profile.surname')}}:</label><input class="js_settings-input" name="last_name" type="text"
                                                                        value="{{ auth()->user()->client->last_name }}"
                                                                        id="LastName" disabled>
                        <label for="LastName">Номер карточки:</label><input class="js_settings-input" name="card" type="text"
                                                                     value="{{ auth()->user()->client->card }}"
                                                                     id="LastName" disabled>
                        <label for="Passport">{{ trans('index.profile.passport_series')}}:</label><input class="js_settings-input" name="passport" type="text"
                                                                     value="{{ auth()->user()->client->passport }}"
                                                                     id="Passport" disabled>
                        <label for="BirthDay">{{ trans('index.profile.birth_date')}}:</label><input class="js_settings-input js_datepicker" name="birth_day" type="text"
                                                                            value="{{  auth()->user()->client->birth_day ?  date('d.m.Y', strtotime(auth()->user()->client->birth_day)) : '' }}"
                                                                            id="BirthDay" disabled>
                        @if(env('TIME_ZONE'))

                            {{ Form::panelSelect('timezone',$timezonelist,auth()->user()->client ? auth()->user()->client->timezone : null ) }}
                        @endif
                        <label for="email">E-mail:</label><input class="js_settings-input" name="email" type="text"
                                                                 value="{{ auth()->user()->client->email }}" id="email"
                                                                 disabled>
                        <label for="phone">{{ trans('index.profile.phone')}}:</label><input class="js_settings-input js_mask-phone" name="phone"
                                                                  type="text"
                                                                  {{--value="+375{{ auth()->user()->client->editPhone }}"--}}
                                                                  value="+{{ auth()->user()->client->phone }}"
                                                                  id="phone" disabled>
                        <label for="password">{{ trans('index.profile.password')}}:</label><input class="js_settings-input" name="password"
                                                                    type="text" value="" id="password" disabled>
                        {{--<label for="confirmPassword">{{ trans('index.profile.pconfirm_of_pass')}}:</label><input type="text" id="confirmPassword" disabled>--}}
                        <label for="statusSel">{{ trans('index.profile.status')}}</label>
                        {!! Form::select('status_id', $statuses, auth()->user()->client->status_id, ['placeholder' => trans('admin.buses.sel_status'), 'id' => 'statusSel', 'class' => 'js_settings-input', 'disabled']) !!}
                        {{--<label for="regDate">{{ trans('index.profile.my_tickets')}}:</label>
                        <input type="text" value="08.02.2015" id="regDate" disabled>--}}
                        <br style="clear: both">
                        {{--<input type="checkbox" id="subscribeToNewsletter" disabled><label class="forCheckbox" for="subscribeToNewsletter">{{ trans('index.profile.subscribe_of_newsletter')}}</label>--}}
                        <br style="clear: both">
                    </form>
                    <a class="saveChangeButton change js_settings-edit">{{  trans('index.profile.change_data')}}</a>
                    <input type="submit" class="saveChangeButton save js_settings-save" style="display: none;"
                           value="{{ trans('admin.filter.save')}}">

                    <ul class="infoForUser"> 
                        {{--<li class="treepsNum">
                            <div class="pictureWrapper">
                                <div class="picture"></div>
                            </div>
                            <div class="inscriptionWrapper">
                                <span class="nameInscription">{{ trans('index.home.trip_quantity')}}</span>
                            </div>
                            <div class="bottomInfoWrapper">
                                <div class="value">{{ auth()->user()->client->order_success }}</div>
                            </div>
                        </li>
                        <li class="freeTrip">
                            <div class="pictureWrapper">
                                <div class="picture"></div>
                            </div>
                            <div class="inscriptionWrapper">
                                <span class="nameInscription">{{ trans('index.profile.free_seats')}}</span>
                            </div>
                            <div class="bottomInfoWrapper">
                                <div class="value">0</div>
                            </div>
                            </li>--}}
                        @if(auth()->user()->client->socialStatus)
                            <li class="discount">
                                <div class="pictureWrapper">
                                    <div class="picture"></div>
                                </div>
                                <div class="inscriptionWrapper">
                                    <span class="nameInscription">{{ trans('index.profile.your_sale')}}</span>
                                </div>
                                <div class="bottomInfoWrapper">
                                    @if (auth()->user()->client->socialStatus->is_percent > 0)
                                        <div class="value">{{ auth()->user()->client->socialStatus->percent }}%</div>
                                    @else
                                        <div class="value">{{ auth()->user()->client->socialStatus->value }} {{ trans('admin_labels.currencies_short.'.$currency) }}</div>
                                    @endif
                                </div>
                            </li>
                            <li class="customerStatus">
                                <div class="pictureWrapper">
                                    <div class="picture"></div>
                                </div>
                                <div class="inscriptionWrapper">
                                    <span class="nameInscription">{{ trans('index.profile.your_status')}}</span>

                                </div>
                                <div class="bottomInfoWrapper">
                                    <div class="value">{{ auth()->user()->client->socialStatus->name }}</div>
                                </div>
                            </li>
                        @endif
                        {{--<li class="bonusPoints">--}}
                        {{--<div class="pictureWrapper">--}}
                        {{--<div class="picture"></div>--}}
                        {{--</div>--}}
                        {{--<div class="inscriptionWrapper">--}}
                        {{--<span class="nameInscription">{{ trans('index.profile.bonus_points')}}</span>--}}
                        {{--</div>--}}
                        {{--<div class="bottomInfoWrapper">--}}
                        {{--<div class="value">150</div>--}}
                        {{--</div>--}}
                        {{--</li>--}}
                        <br style="clear: both"/>
                    </ul>
                    {{--<p class="thereIsTheBus">--}}
                    {{--<a href="minibus_location.php">{{ trans('index.profile.where_is_minibus')}}</a>--}}
                    {{--</p>--}}
                </div>
                <br style="clear: both"/>
            </div>
        </div>
    </div>
@endsection 