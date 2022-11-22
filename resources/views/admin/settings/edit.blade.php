@extends('panel::layouts.main')

@section('title', trans('admin.settings.edit'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_panel_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i> {{trans('admin.filter.back')}}</a>
@endsection

@section('main')
    {!! Form::model($setting, ['route' => 'admin.settings.store', 'class' => 'ibox form-horizontal js_panel_form-ajax js_panel_form-ajax-reset'])  !!}
    <div class="ibox-content">
        <h2>{{ trans('admin.settings.edit') }}</h2>
        <a href="{{ route('admin.users.permissions.index') }}" class="btn btn-sm btn-warning pjax-link"><span
                class="fa fa-cog"></span> {{ trans('admin.users.permissions.title') }}</a>
        <div class="hr-line-dashed"></div>
        <div class="row">
            <div class="col-md-6">
                {{ Form::panelText('company_name') }}
                {{ Form::panelText('main_site') }}
                {{ Form::panelText('phone_one') }}
                {{ Form::panelText('phone_two') }}
                {{ Form::panelText('phone_tree') }}
                {{ Form::panelText('account_ok') }}
                {{ Form::panelText('account_vk') }}
                {{ Form::panelText('account_f') }}
                {{ Form::panelText('account_i') }}
                {{ Form::panelTextarea('text_footer') }}
                {{ Form::panelTextarea('field_popup_window') }}
                <!-- {{ Form::panelTextarea('addressInfo') }} -->
                {{ Form::panelText('index_title') }}
                <!-- {{ Form::panelText('index_description') }} -->
                {{ Form::panelText('allowed_ip') }}
                {{ Form::panelSelect('is_send_to_email', __('admin_labels.no_yes'), $setting->is_send_to_email) }}
                {{ Form::panelText('main_email') }}
                @if(Auth::user()->roles->first()->slug == 'superadmin' && $setting->is_pay_on)
                    {{ Form::panelSelect('turn_on_notification_if_order_paid', __('admin_labels.no_yes'), $setting->turn_on_notification_if_order_paid) }}
                    {{ Form::panelText('email_for_notification') }}
                @endif
                {{ Form::panelSelect('sip_registration', __('admin_labels.cip_fix_dynamic')) }}
                {{--{{ Form::panelText('copyright') }}--}}
                <div class="form-group">
                <label for="display_types_of_orders" class="col-md-4">Отображение типа оплаты в бронировании</label>
                    <div class="col-md-8">
                        <select class="form-control " id="display_types_of_orders" name="display_types_of_orders">
                            <option value="0">{{ trans('admin.buses.sel_status') }}</option>
                            @foreach(__('admin.orders.pay_types') as $key=>$type)
                                <option value="{{ $key }}" {{ ($setting->display_types_of_orders == $key)?'selected':'' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                        <p class="error-block"></p>
                    </div>
                </div>
                {{ Form::panelSelect('complete_tours', __('admin_labels.no_yes'), $setting->complete_tours) }}
                {{ Form::panelSelect('default_timezone', $timezonelist, $setting->default_timezone ? $setting->default_timezone : 'Europe/Moscow') }}
                <br>
                <div class="form-group">
                    <label for="phone_codes" class="col-md-4">Отображение на сайте телефонные коды</label>
                    <div class="col-md-8">
                        <select class="js_input-select2 col-md-12 height: 150%;" name="phone_codes[]" multiple="multiple">
                            @foreach ($phone_codes as $name => $item)
                                <option 
                                    @if (in_array($name, explode(",", $codes)))) selected
                                    @endif value="{{$name}}">
                                    {{$item}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <span data-url="{{route ('admin.settings.setToursFieldsPopup')}}" data-toggle="modal"
                        data-target="#popup_tour-edit" class="btn btn-sm btn-info">Настройка отображения полей в рейсе
                </span>
                
                <fieldset>
                    <legend>Билет</legend>
                <div class="form-group">

                    <div class="col-md-11">
                        <b>{{trans('admin_labels.anyway_download_tickets')}}</b>
                    </div>

                    <div class="col-md-1 checkbox">
                        {!! Form::hidden('anyway_download_tickets', 0) !!}
                        <input class="checkbox" @if($setting->anyway_download_tickets) checked
                            @endif name="anyway_download_tickets" type="checkbox" value="1">
                        <label for="anyway_download_tickets"></label>
                        {{--{{Form::Checkbox('anyway_download_tickets') }}--}}
                    </div>

                    <label for="ticket_language" class="col-md-4">{{ __('admin_labels.ticket_language')  }}</label>
                    <div class="col-md-8">
                        <select class="form-control" id="ticket_language" name="ticket_language">
                            @foreach($ticket_languages as $key=>$language)
                                <option value="{{ $key }}" {{ ($setting->ticket_language == $key)?'selected':'' }} >{{ $language }}</option>
                            @endforeach
                        </select>
                        <p class="error-block"></p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="ticket_type" class="col-md-4">{{ __('admin_labels.ticket_type')  }}</label>
                    <div class="col-md-8">
                        @if(Auth::user()->roles->first()->slug == 'superadmin')
                            <select class="form-control" id="ticket_type" name="ticket_type">
                                @foreach($ticket_types as $key=>$type)
                                    <option value="{{ $key }}" {{ ($setting->ticket_type == $key)?'selected':'' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                            <p class="error-block"></p>
                        @else
                            <input class="form-control" value="{{ $ticket_types[$setting->ticket_type] }}" disabled="disabled">
                        @endif
                    </div>
                </div>
                <div id="field">
                    {{ Form::panelText('ticket_cancel_phone') }}
                    {{ Form::panelTextarea('ticket_cancel_info') }}
                    {{ Form::panelTextarea('ticket_info') }}
                </div>
                </fieldset>

                <fieldset>
                    <legend>Интеграции</legend>
                    {{ Form::panelText('field_code_jivo') }}
                    {{ Form::panelText('ios_link') }}
                    {{ Form::panelText('android_link') }}
                </fieldset>
                <fieldset>
                    <legend>{{trans('admin_labels.seo_code')}}</legend>
                    {{ Form::panelTextarea('seo_head') }}
                    {{ Form::panelTextarea('seo_body') }}
                </fieldset>

            </div>
            <div class="col-md-6">
                <h3>{{trans('admin.settings.logo')}}</h3>
                {!! $setting->getImagesView($setting::IMAGE_TYPE_IMAGE) !!}
                <br><br>

                {{ Form::panelSelect('payment', trans('admin_labels.payments'), $setting->payment, ['class' => 'form-control js_route_type']) }}
                @if($setting->is_pay_on)
                    {{ Form::panelText('time_limit_pay') }}
                @endif
                {{ Form::panelText('history_days') }}
                <fieldset>
                    <legend>{{trans('admin.orders.title')}}</legend>
                    @if(Auth::user()->roles->first()->slug == 'superadmin')
                        {{ Form::panelSelect('is_system_paid',  __('admin_labels.no_yes')) }}
                    @endif
                    {{ Form::panelSelect('limit_one_order_route',  __('admin_labels.no_yes')) }}
                    {{ Form::panelText('time_hidden_tour_front') }}
                    {{ Form::panelText('limit_order_by_count') }}
                    {{ Form::panelText('limit_order_by_place') }}
                    {{ Form::panelText('limit_booking_time') }}
                    {{ Form::panelText('order_cancel_time') }}
                    {{ Form::panelText('display_orders_quantity') }}
                </fieldset>
                {{--{{ Form::panelText('discount_children') }}--}}
                <fieldset>
                    <div class="col-md-7 text-right">
                        <b>{{trans('admin_labels.show_places_left')}}</b>
                    </div>
    
                    <div class="col-md-5 checkbox small-pad">
                        {!! Form::hidden('show_places_left', 0) !!}
                        <input class="checkbox" @if($setting->show_places_left) checked
                               @endif name="show_places_left" type="checkbox" value="1">
                        <label for="show_places_left"></label>
                        {{--{{Form::Checkbox('show_places_left') }}--}}
                    </div>
                </fieldset>

                <fieldset>
                    <div class="col-md-7 text-right">
                        <b>{{trans('admin_labels.is_client_statistic')}}</b>
                    </div>
    
                    <div class="col-md-5 checkbox small-pad">
                        {!! Form::hidden('is_client_statistic', 0) !!}
                        <input class="checkbox" @if($setting->is_client_statistic) checked
                               @endif name="is_client_statistic" type="checkbox" value="1">
                        <label for="is_client_statistic"></label>
                        {{--{{Form::Checkbox('is_client_statistic') }}--}}
                    </div>
                </fieldset>

                <fieldset>
                    <div class="col-md-7 text-right">
                        <b>{{trans('admin_labels.is_change_price_agent')}}</b>
                    </div>
    
                    <div class="col-md-5 checkbox small-pad">
                       {!! Form::hidden('is_change_price_agent', 0) !!}
                        <input class="checkbox" @if($setting->is_change_price_agent) checked
                               @endif name="is_change_price_agent" type="checkbox" value="1">
                        <label for="is_change_price_agent"></label>
                        {{--{{Form::Checkbox('is_change_price_agent') }}--}}
                    </div>
                </fieldset>

                <fieldset>
                    <div class="col-md-7 text-right">
                        <b>{{trans('admin_labels.is_change_in_completed_tours')}}</b>
                    </div>
    
                    <div class="col-md-5 checkbox small-pad">
                       {!! Form::hidden('is_change_in_completed_tours', 0) !!}
                        <input class="checkbox" @if($setting->is_change_in_completed_tours) checked
                               @endif name="is_change_in_completed_tours" type="checkbox" value="1">
                        <label for="is_change_in_completed_tours"></label>
                        {{--{{Form::Checkbox('is_change_in_completed_tours') }}--}}
                    </div>
                </fieldset>

                <fieldset>
                    <div class="col-md-7 text-right">
                        <b>{{trans('admin_labels.edit_departure_all_stations')}}</b>
                    </div>
    
                    <div class="col-md-5 checkbox small-pad">
                        {!! Form::hidden('edit_departure_all_stations', 0) !!}
                        <input class="checkbox" @if($setting->edit_departure_all_stations) checked
                               @endif name="edit_departure_all_stations" type="checkbox" value="1">
                        <label for="edit_departure_all_stations"></label>
                    </div>
                </fieldset>

                <fieldset>
                    <div class="col-md-7 text-right">
                        <b >{{trans('admin_labels.is_notification_sms')}}</b>
                    </div>
                    <div class="col-md-5 checkbox small-pad">
                        {!! Form::hidden('is_notification_sms', 0) !!}
                        <input class="checkbox" @if($setting->is_notification_sms) checked
                               @endif name="is_notification_sms" type="checkbox" value="1">
                        <label for="is_notification_sms"></label>
                    </div>
                </fieldset>

                <fieldset>
                    <div class="col-md-7 text-right">
                        <b>{{trans('admin_labels.is_notification_edit_sms')}}</b>
                    </div>
                    <div class="col-md-5 checkbox small-pad">
                        {!! Form::hidden('is_notification_edit_sms', 0) !!}
                        <input class="checkbox" @if($setting->is_notification_edit_sms) checked
                               @endif name="is_notification_edit_sms" type="checkbox" value="1">
                        <label for="is_notification_edit_sms"></label>
                    </div>
                </fieldset>

                <fieldset>
                    <div class="col-md-7 text-right">
                        <b>{{trans('admin_labels.is_notification_cancel_sms')}}</b>
                    </div>
                    <div class="col-md-5 checkbox small-pad">
                        {!! Form::hidden('is_notification_cancel_sms', 0) !!}
                        <input class="checkbox" @if($setting->is_notification_cancel_sms) checked
                               @endif name="is_notification_cancel_sms" type="checkbox" value="1">
                        <label for="is_notification_cancel_sms"></label>
                    </div>
                </fieldset>

                <fieldset>
                    <div class="col-md-7 text-right">
                        <b>{{trans('admin_labels.send_sms_of_remove_order')}}</b>
                    </div>
                    <div class="col-md-5 checkbox small-pad">
                        {!! Form::hidden('send_sms_of_remove_order', 0) !!}
                        <input class="checkbox" @if($setting->send_sms_of_remove_order) checked
                               @endif name="send_sms_of_remove_order" type="checkbox" value="1">
                        <label for="send_sms_of_remove_order"></label>
                    </div>
                </fieldset>

                <fieldset>
                    <div class="col-md-7 text-right">
                        <b>{{trans('admin_labels.auto_turn_notification')}}</b>
                    </div>
                    <div class="col-md-5 checkbox small-pad">
                        {!! Form::hidden('auto_turn_notification', 0) !!}
                        <input class="checkbox" @if($setting->auto_turn_notification) checked
                               @endif name="auto_turn_notification" type="checkbox" value="1">
                        <label for="auto_turn_notification"></label>
                    </div>
                </fieldset>

                <fieldset>
                    <div class="col-md-7 text-right">
                        <b>{{trans('admin_labels.is_promotion_backend')}}</b>
                    </div>
                    <div class="col-md-5 checkbox small-pad">
                        {!! Form::hidden('is_promotion_backend', 0) !!}
                        <input class="checkbox" @if($setting->is_promotion_backend) checked
                               @endif name="is_promotion_backend" type="checkbox" value="1">
                        <label for="is_promotion_backend"></label>
                    </div>
                </fieldset>

                <fieldset>
                    <div class="col-md-7 text-right">
                        <b>{{trans('admin_labels.enable_transfer_api')}}</b>
                    </div>
                    <div class="col-md-5 checkbox small-pad">
                        {!! Form::hidden('enable_transfer_api', 0) !!}
                        <input class="checkbox" @if($config['enable_transfer_api']) checked="checked" @endif name="enable_transfer_api" type="checkbox" value="1">
                        <label for="enable_transfer_api"></label>
                    </div>
                </fieldset>

                <div style="clear: both">
                    <br />
                    {{ Form::panelTextarea('promotion_backend_text') }}
                </div>
                <div style="clear: both">
                    <br />
                    {{ Form::panelTextarea('sms_info_text') }}
                </div>
            </div>
        </div>
    </div>
    <div class="ibox-footer">
        {{ Form::panelButton() }}
    </div>
    {!! Form::close() !!}
@endsection
