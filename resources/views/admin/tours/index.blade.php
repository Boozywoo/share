@extends('panel::layouts.main')
@php($isAgent = Auth::user()->isAgent)
@php($isMediator = Auth::user()->isMediator)

@section('title', trans('admin.'. $entity . '.title'))

@section('actions')
    @if(!$isAgent && !$isMediator)

        {{-- <span style="padding-right: 20px">
            <input class="js_only_visible" id="only_visible_ch" type="checkbox" value="1">
            <label for="only_visible_ch">Видимые</label>
        </span> --}}

        <span style="padding-right: 20px">
            <input class="js_all_dates" id="all_dates_ch" type="checkbox" value="1">
            <label for="all_dates_ch">Все даты</label>
        </span>

        <span class="btn btn-sm btn-primary js_mass_change_price" data-title="<h2>Изменить цену всех рейсов</h2>">
            Изменить цену рейсов
        </span>
    @endif
        <span data-url="{{ route('admin.'. $entity . '.showPopup') }}" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#popup_tour-edit">
            <span class="fa fa-plus"></span>
            {{ trans('admin.'. $entity . '.add_button') }}
        </span>
        <span data-url="{{ route('admin.packages.showPopup') }}" class="block-left btn btn-sm btn-primary" data-toggle="modal" data-target="#popup_package-add">
            <span class="fa fa-plus"></span>
            {{ trans('admin.packages.add_button') }}
        </span>
    <span data-url="{{ route('admin.packages.indexPackagesByDate') }}"
          class="block-left btn btn-sm btn-primary"
          id="index-packages">
            <span class="packages-button packages-button-active"><span class="fa fa-shopping-bag"></span> Посылки</span>
            <span class="tours-button" style="display: none"><span class="fa fa-cab"></span> Рейсы</span>
        </span>
@endsection

@section('main')
    <div class="row">
        <div class="col-md-3">
            <div class="ibox">
                <div class="ibox-content">
                    @include('admin.tours.index.filter')

                    @if ($client!=null)
                        <hr>
                        <h2>{{trans('admin_labels.client_id')}}</h2>
                        {!! Form::hidden('client_id', $client ? $client->id : '') !!}
                        {!! Form::panelText('last_name', $client ? $client->last_name : '', null, ['class' => 'form-control js_orders-client-last_name'], false) !!}
                        {!! Form::panelText('first_name', $client ? $client->first_name : '', null, ['class' => 'form-control js_orders-client-first_name'], false) !!}
                        {!! Form::panelText('middle_name', $client ? $client->middle_name : '', null, ['class' => 'form-control js_orders-client-middle_name'], false) !!}
                        {!! Form::panelText('passport', $client ? $client->passport : '', null, ['class' => 'form-control js_orders-client-passport'], false) !!}

                        <div class="form-group">
                            <label class=" control-label">{{trans('admin_labels.phone')}}</label>
                            <div class="">
                                <input class="form-control " type="text" value="{{$client->phone}}">
                                <p class="error-block"></p>
                            </div>
                        </div>

                        {!! Form::panelText('timezone', $client ? $client->timezone : '', null, ['class' => 'form-control js_orders-client-timezone'], false) !!}
                        <div class="div_social_status" data-url="{{route('admin.clients.get_social_status')}}">
                            {!! Form::panelSelect('status_id', App\Models\Status::SelectStatuses(), $client ? $client->status_id : 0,
                             ['class' => 'form-control  js_orders-client-status_is',
                             'data-url' =>route('admin.clients.change_status'),], false) !!}

                            {{ Form::panelText('date_social', (isset($client)) && $client->date_social ? $client->date_social->format('d.m.Y') : '', 'js_datepicker js_orders-client-date_social',
                            [
                            'class' => 'form-control js_datepicker js_orders-client-date_social',
                                'data-url' =>route('admin.clients.change_date_social'),
                                ],false) }}
                        </div>

                    @endif

                </div>

            </div>


        </div>
        <div class="col-md-9">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="js_table-wrapper">
                        @include('admin.tours.index.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/admin/js/bootbox.min.js') }}"></script>
@endpush
