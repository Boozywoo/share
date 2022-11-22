    <div class="form-group">
        <h2>
            Настройки cайта
        </h2>
        <div class="hr-line-dashed"></div>
        {!! Form::model($settings, ['route' => 'admin.settings.clients_interface_settings.frame.save', 'class' => 'form-horizontal js_panel_form-ajax js_panel_form-ajax-reset'])  !!}
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-4">
                    <label for="button_color">Выберите цвет кнопки:</label>
                    <input type="color" id="button_color" name="button_color" value="{{$settings->button_color}}">
                    <br>
                    <button class="btn button_color" style="background-color: #553D67" value="#553D67"></button>
                    <button class="btn button_color" style="background-color: #2a6592" value="#2a6592"></button>
                    <button class="btn button_color" style="background-color: #659dbd" value="#659dbd"></button>
                    <button class="btn button_color" style="background-color: #557a95" value="#557a95"></button>
                    <button class="btn button_color" style="background-color: #6f2232" value="#6f2232"></button>
                    <button class="btn button_color" style="background-color: #c2b9b0" value="#c2b9b0"></button>
                    <button class="btn button_color" style="background-color: #e3afbc" value="#e3afbc"></button>
                    <button class="btn button_color" style="background-color: red" value="#FF0000"></button>
                </div>
                <div class="col-md-4">
                    <label for="background_color">Выберите цвет фона:</label>
                    <input type="color" id="background_color" name="background_color" value="{{$settings->background_color}}">
                    <br>
                    <button class="btn background_color" style="background-color: #99738E" value="#99738E"></button>
                    <button class="btn background_color" style="background-color: #7e9a9a" value="#7e9a9a"></button>
                    <button class="btn background_color" style="background-color: #DAAD86" value="#DAAD86"></button>
                    <button class="btn background_color" style="background-color: #b1a296" value="#b1a296"></button>
                    <button class="btn background_color" style="background-color: #4e4e50" value="#4e4e50"></button>
                    <button class="btn background_color" style="background-color: #afd275" value="#afd275"></button>
                    <button class="btn background_color" style="background-color: #ee4c7c" value="#ee4c7c"></button>
                    <button class="btn background_color" style="background-color: grey" value="grey"></button>
                </div>
                <div class="col-md-4">
                    <label for="font_size">Выберите размер шрифта:</label>
                    <div class="range-field w-25">
                        <input type="range" class="custom-range" min="12" max="24" step="3" id="font_size" value="{{$settings->font_size}}" name="font_size">
                        <div id="example_size" style="font-size: {{$settings->font_size}}px;">Пример</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="border_radius">Выберите радиус блоков:</label>
                    <div class="range-field w-25">
                        <input type="range" class="custom-range" min="0" max="40" step="5" id="border_radius" value="{{$settings->border_radius}}" name="border_radius">
                        <button class="btn btn-info" id="example_radius" style="border-radius: {{$settings->border_radius}}px;">Пример</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="opacity">Выберите прозрачность блока:</label>
                    <div class="range-field w-25">
                        <input type="range" class="custom-range" min="0" max="1" step="0.1" id="opacity" value="{{$settings->opacity}}" name="opacity">
                        <button class="btn btn-info" id="example_opacity" style="opacity: {{$settings->opacity}};">Пример</button>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <label for="font_color">Выберите цвет шрифта:</label>
                    <input type="color" id="font_color" name="font_color" value="{{$settings->font_color}}">
                    <br>
                    <button class="btn font_color" style="background-color: white" value="#FFFFFF"></button>
                    <button class="btn font_color" style="background-color: #000000" value="#000000"></button>
                </div>
                <div class="col-md-4">
                    <label for="font_color_authorization_buttons">Выберите цвет шрифта кнопок авторизации:</label>
                    <input type="color" id="font_color_authorization_buttons" name="font_color_authorization_buttons" value="{{$settings->font_color_authorization_buttons}}">
                    <br>
                    <button class="btn font_color_authorization_buttons" style="background-color: white" value="#FFFFFF"></button>
                    <button class="btn font_color_authorization_buttons" style="background-color: #000000" value="#000000"></button>
                </div>
            </div>
            <br>
            {{ Form::panelButton() }}
        {!! Form::close() !!}
    </div>