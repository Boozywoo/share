@if(!empty($superiors) && $superiors->count() > 0)
    {{ Form::panelSelect('superior_id',$superiors) }}
@endif
