<div class="form-group">
    {{ Form::label($name .'-yes', trans('admin_labels.'. $name), 
        ['class' => 'control-label col-md-4']
    ) }}
    <div class="col-md-8">
        <div class="radio radio-warning radio-inline">
            {{ Form::radio($name, 1, $selected, ['id' => $name .'-yes']) }}
            <label for="{{ $name }}-yes">{{trans('admin.home.yes')}}</label>
        </div>
        <div class="radio radio-danger radio-inline">
            {{ Form::radio($name, 0, !$selected ? true : false, ['id' => $name.'-no']) }}
            <label for="{{ $name }}-no">{{trans('admin.home.no')}}</label>
        </div>
    </div>
</div>