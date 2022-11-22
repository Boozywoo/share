<div class="onoffswitch">
    {!! Form::checkbox($name, 1, $checked, ['class' => 'onoffswitch-checkbox', 'id' => $name. '-'. $id])  !!}
    {!! Form::labelHtml($name .'-'. $id, '<span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span>', 
        ['class' => 'onoffswitch-label']
    ) !!}
</div>