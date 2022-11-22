<div class="form-group">
    {!! Form::label($name, trans('admin_labels.'. $name . ''), 
        [
            'class' => ($col ? 'col-md-4' : '' . ' control-label')
        ]
    ) !!}
    <div class="{{ $col ? 'col-md-8' : '' }}">
        {!! Form::select($name, $values, $selected, array_merge(['class' => "form-control"], $arr)) !!}
        <p class="error-block"></p>
    </div>
</div>