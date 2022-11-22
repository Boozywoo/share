@php($label = str_replace('[', '.', $name))
@php($label = str_replace(']', '', $label))

<div class="form-group">
    {!! Form::label($arr['id'] ?? $name, trans('admin_labels.'. $label), 
        ['class' => ($col ? 'col-md-4' : '' . ' control-label')]) 
    !!}
    <div class="{{ $col ? 'col-md-8' : '' }}">
        {!! Form::number($name, $val, array_merge(['class' => 'form-control '. $class], $arr)) !!}
        <p class="error-block"></p>
    </div>
</div>