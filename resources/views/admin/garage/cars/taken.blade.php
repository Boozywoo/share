@extends('panel::layouts.main')
@php
    $type = $userTakenBus->status == \App\Models\UserTakenBus::STATUS_TAKEN ? 'take' : 'put';
@endphp
@section('title', $type == 'take' ? trans('admin.'. $entity . '.take_car') : trans('admin.'. $entity . '.put_car'))

@section('main')
    <div class="ibox">
        <div class="ibox-content">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6 text-center">
                    <h3>{{trans('admin.'. $entity . '.car')}}</h3>
                    <div class="rows">{{$userTakenBus->bus ? $userTakenBus->bus->name : ''}}</div>
                    <h3>{{ $type == 'take' ? trans('admin.'. $entity . '.taken_by_user') : trans('admin.'. $entity . '.put_by_user') }}

                        @if(!$userTakenBus->condition){{trans('admin.'. $entity . '.with_malfunction')}}@endif</h3>
                    <div class="rows">{{$userTakenBus->imageable->name}}</div>
                    <br>
                    <div>
                        <div style="display: inline-block">{{__('admin.buses.review_card_template')}} :</div>
                        <div style="display: inline-block; border: 1px solid white; width: fit-content; padding: 7px; border-radius: 13px;
                                background: {{$userTakenBus->condition ? '#1ab394' : '#f8ac59'}}; font-size: 14px; font-weight: 600;">
                            {{$userTakenBus->diagnostic_cards->last() && $userTakenBus->diagnostic_cards->last()->template ? $userTakenBus->diagnostic_cards->last()->template->name : ''}}
                        </div>
                    </div>
                    <br>
                    <h3>{{trans('admin.drivers.time') . ' ' . Carbon\Carbon::now()->format('H:i')}}</h3>
                    <br>
                    @if($type == 'take')
                        <h3> {{trans('admin.'. $entity . '.max_time') . ' ' . ($userTakenBus->bus->max_rent_time == 0 ? 24 : $userTakenBus->bus->max_rent_time)  . trans('admin.'. $entity . '.hours')}}</h3>
                    @else
                        @php
                            $diffInMinutes = \Carbon\Carbon::parse($userTakenBus->started_at)->diffInMinutes($userTakenBus->ended_at);
                            $hours = intdiv($diffInMinutes, 60);
                            $minutes = $diffInMinutes % 60;
                            $lastBusVariable = $userTakenBus->diagnostic_card_type_put() ? $userTakenBus->diagnostic_card_type_put()->bus_variable : null;
                            $currentBusVariable = $userTakenBus->diagnostic_card_type_take() ? $userTakenBus->diagnostic_card_type_take()->bus_variable: null;
                        @endphp
                        {{--                        {{dd($lastBusVariable, $currentBusVariable)}}--}}
                        <h3>{{__('admin.'.$entity.'.time_has_been_use').' '.($hours > 0 ? $hours.'ч. ': '').$minutes.'м.'}}</h3>
                        @if(!empty($currentBusVariable) && !empty($lastBusVariable))

                            @if($currentBusVariable->fuel && $lastBusVariable->fuel)
                                <h3>{{"Потрачено топлива: " . ($currentBusVariable->fuel - $lastBusVariable->fuel)}}</h3>
                            @endif
                            @if($lastBusVariable->odometer && $currentBusVariable->odometer)
                                <h3>{{"Проехали километров: " . ($lastBusVariable->odometer - $currentBusVariable->odometer)}}</h3>
                            @endif

                        @endif
                    @endif
                    <br>
                    <a href="{{route('admin.'.$entity.'.index', ['department_id='])}}"
                       class="btn btn-block btn-primary">Ok</a>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </div>
@endsection
<style>
    .rows {
        padding-left: 20px;
    }
</style>