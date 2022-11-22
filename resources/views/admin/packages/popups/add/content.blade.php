{!! Form::model($package, ['route' => 'admin.'. $entity . '.store', 'class' => 'ibox-content js_form-ajax js_form-ajax-popup js_form-ajax-table js_form-current-page']) !!} 

<button type="button" class="close" data-dismiss="modal">
    <span aria-hidden="true">&times;</span>
    <span class="sr-only">Close</span>
</button>

<h2>Добавление посылки</h2>

<div class="hr-line-dashed"></div>

<div class="row">
    <div class="col-md-6">
{{-- Carbon\Carbon::now()->format('d.m.Y') --}}
        {{ Form::panelText('time_start', Carbon\Carbon::now()->format('d.m.Y'), null, [
            'class' => 'form-control js_datepicker_without_previous',
            'data-url' => route('admin.'. $entity . '.getRoutes'),
            ]) }}
        {{ Form::panelSelect('route_id', [], null, [
            'class' => 'form-control',
            'disabled' => '',
            ]) }}
        {{ Form::panelSelect('tour_id', [], null, [
            'class' => 'form-control',
            'disabled' => '',
            ]) }}

        <div class="from_dist_package" style="display: none">
            <div class="form-group">
                <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-8 mb-2">
                    <label class="radio-inline"><input  value="1" type="radio" name="station_radio" class="" checked>Остановки</label>
                    <label class="radio-inline"><input  value="0" type="radio" name="station_radio" class="">Вручную</label>
                </div>
                </div>
            </div>

        <div class="from_dist_package_select" style="display: none">

            {{ Form::panelSelect('from_station_id', [], null, [
                'class' => 'form-control',
                'placeholder' => 'выберите остановку',
            ]) }}
            {{ Form::panelSelect('destination_station_id', [], null, [
                'class' => 'form-control',
                'placeholder' => 'выберите остановку',
            ]) }}

        </div>

            <div class="from_dist_package_field" style="display: none">
                {{ Form::panelText('package_from', null, null, [
                    'class' => 'form-control',
            ]) }}
                {{ Form::panelText('package_destination', null, null, [
                    'class' => 'form-control',
                ]) }}
            </div>

        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#from_station_id').select2({
                width: "100%",
                dropdownParent: $("#popup_package-add")
            });
            $('#destination_station_id').select2({
                width: "100%",
                dropdownParent: $("#popup_package-add")
            });
        });
    </script>
    <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    {{ Form::panelText('price', null, null, [
                        'class' => 'form-control',
                    ]) }}
                </div>
                <div class="col-md-6">
                    {{ Form::panelSelect('currency_id', $currencies, null, [
                         'class' => 'form-control'
                         ]) }}
                </div>
            </div>
            {{ Form::panelText('name_sender', null, null, [
            'class' => 'form-control',
            ]) }}
            {{ Form::panelText('phone_sender', null, null, [
            'class' => 'form-control',
            ]) }}
            {{ Form::panelText('name_receiver', null, null, [
            'class' => 'form-control',
            ]) }}
            {{ Form::panelText('phone_receiver', null, null, [
            'class' => 'form-control',
            ]) }}


            <div class="col-md-8"></div>
            <div class="col-md-4">
                <label for="send_sms" class="checkbox-inline relative-top-20">Отправка смс</label>
                    <input id="send_sms" type="checkbox" name="send_sms" value="1">
            </div>


    </div>
</div>    

<div class="hr-line-dashed"></div>

{{ Form::panelButton() }}
<span class="btn btn-sm btn-danger" data-dismiss="modal" type="button">Закрыть</span>


{!! Form::close() !!} 