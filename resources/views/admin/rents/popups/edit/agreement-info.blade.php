<h3><b>{{trans('admin_labels.agreement_id')}} {{$agreement->number}}</b></h3>
<br>
<h3>{{trans('admin_labels.date_finish')}}</h3>
<h3>{{$agreement->date_end->format('Y-m-d')}}</h3>
<br>
<h3>{{trans('admin_labels.limit')}} @price($agreement->limit)</h3>
<h3>{{trans('admin_labels.amountRents')}} @price($agreement->AmountRents)</h3>
@php($balance = $agreement->limit - $agreement->AmountRents)
<h3>{{trans('admin_labels.balance')}} @price($balance)</h3>