@if(!empty($positions) && $positions->count() > 0)
    {{ Form::panelSelect('position_id',$positions) }}
@endif
