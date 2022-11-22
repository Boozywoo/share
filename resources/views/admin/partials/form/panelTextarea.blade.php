@if(!$type)
    <div class="form-group">
        {!! Form::label($name, $label ?: trans('admin_labels.'. $name),
            ['class' => 'col-md-4 control-label']
        ) !!}
        <div class="col-md-8">
            {!! Form::textarea($name, $value,array_merge( ['class' => 'form-control '. ($redactor ? 'js_panel_input-redactor' : '')], $arr)) !!}
            <p class="error-block"></p>
        </div>
    </div>
@else
    @if($redactor)
        <div class="hr-line-dashed"></div>
        <h3 class="edit">{{ trans('admin_labels.'. $name) }}</h3>
    @endif
    {{ Form::textarea($name, $value, array_merge(['class' => 'form-control '. ($redactor ? 'js_panel_input-froala' : ''), 'placeholder' => trans('admin_labels.'. $name)], $arr)) }}
@endif
