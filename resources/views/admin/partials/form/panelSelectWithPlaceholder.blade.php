<div class="form-group">
    <div class="{{ $col ? 'col-md-12' : '' }}">
        {!! Form::select($name, $values, $selected, array_merge(['class' => "form-control select-with-placeholder",'placeholder' => trans('admin_labels.'. $name . '')], $arr)) !!}
        <p class="error-block"></p>
    </div>
</div>
<script>
    $('select.form-control.select-with-placeholder option:first').attr('disabled', true);

</script>
