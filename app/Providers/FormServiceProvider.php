<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Form;

class FormServiceProvider extends ServiceProvider
{

    public function boot()
    {

        Form::macro('labelHtml', function ($name, $value = null, $options = array()) {
            return htmlspecialchars_decode(Form::label($name, $value, $options));
        });

        //Admin
        Form::component('panelCheckbox', 'admin.partials.form.panelCheckbox', ['name', 'checked', 'id']);
        Form::component('onOffCheckbox', 'admin.partials.form.onOffCheckbox', ['name', 'checked', 'id']);
        Form::component('panelText', 'admin.partials.form.panelText', ['name', 'val', 'class', 'arr' => [], 'col' => true, 'autocomplete' => 'off']);
        Form::component('panelNumber', 'admin.partials.form.panelNumber', ['name', 'val', 'class', 'arr' => [], 'col' => true, 'autocomplete' => 'off']);
        Form::component('panelRange', 'admin.partials.form.panelRange', ['name', 'min', 'max', 'val', 'class', 'arr' => [], 'col' => true]);
        Form::component('panelTextarea', 'admin.partials.form.panelTextarea', ['name', 'redactor' => false, 'type' => null, 'label' => null, 'arr' => [],'value' => null]);
        Form::component('panelRadio', 'admin.partials.form.panelRadio', ['name', 'selected' => false]);
        Form::component('panelRadios', 'admin.partials.form.panelRadios', ['name', 'values', 'def']);
        Form::component('panelSelect', 'admin.partials.form.panelSelect', ['name', 'values', 'selected', 'arr' => [], 'col' => true]);
        Form::component('panelSelect2', 'admin.partials.form.panelSelect2', ['name', 'values', 'selected','id', 'placeholder' => '','multiple' => true]);
        Form::component('panelSelectWithPlaceholder', 'admin.partials.form.panelSelectWithPlaceholder', ['name', 'values', 'selected', 'arr' => [], 'col' => true]);
        Form::component('panelButton', 'admin.partials.form.panelButton', ['text' => '']);
        Form::component('goToUrl', 'admin.partials.form.panelGoToUrl', ['url']);
        Form::component('panelTextSelect', 'admin.partials.form.panelTextSelect', ['data'=>['text'=>['name', 'value'], 'select'=>['name', 'values', 'selected']]]);

    }

    public function register()
    {

    }
}
