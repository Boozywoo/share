<form action="{{ route('admin.settings.interface_settings.change_background_image') }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-6">
            <input type="file" name="image" class="form-control">
        </div>
        <div class="col-md-6">
            <button type="submit" class="btn btn-primary">Загрузить</button>
        </div>
    </div>
</form>
<br>
<h2>
    Настройки темы
</h2>
<div class="hr-line-dashed"></div>
<div class="ajax-reply"></div>
<div class="clearfix"></div>
<div class="col-md-6">
    {!! Form::open(['route' => 'admin.settings.interface_settings.store']) !!}
        <div class="left_panelSelect">
            {{ Form::panelSelect('theme_color_admin_panel', 
                __('admin.settings.interfaceSettings.color_theme'), 
                [$selectedColorTheme]
            ) }}
        </div>
        {{ Form::panelButton() }}
    {!! Form::close() !!}
</div>

<div class="clearfix"></div>