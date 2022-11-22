@extends('panel::layouts.main')

@section('title', trans('admin.settings.driverapp.title'))
@section('main')
{!! Form::model($d_a_setting, ['route' => 'admin.settings.driverapp.store', 'class' => 'ibox form-horizontal js_panel_form-ajax js_panel_form-ajax-reset'])  !!}
    <div class="ibox-content">
        <h2>{{ trans('admin.settings.edit') }}</h2>
        <div class="hr-line-dashed"></div>
        <div class="row">
            <div class="col-md-6">
                <div class="col-md-11 text-left">
                    <b>{{trans('admin_labels.is_see_passeger_phone')}}</b>
                </div>
                <div class="col-md-1 checkbox">
                    {!! Form::hidden('is_see_passeger_phone', 0) !!}
                    <input class="checkbox" @if($d_a_setting->is_see_passeger_phone) checked
                            @endif name="is_see_passeger_phone" type="checkbox" value="1">
                    <label for="is_see_passeger_phone"></label>
                    {{--{{Form::Checkbox('is_see_passeger_phone') }}--}}
                </div>

                <div class="col-md-11">
                    <b>{{trans('admin_labels.is_accept_cashless_payment')}}</b>
                </div>
                <div class="col-md-1 checkbox">
                    {!! Form::hidden('is_accept_cashless_payment', 0) !!}
                    <input class="checkbox" @if($d_a_setting->is_accept_cashless_payment) checked
                            @endif name="is_accept_cashless_payment" type="checkbox" value="1">
                    <label for="is_accept_cashless_payment"></label>
                    {{--{{Form::Checkbox('is_accept_cashless_payment') }}--}}
                </div>

                <div class="col-md-11">
                    <b>{{trans('admin_labels.is_change_price')}}</b>
                </div>
                <div class="col-md-1 checkbox">
                    {!! Form::hidden('is_change_price', 0) !!}
                    <input class="checkbox" @if($d_a_setting->is_change_price) checked
                            @endif name="is_change_price" type="checkbox" value="1">
                    <label for="is_change_price"></label>
                    {{--{{Form::Checkbox('is_change_price') }}--}}
                </div>

                <div class="col-md-11">
                    <b>{{trans('admin_labels.was_calling')}}</b>
                </div>
                <div class="col-md-1 checkbox">
                    {!! Form::hidden('was_calling', 0) !!}
                    <input class="checkbox" @if($d_a_setting->was_calling) checked
                            @endif name="was_calling" type="checkbox" value="1">
                    <label for="was_calling"></label>
                    {{--{{Form::Checkbox('was_calling' }}--}}
                </div>

                <div class="col-md-11">
                    <b>{{trans('admin_labels.is_cancel')}}</b>
                </div>
                <div class="col-md-1 checkbox">
                    {!! Form::hidden('is_cancel', 0) !!}
                    <input class="checkbox" @if($d_a_setting->is_cancel) checked
                            @endif name="is_cancel" type="checkbox" value="1">
                    <label for="is_cancel"></label>
                    {{--{{Form::Checkbox('is_cancel') }}--}}
                </div>

                <div class="col-md-11">
                    <b>{{trans('admin_labels.is_see_statistics')}}</b>
                </div>
                <div class="col-md-1 checkbox">
                    {!! Form::hidden('is_see_statistics', 0) !!}
                    <input class="checkbox" @if($d_a_setting->is_see_statistics) checked
                            @endif name="is_see_statistics" type="checkbox" value="1">
                    <label for="is_see_statistics"></label>
                    {{--{{Form::Checkbox('is_see_statistics') }}--}}
                </div>

                <div class="col-md-11">
                    <b>{{trans('admin_labels.is_see_pay')}}</b>
                </div>
                <div class="col-md-1 checkbox">
                    {!! Form::hidden('is_see_pay', 0) !!}
                    <input class="checkbox" @if($d_a_setting->is_see_pay) checked
                            @endif name="is_see_pay" type="checkbox" value="1">
                    <label for="is_see_pay"></label>
                    {{--{{Form::Checkbox('is_see_pay') }}--}}
                </div>

                <div class="col-md-11">
                    <b>{{trans('admin_labels.is_see_map')}}</b>
                </div>
                <div class="col-md-1 checkbox">
                    {!! Form::hidden('is_see_map', 0) !!}
                    <input class="checkbox" @if($d_a_setting->is_see_map) checked
                            @endif name="is_see_map" type="checkbox" value="1">
                    <label for="is_see_map"></label>
                    {{--{{Form::Checkbox('is_see_map') }}--}}
                </div>

                <div class="col-md-11">
                    <b>{{trans('admin_labels.is_display_cities')}}</b>
                </div>
                <div class="col-md-1 checkbox">
                    {!! Form::hidden('is_display_cities', 0) !!}
                    <input class="checkbox" @if($d_a_setting->is_display_cities) checked
                            @endif name="is_display_cities" type="checkbox" value="1">
                    <label for="is_display_cities"></label>
                    {{--{{Form::Checkbox('is_see_map') }}--}}
                </div>

                <div class="col-md-11">
                    <b>{{trans('admin_labels.is_display_streets')}}</b>
                </div>
                <div class="col-md-1 checkbox">
                    {!! Form::hidden('is_display_streets', 0) !!}
                    <input class="checkbox" @if($d_a_setting->is_display_streets) checked
                            @endif name="is_display_streets" type="checkbox" value="1">
                    <label for="is_display_streets"></label>
                    {{--{{Form::Checkbox('is_display_streets') }}--}}
                </div>

                <div class="col-md-11">
                    <b>{{trans('admin_labels.is_display_stations')}}</b>
                </div>
                <div class="col-md-1 checkbox">
                    {!! Form::hidden('is_display_stations', 0) !!}
                    <input class="checkbox" @if($d_a_setting->is_display_stations) checked
                            @endif name="is_display_stations" type="checkbox" value="1">
                    <label for="is_display_stations"></label>
                    {{--{{Form::Checkbox('is_display_stations') }}--}}
                </div>

                <div class="col-md-11">
                    <b>{{trans('admin_labels.is_display_finished_button')}}</b>
                </div>
                <div class="col-md-1 checkbox">
                    {!! Form::hidden('is_display_finished_button', 0) !!}
                    <input class="checkbox" @if($d_a_setting->is_display_finished_button) checked
                            @endif name="is_display_finished_button" type="checkbox" value="1">
                    <label for="is_display_finished_button"></label>
                    {{--{{Form::Checkbox('is_display_finished_button') }}--}}
                </div>

                <div class="col-md-11">
                    <b>{{trans('admin_labels.is_display_utc')}}</b>
                </div>
                <div class="col-md-1 checkbox">
                    {!! Form::hidden('is_display_utc', 0) !!}
                    <input class="checkbox" @if($d_a_setting->is_display_utc) checked
                            @endif name="is_display_utc" type="checkbox" value="1">
                    <label for="is_display_utc"></label>
                    {{--{{Form::Checkbox('is_display_utc') }}--}}
                </div>

                <div class="col-md-11">
                    <b>{{trans('admin_labels.is_display_last_name')}}</b>
                </div>
                <div class="col-md-1 checkbox">
                    {!! Form::hidden('is_display_last_name', 0) !!}
                    <input class="checkbox" @if($d_a_setting->is_display_last_name) checked
                            @endif name="is_display_last_name" type="checkbox" value="1">
                    <label for="is_display_last_name"></label>
                    {{--{{Form::Checkbox('is_display_last_name') }}--}}
                </div>

                <div class="col-md-11">
                    <b>{{trans('admin_labels.is_display_first_name')}}</b>
                </div>
                <div class="col-md-1 checkbox">
                    {!! Form::hidden('is_display_first_name', 0) !!}
                    <input class="checkbox" @if($d_a_setting->is_display_first_name) checked
                            @endif name="is_display_first_name" type="checkbox" value="1">
                    <label for="is_display_first_name"></label>
                    {{--{{Form::Checkbox('is_display_first_name') }}--}}
                </div>

                <div class="col-md-11">
                    <b>{{trans('admin_labels.is_display_middle_name')}}</b>
                </div>
                <div class="col-md-1 checkbox">
                    {!! Form::hidden('is_display_middle_name', 0) !!}
                    <input class="checkbox" @if($d_a_setting->is_display_middle_name) checked
                            @endif name="is_display_middle_name" type="checkbox" value="1">
                    <label for="is_display_middle_name"></label>
                    {{--{{Form::Checkbox('is_display_middle_name') }}--}}
                </div>

                <div class="col-md-11">
                    <b>{{trans('admin_labels.is_show_both_directions')}}</b>
                </div>
                <div class="col-md-1 checkbox">
                    {!! Form::hidden('is_show_both_directions', 0) !!}
                    <input class="checkbox" @if($d_a_setting->is_show_both_directions) checked
                            @endif name="is_show_both_directions" type="checkbox" value="1">
                    <label for="is_show_both_directions"></label>
                    {{--{{Form::Checkbox('is_show_both_directions') }}--}}
                </div>

                <div class="col-md-11">
                    <b>{{trans('admin_labels.is_see_passeger_passport')}}</b>
                </div>
                <div class="col-md-1 checkbox">
                    {!! Form::hidden('is_see_passeger_passport', 0) !!}
                    <input class="checkbox" @if($d_a_setting->is_see_passeger_passport) checked
                            @endif name="is_see_passeger_passport" type="checkbox" value="1">
                    <label for="is_see_passeger_passport"></label>
                    {{--{{Form::Checkbox('is_see_passeger_passport') }}--}}
                </div>
            </div>
            <div class="col-md-6">
                {{ Form::panelText('time_show_driver') }}
                {{ Form::panelText('time_click_driver') }}
                {{ Form::panelText('count_of_passport_digits') }}

                <div class="form-group">
                <label for="notification" class="col-md-4">{{ __('admin_labels.notification')  }}</label>
                    <div class="col-md-8">
                        <select class="form-control " id="notification" name="notification">
                            @foreach($notification as $key=>$type)
                                <option value="{{ $key }}" {{ ($d_a_setting->notification == $key)?'selected':'' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                        <p class="error-block"></p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="default_code" class="col-md-4">{{ __('admin_labels.phone_code')  }}</label>
                    <div class="col-md-8">
                        <select id="default_code" class="form-control" name="default_code">
                            @foreach(\App\Models\Client::CODE_PHONES as $abbr => $code)
                                <option @if ($d_a_setting->default_code == $abbr) selected @endif value="{{$abbr}}">+{{$code}}</option>
                            @endforeach
                        </select>
                        
                    </div>
                </div>
            </div>
        </div>
        
        <div class="hr-line-dashed"></div>
        {{ Form::panelButton() }}
        {!! Form::close() !!}
    </div>

    <div class="form-group ibox ibox-content">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
            </div>
            <img src="images/{{ Session::get('image') }}">
        @endif

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h2>
            Фоновое изображение
        </h2>
        <div class="hr-line-dashed"></div>
        <div class="row">
            @foreach ($images as $image) 
                <div class="col-md-4">
                    <div class="thumbnail img-wraps">
                        <span  class="closes" title="Delete" path="{{ $image->getFilename() }}">&times;</span>
                        <img src="{{ asset('assets/driver/images/' . $image->getFilename()) }}" class="img-responsive">
                    </div>
                </div>
            @endforeach
        </div>

        <form action="{{ route('admin.settings.driverapp.upload') }}" method="POST" enctype="multipart/form-data">
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
    </div>
    
@endsection
