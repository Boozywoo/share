@if(!empty($departments) && $departments->count() > 0)
    {{ Form::panelSelect('department_id',$departments) }}
@endif
