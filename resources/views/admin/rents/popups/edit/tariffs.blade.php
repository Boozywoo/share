@if (isset($tour->rent) && $tour->rent->company_carrier_id)
    @php($companyCarrierId = $tour->rent->company_carrier_id)
@elseif (!isset($companyCarrierId))
    @php($companyCarrierId = null)
@endif

@if (isset($tour->rent) && $tour->rent->company_customer_id)
    @php($companyCustomerId = $tour->rent->company_customer_id)
@elseif (!isset($companyCustomerId))
    @php($companyCustomerId = null)
@endif

@if (isset($tour->rent) && $tour->rent->agreement_id)
    @php($agreementId = $tour->rent->agreement_id)
@elseif (!isset($agreementId))
    @php($agreementId = null)
@endif

@if (isset($tour->rent) && $tour->rent->tariff_id)
    @php($tariffId = $tour->rent->tariff_id)
@elseif (!isset($tariffId))
    @php($tariffId = null)
@endif

{{ Form::panelSelect('company_carrier_id', $companyCarriers, $companyCarrierId, ['class' => "form-control js_rent-tariff"]) }}
@if(Auth::user()->isAgent || \Auth::user()->isMediator)
    {!! Form::hidden('operator_id', Auth::id()) !!}
@else
    {{ Form::panelSelect('operator_id', $operators, isset($tour->rent) && $tour->id ? $tour->rent->operator_id : Auth::id()) }}
@endif
@if (!is_array($tariffs))
    @php($tariffs = $tariffs->toArray())
@endif
{{--{{ Form::panelSelect('company_customer_id', $companyCustomers, $companyCustomerId, ['class' => "form-control js_rent-tariff"]) }}
{{ Form::panelSelect('customer_department_id', $customerDepartments, 0, ['class' => "form-control js_rent-tariff"]) }}
{{ Form::panelSelect('customer_user_id', $customerUsers, 0, ['class' => "form-control js_rent-tariff"]) }} --}}
{{--
{{ Form::panelSelect('methodist_id', $methodists, isset($tour->rent) && $tour->id ? $tour->rent->methodist_id : null) }}
{{ Form::panelSelect('agreement_id', $agreements, $agreementId, ['class' => "form-control js_rent-tariff"]) }}
{{ Form::panelSelect('tariff_id', [0 => 'Без тарифа'] + $tariffs, $tariffId, ['class' => "form-control js_rent-tariff-change "]) }}
<div id="div-rent-price" @if ($tariffId) style="display: none" @endif>
    {!! Form::panelText('price', isset($tour) ? $tour->price : '', null, ['class' => "form-control"], true) !!}
</div>
--}}

