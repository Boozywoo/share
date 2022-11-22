@extends('panel::layouts.main')

@section('title', $bus->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i>{{ trans('admin.filter.back') }}</a>
@endsection
@section('main')
    {!! Form::model($bus, ['route' => 'admin.'. $entity . '.store', 'class' => "ibox form-horizontal js_form-ajax js_form-ajax-reset"])  !!}
    {!! Form::hidden('id') !!}
    <div class="ibox-content">
        <h2>
            {{ $bus->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}
            @if($bus->status == \App\Models\Bus::STATUS_REPAIR)
                <span class="label label-danger">{{ trans('admin.buses.repair') }}</span>
            @endif
        </h2>
        @if(isset($bus->id))
            <span data-url="{{route ('admin.' . $entity . '.set-bus-popup', [$bus])}}" data-toggle="modal"
                  data-target="#popup_tour-edit" class="btn btn-sm btn-info">Отделы
            </span>
        @endif
        @if(isset($bus->id))
            <span data-url="{{route ('admin.' . $entity . '.set-users-popup', [$bus])}}" data-toggle="modal"
                  data-target="#popup_tour-edit" class="btn btn-sm btn-info">Пользователи
            </span>
        @endif
        <div class="hr-line-dashed"></div>
        <div class="row">
            <div class="col-md-6">
                {{ Form::panelText('name') }}
                {{ Form::panelText('name_tr') }}
                {{ Form::panelSelect('bus_type_id', $types) }}
                {{ Form::panelText('number') }}
                {{ Form::panelText('garage_number') }}
                {{ Form::panelSelect('driver_category', \App\Models\Bus::DRIVER_CATEGORIES) }}
                {{ Form::panelSelect('year', \App\Models\Bus::getYearsList()) }}
                {{ Form::panelSelect('color', $colors,$bus->color ?? '',['placeholder' => __('admin_labels.color')]) }}
                {{ Form::panelSelect('tires', __('admin.buses.tires')) }}
                {{ Form::panelText('manufacturer') }}
                {{ Form::panelText('commissioning_date', $bus->commissioning_date ? $bus->commissioning_date->format('d.m.Y'): '','js_datepicker') }}

                <div class="hr-line-dashed"></div>
                {{--                {{ Form::panelText('odometer') }}--}}

                {{--                @include('admin.buses.edit.select-company', $companies)--}}

                {{ Form::panelSelect('company_id', $companies) }}
                {{ Form::panelText('structure_department') }}
                {{ Form::panelText('owner_legally') }}
                {{ Form::panelSelect('customer_company', $customerCompanies, $bus->customer_company ?? '' ,['placeholder' => __('admin_labels.customer_company')]) }}
                {{ Form::panelSelect('customer_department', $customerDepartments,$bus->customer_department ?? '', ['placeholder' => __('admin_labels.customer_department')]) }}
                {{ Form::panelSelect('customer_director', $customerPersons,$bus->customer_director ?? '',['placeholder' => __('admin_labels.customer_director')]) }}
                {{ Form::panelSelect('fact_referral', $customerDepartments,$bus->fact_referral ?? '', ['placeholder' => __('admin_labels.fact_referral')]) }}
                <div class="hr-line-dashed"></div>

                @if($bus->id)
                    @if($bus->status != App\Models\Bus::STATUS_REPAIR)
                        {{ Form::panelSelect('status', $statuses) }}
                    @else
                        {{ Form::hidden('status', App\Models\Bus::STATUS_REPAIR) }}
                    @endif
                @endif
                {{ Form::panelSelect('location_status', __('admin.buses.location_statuses')) }}

                <div class="hr-line-dashed"></div>
                {{ Form::panelText('insurance_policy') }}
                {{ Form::panelText('insurance_day', $bus->insurance_day ? date('d.m.Y', strtotime($bus->insurance_day)) : null,'js_datepicker',[]) }}
                {{ Form::panelText('day_before_insurance', $bus->day_before_insurance) }}
                {{ Form::panelText('vehicle_passport') }}
                {{ Form::panelText('vehicle_passport_date', $bus->vehicle_passport_date ? $bus->vehicle_passport_date->format('d.m.Y') : '','js_datepicker') }}
                {{ Form::panelText('registration_certificate') }}
                {{ Form::panelText('registration_certificate_date', $bus->registration_certificate_date ? $bus->registration_certificate_date->format('d.m.Y'): '','js_datepicker') }}
                {{ Form::panelNumber('diagnostic_card_number') }}
                {{ Form::panelText('diagnostic_card_date', $bus->diagnostic_card_date ? $bus->diagnostic_card_date->format('d.m.Y'): '','js_datepicker') }}

                <div class="hr-line-dashed"></div>
                {{ Form::panelNumber('inventory_number') }}
                {{ Form::panelNumber('operating_mileage') }}
                {{ Form::panelNumber('weight_allowed') }}
                {{ Form::panelNumber('weight_empty') }}
                {{ Form::panelNumber('balance_price') }}
                {{ Form::panelNumber('residual_price') }}
                {{ Form::panelNumber('transport_tax') }}
                {{ Form::panelNumber('property_tax') }}

                <div class="hr-line-dashed"></div>

                {{ Form::panelText('revision_day', $bus->revision_day ? date('d.m.Y', strtotime($bus->revision_day)) : null,'js_datepicker') }}
                {{ Form::panelText('day_before_revision', $bus->day_before_revision) }}
                {{ Form::panelText('universal_day', $bus->universal_day ? date('Y-m-d H:i:s', strtotime($bus->universal_day)) : null,'timepicker', ['autocomplete="off"']) }}
                {{ Form::panelText('universal_field') }}

            </div>
            <div class="col-md-6">
                {{ Form::panelText('vin') }}
                {{ Form::panelText('chassis_number') }}
                {{ Form::panelText('body_number') }}

                {{ Form::panelText('engine_model') }}
                {{ Form::panelText('engine_number') }}
                {{ Form::panelText('engine_power') }}
                <div class="hr-line-dashed"></div>
                {{ Form::panelSelect('repair_card_type_id', $repairCardTemplates) }}
                {{ Form::panelSelect('diagnostic_card_template_id', $diagnosticCardTemplates) }}
                <div class="hr-line-dashed"></div>

                {{ Form::panelText('places', null, '', ['class' => "form-control js_template-input js_ajax-change", 'data-url' => route('admin.buses.selectTemplates'), 'data-wrapper' => 'js_templates-content']) }}

                <div class="js_templates-content">
                    @include('admin.'. $entity . '.edit.select-templates', ['template' => $bus->template])
                </div>
                {{ Form::panelSelect('is_rent', [trans('admin.home.no'), trans('admin.home.yes')]) }}
                {{ Form::panelNumber('max_rent_time') }}
                <div class="form-group">
                    <label for="cities" class="col-md-4">{{trans('admin.buses.rent_cities')}}</label>
                    <div class="col-md-8">
                        <select class="js_input-select2 col-md-12" name="cities[]" multiple="multiple">
                            @foreach ($cities as $id => $item)
                                <option @if (in_array($id, $busCities)) selected @endif value="{{$id}}" class="">
                                    {{$item}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('password', trans('admin.auth.pass'), ['class' => "col-md-4 control-label" ]) !!}
                    <div class="col-md-8">
                        <input type="text" style="opacity: 0; position: fixed; height: 0; width: 0;">
                        {!! Form::password('password', ['class' => "form-control", 'autocomplete' => 'new-password']) !!}
                        <p class="error-block"></p>
                    </div>
                </div>
                <div class="">
                    @foreach($amenities as $id=>$amenity )
                        <label for="amenities[{{$id}}]" class="col-md-7 text-right">
                            @if(!empty($amenity->mainImage))
                                <img src="/{{ $amenity->getImagePath('image', 'original',  $amenity->mainImage->path) }}" alt="" width="24" height="24">
                            @else
                                {{$amenity->name}}
                            @endif
                        </label>
                        <div class="col-md-5">
                            {!! Form::hidden('amenities['.$amenity->id.']', 0) !!}
                            {{ Form::onOffCheckbox('amenities['.$amenity->id.']', in_array($amenity->id, $busAmenities) ? 1: 0) }}
                        </div>
                    @endforeach
                </div>
                <div class="col-md-12">
                    <div class="hr-line-dashed"></div>
                    {!! $bus->getImagesView($bus::IMAGE_TYPE_IMAGE) !!}
                    <div class="hr-line-dashed"></div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <canvas id="variable_odometer" style="background-color: white"></canvas>
                </div>
                <div class="col-md-6">
                    <canvas id="variable_fuel" style="background-color: white"></canvas>
                </div>
            </div>
            <div class="col-md-12">
                <div class="hr-line-dashed"></div>
                <h3 class="edit">{{ trans('admin_labels.garage') }}</h3>
                <div>
                    <input type="hidden" name="garage_latitude" value="{{ $bus->garage_latitude }}">
                    <input type="hidden" name="garage_longitude" value="{{ $bus->garage_longitude }}">
                    <div id="map" style="width: 100%; height: 400px;"></div>
                </div>
                <p class="error-block"></p>
            </div>
        </div>
    </div>
    <div class="ibox-footer">
        {{ Form::panelButton() }}
    </div>
    {!! Form::close() !!}

    <script>
        $(document).ready(function () {
            $("#customer_director option:first").attr('disabled', 'disabled');
            $("#customer_director").select2();

            $("#customer_company option:first").attr('disabled', 'disabled');
            $("#customer_company").select2();

            $("#customer_department option:first").attr('disabled', 'disabled');
            $("#customer_department").select2();

            $("#fact_referral option:first").attr('disabled', 'disabled');
            $("#fact_referral").select2();

            $("#color option:first").attr('disabled', 'disabled');
            $("#color").select2();
        });
    </script>
    @if(!empty($variables))
        {{--        {{dd($variables['odometer'])}}--}}
        <script>
            $(document).ready(function () {

                const odometer = new Chart('variable_odometer', {
                        type: 'line',
                        data: {
                            labels: {!!  json_encode($variables['odometer']->keys()->all()) !!},
                            datasets: [{
                                label: "{{__('admin_labels.odometer')}}",
                                backgroundColor: 'rgb(0,48,246)',
                                borderColor: 'rgb(0,48,246)',
                                data: {!!  json_encode($variables['odometer']->values()) !!},
                            }]
                        },
                        options: {}
                    }
                );
                const fuel = new Chart(
                                    'variable_fuel', {
                                        type: 'line',
                                        data: {
                                            labels: {!!  json_encode($variables['fuel']->keys()->all()) !!},
                            datasets: [{
                                label: "{{__('admin_labels.fuel')}}",
                                backgroundColor: 'rgb(0,48,246)',
                                borderColor: 'rgb(0,48,246)',
                                data: {!!  json_encode($variables['fuel']->values()) !!},
                            }]
                                        },
                                        options: {}
                                    }
                                );

            });

        </script>
    @endif
@endsection

{{--@push('scripts')--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.0/chart.min.js" async></script>--}}
{{--@endpush--}}
