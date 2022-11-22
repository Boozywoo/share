@php
    if(!function_exists('actionButtons')){
            function actionButtons($id, $activeField){
            return "<span onclick='saveField(event,`".$id."`,`$activeField`)'><i class='fa fa-check'></i></span>
                        <span onclick='hideField(`".$id."`,`$activeField`)'><i class='fa fa-close'></i></span>";}
    }
    $editMode = false;
@endphp
<td>{{$bus->get('id')}}</td>
@foreach($selectedFields as $field)
    <td id="field_{{$bus->get('id')}}_{{$field}}">
{{--        ondblclick="showField(this,'{{$field}}', '{{$bus->get('id')}}')">--}}


        @if($field == 'status')
            <div class="field-value">
                {!!  __('pretty.statuses.'.$bus->get($field))!!}
            </div>
            @if($editMode)

                <div class="field-input">
                    {!!   actionButtons($bus->get('id'), $field) !!}
                    {!! Form::panelSelect2($bus->get('id').'['.$field.']', __("admin.buses.statuses"), $bus->get($field), $bus->get('id').'_'.$field, __("admin_labels.$field"), false) !!}
                </div>
            @endif
        @elseif($field == 'location_status')
            <div class="field-value">
                {{ __('admin.buses.location_statuses.'.$bus->get($field)) }}
            </div>
            @if($editMode)
                <div class="field-input">
                    {!!   actionButtons($bus->get('id'), $field) !!}
                    {!! Form::panelSelect2($bus->get('id').'['.$field.']', __("admin.buses.location_statuses"), $bus->get($field), $bus->get('id').'_'.$field, __("admin_labels.$field"), false) !!}
                </div>
            @endif
        @elseif($field == 'color')
            <div class="field-value">
                {{$colors->get($bus->get($field))}}
            </div>
            @if($editMode)
                <div class="field-input">
                    {!!   actionButtons($bus->get('id'), $field) !!}
                    {!! Form::panelSelect2($bus->get('id').'['.$field.']', $colors, $bus->get($field), $bus->get('id').'_'.$field, __("admin_labels.$field"), false) !!}
                </div>
            @endif

        @elseif($field == 'departments')
            <div class="field-value">
                {{ $bus->get('departments') ? implode(', ', collect($bus->get('departments'))->pluck('name')->toArray()) : '' }}
            </div>
            @if($editMode)
                <div class="field-input">
                    {!!   actionButtons($bus->get('id'), $field) !!}
                    {!! Form::panelSelect2($bus->get('id').'['.$field.']', $departments, array_map(function ($val) {return $val['id'];},$bus->get($field) ?? []), $bus->get('id').'_'.$field, __("admin_labels.$field"), true) !!}
                </div>
            @endif
        @elseif($field == 'bus_drivers')
            <div class="field-value" style="min-width: 250px;">
                {!!   $bus->get($field) ? implode(', <br> ', collect($bus->get($field))->pluck('name')->toArray()) : '' !!}
            </div>
            @if($editMode)
                <div class="field-input" style="min-width: 250px;">
                    {!!   actionButtons($bus->get('id'), $field) !!}
                    {!! Form::panelSelect2($bus->get('id').'['.$field.']', $busDrivers, array_map(function ($val) {return $val['id'];},$bus->get($field) ?? []), $bus->get('id').'_'.$field, __("admin_labels.$field"), true) !!}
                </div>
            @endif
        @elseif($field == 'type' || $field == 'company')
            <div class="field-value">
                {{ $bus->get($field)['name'] ?? ''}}
            </div>
            @if($editMode)
                <div class="field-input">
                    {!!   actionButtons($bus->get('id'), $field) !!}
                    {!! Form::panelSelect2($bus->get('id').'['.$field.']', $field == 'company' ? $companies : $types, $bus->get($field)['id'] ?? '', $bus->get('id').'_'.$field, __("admin_labels.$field"), false) !!}
                </div>
            @endif
        @elseif($field == 'customer_director')
            <div class="field-value">
                {{ $customerPersonalities->get($bus->get($field))  }}
            </div>
            @if($editMode)
                <div class="field-input">
                    {!!   actionButtons($bus->get('id'), $field) !!}
                    {!! Form::panelSelect2($bus->get('id').'['.$field.']', $customerPersonalities, $bus->get($field), $bus->get('id').'_'.$field, __("admin_labels.$field"), false) !!}
                </div>
            @endif
        @elseif($field == 'customer_company')
            <div class="field-value">
                {{ $customerCompanies->get($bus->get($field))  }}
            </div>
            @if($editMode)
                <div class="field-input">
                    {!!   actionButtons($bus->get('id'), $field) !!}
                    {!! Form::panelSelect2($bus->get('id').'['.$field.']', $customerCompanies, $bus->get($field), $bus->get('id').'_'.$field, __("admin_labels.$field"), false) !!}
                </div>
            @endif
        @elseif($field == 'customer_department')
            <div class="field-value">
                {{ $customerDepartments->get($bus->get($field))  }}
            </div>
            @if($editMode)
                <div class="field-input">
                    {!!   actionButtons($bus->get('id'), $field) !!}
                    {!! Form::panelSelect2($bus->get('id').'['.$field.']', $customerDepartments, $bus->get($field), $bus->get('id').'_'.$field, __("admin_labels.$field"), false) !!}
                </div>
            @endif
        @elseif($field == 'fact_referral')
            <div class="field-value">
                {{ $customerDepartments->get($bus->get($field))  }}
            </div>
            @if($editMode)
                <div class="field-input">
                    {!!   actionButtons($bus->get('id'), $field) !!}
                    {!! Form::panelSelect2($bus->get('id').'['.$field.']', $customerDepartments, $bus->get($field), $bus->get('id').'_'.$field, __("admin_labels.$field"), false) !!}
                </div>
            @endif
        @elseif($field == 'tires')
            <div class="field-value">
                {{ $bus->has($field) && !empty($bus->get($field))  ? __('admin.buses.tires.'.$bus->get($field)) : ''}}
            </div>
            @if($editMode)
                <div class="field-input">
                    {!!   actionButtons($bus->get('id'), $field) !!}
                    {!! Form::panelSelect2($bus->get('id').'['.$field.']', __('admin.buses.tires'), $bus->get($field), $bus->get('id').'_'.$field, __("admin_labels.$field"), false) !!}
                </div>
            @endif
        @elseif($field == 'driver_category')
            <div class="field-value">
                {{ $bus->has($field) ? $bus->get($field) : ''}}
            </div>
            @if($editMode)
                <div class="field-input">
                    {!!   actionButtons($bus->get('id'), $field) !!}
                    {!! Form::panelSelect2($bus->get('id').'['.$field.']', array_combine(\App\Models\Bus::DRIVER_CATEGORIES,\App\Models\Bus::DRIVER_CATEGORIES), $bus->get($field), $bus->get('id').'_'.$field, __("admin_labels.$field"), false) !!}
                </div>
            @endif
        @elseif(in_array($field,$fields['dates']))
            <div class="field-value">
                {{ \Carbon\Carbon::parse($bus->get($field))->format('d.m.Y') }}

            </div>
            @if($editMode)
                <div class="field-input">
                    {!!   actionButtons($bus->get('id'), $field) !!}
                    {!! Form::text($bus->get('id').'['.$field.']', \Carbon\Carbon::parse($bus->get($field))->format('d.m.Y'), ['class' => 'form-control js_datepicker']) !!}
                </div>
            @endif
        @else
            <div class="field-value">
                {{$bus->get($field)}}
            </div>
            @if($editMode)
                <div class="field-input">
                    {!!   actionButtons($bus->get('id'), $field) !!}
                    {!! Form::text($bus->get('id').'['.$field.']', $bus->get($field), ['class' => 'form-control ']) !!}
                </div>
            @endif
        @endif
    </td>

@endforeach
@if(empty($modelBus))
    <script>
        $('.js_datepicker').datepicker({
            format: 'dd.mm.yyyy',
            autoclose: true,
            todayHighlight: true,
            language: 'ru',
            dateFormat: 'dd.mm.yyyy',
            changeMonth: true,
            changeYear: true,
            startDate: '-1200m'
        })

    </script>
@endif
