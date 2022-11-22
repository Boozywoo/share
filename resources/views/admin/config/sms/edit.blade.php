@extends('panel::layouts.main')

@section('title', trans('admin.settings.smsconfig.title'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_panel_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i> {{trans('admin.filter.back')}}</a>
@endsection

@section('main')
    {!! Form::open(['route' => 'admin.settings.smsconfig.store', 'class' => "ibox form-horizontal js_panel_form-ajax js_panel_form-ajax-reset", 'onsubmit' => 'javascript:$("#js_provider-add-new").trigger("click");'])  !!}
    <div class="ibox-content">
        <h2>{{ trans('admin.settings.smsconfig.edit') }}</h2>
        <div class="hr-line-dashed"></div>
        <ul class="nav nav-tabs" id="js_sms-providers" role="tablist">
            @if ($providers->count())
                @foreach ($providers as $key=>$provider)
                    <li class="nav-item {{ ($key==0)?'active':'' }}">
                        <a class="nav-link" id="provider{{ $provider->id }}"
                           data-toggle="tab" href="#provider-tab{{ $provider->id }}" role="tab"
                           aria-controls="provider{{ $provider->id }}" aria-selected="true">{{ $provider->name }}</a>
                    </li>
                @endforeach
            @endif
            <li class="nav-item" id="add-tab">
                <a class="nav-link  text-success " data-toggle="tab" href="#js_provider-new"
                   role="tab">+ {{__('admin_labels.provider_btn_add')}}</a>
            </li>
        </ul>
        <br>
        <div class="row">

            <div class="tab-content col-md-6">

                @if ($providers->count())
                    @foreach ($providers as $key=>$provider)
                        <div class="tab-pane fade {{ ($key==0)?'active in':'' }}" id="provider-tab{{ $provider->id }}"
                             role="tabpanel"
                             aria-labelledby="provider-tab{{ $provider->id }}">
                            <div class="form-group">
                                <label for="provider_name{{ $provider->id }}"
                                       class="col-md-4">{{ trans('admin_labels.provider_name') }}</label>
                                <div class="col-md-8">
                                    <input class="form-control" required name="provider_name[{{ $provider->id }}]"
                                           type="text"
                                           id="provider_name{{ $provider->id }}" value="{{ $provider->name }}">
                                    <p class="error-block"></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="provider_number_prefix{{ $provider->id }}"
                                       class="col-md-4">{{ trans('admin_labels.provider_number_prefix') }}</label>
                                <div class="col-md-8">
                                    <input class="form-control" required
                                           name="provider_number_prefix[{{ $provider->id }}]"
                                           type="text"
                                           id="provider_number_prefix{{ $provider->id }}"
                                           value="{{ $provider->number_prefix }}">
                                    <p class="error-block"></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="provider_number_prefix{{ $provider->id }}"
                                       class="col-md-4">{{ trans('admin_labels.sms_send') }}</label>
                                <div class="col-md-8">
                                    <input class="form-control " required name="provider_sms_send[{{ $provider->id }}]"
                                           type="text"
                                           id="provider_sms_send{{ $provider->id }}" value="{{$provider->sms_send}}">
                                    <p class="error-block"></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="provider_is_latin{{ $provider->id }}"
                                       class="col-md-4">{{ trans('admin_labels.is_latin') }}</label>
                                <div class="col-md-8">
                                    <input class="form-control" required name="provider_is_latin[{{ $provider->id }}]"
                                           type="text"
                                           id="provider_is_latin{{ $provider->id }}" value="{{$provider->is_latin}}">
                                    <p class="error-block"></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="provider_sms_sender{{ $provider->id }}"
                                       class="col-md-4">{{ trans('admin_labels.sms_sender') }}</label>
                                <div class="col-md-8">
                                    <input class="form-control" required name="provider_sms_sender[{{ $provider->id }}]"
                                           type="text"
                                           id="provider_sms_sender{{ $provider->id }}"
                                           value="{{ $provider->sms_sender}}">
                                    <p class="error-block"></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="provider_sms_api_login{{ $provider->id }}"
                                       class="col-md-4">{{ trans('admin_labels.sms_api_login') }}</label>
                                <div class="col-md-8">
                                    <input class="form-control " required
                                           name="provider_sms_api_login[{{ $provider->id }}]"
                                           type="text"
                                           id="provider_sms_api_login{{ $provider->id }}"
                                           value="{{ $provider->sms_api_login}}">
                                    <p class="error-block"></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="provider_sms_api_password{{ $provider->id }}"
                                       class="col-md-4">{{ trans('admin_labels.sms_api_password') }}</label>
                                <div class="col-md-8">
                                    <input class="form-control " required
                                           name="provider_sms_api_password[{{ $provider->id }}]"
                                           type="text"
                                           id="provider_sms_api_password{{ $provider->id }}"
                                           value="{{ $provider->sms_api_password}}">
                                    <p class="error-block"></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="provider_active{{ $provider->active }}"
                                       class="col-md-4">{{ trans('admin_labels.provider_active') }}</label>
                                <div class="col-md-8">
                                    <input
                                            name="provider_active[{{ $provider->id }}]"
                                           type="checkbox"
                                           id="provider_active{{ $provider->id }}"
                                           {{ ($provider->active)?'checked':'' }} value="1">
                                    <p class="error-block"></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="provider_default{ $provider->id }}"
                                       class="col-md-4">{{ trans('admin_labels.provider_default') }}</label>
                                <div class="col-md-8">
                                    <input name="provider_default"
                                           class="js-default "
                                           type="radio"
                                           id="provider_default{{ $provider->id }}"
                                           {{ ($provider->default)?'checked':'' }}  value="{{ $provider->id }}">
                                    <p class="error-block"></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-sm btn-danger js-btn-remove"
                                            data-id="{{ $provider->id }}"><i
                                                class="fa fa-dot-circle-o"></i> {{ __('admin_labels.provider_btn_remove') }}
                                    </button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <h2 class="text-shadow">{{ __('admin.settings.smsconfig.smsfields') }}</h2>
                                    <div class="hr-line-dashed"></div>
                                </div>
                            </div>
                            <table class="table table-responsive table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th class="text-center">{{ __('admin.settings.smsconfig.table.number')}}</th>
                                    <th class="text-center">{{ __('admin.settings.smsconfig.table.name')}}</th>
                                    <th class="text-center">{{ __('admin.settings.smsconfig.table.show')}}</th>
                                </tr>
                                </thead>
                                <tbody>


                                @foreach($smsconfig as $key=>$s)

                                    @if($s['id_smsprovider']==$provider->id)

                                    <tr>
                                        <td class="text-center">{{ $s['orderby'] }}.</td>
                                        <td class="text-center">{{ __('admin.settings.smsconfig.fields.'.$s['key'])}}</td>
                                        <td class="text-center">{{ Form::checkbox($s['key'], 1, $s['show']) }}</td>
                                        <td class="text-center">
                                            @if ($s['orderby'] > 0)
                                                <a class="move_up" rowid="{{$s['id']}}" roworder="{{$s['orderby']}}" sms_provider="{{$s['id_smsprovider']}}"><i class="fa fa-arrow-up" aria-hidden="true"></i>
                                                </a>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($key < count($smsconfig))
                                                <a class="move_down" rowid="{{$s['id']}}" roworder="{{$s['orderby']}}" sms_provider="{{$s['id_smsprovider']}}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                @endif

                <div class="tab-pane fade" id="js_provider-new" role="tabpanel" aria-labelledby="add-tab">
                    {{ Form::panelText('provider_name_new', null) }}
                    {{ Form::panelText('provider_number_prefix_new', null) }}
                    @foreach($config as $c)
                        {{ Form::panelText(mb_strtolower($c['key']), '') }}
                    @endforeach
                    <div class="form-group">
                        <label for="provider_active_new"
                               class="col-md-4">{{ __('admin_labels.provider_active') }}</label>
                        <div class="col-md-8">
                            <input name="provider_active_new"
                                   type="checkbox"
                                   id="provider_active_new" checked value="1">
                            <p class="error-block"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="provider_default_new"
                               class="col-md-4">{{ __('admin_labels.provider_default') }}</label>
                        <div class="col-md-8">
                            <input name="provider_default_new"
                                   type="radio"
                                   id="provider_default_new" value="1">
                            <p class="error-block"></p>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary hide" id="js_provider-add-new"><i
                                class="fa fa-dot-circle-o"></i> {{ __('admin_labels.provider_btn_add') }}
                    </button>

                </div>
            </div>
        </div>
        <div class="hr-line-dashed"></div>

        <div class="row">
            <div class="col-md-6">

            </div>
        </div>
        <div class="row">
            <div class="col-md-4 m-b-md m-t-md">
                <p class="text-warning">
                    {{ __('admin.settings.smsconfig.warning')}}
                </p>
            </div>
        </div>
    </div>
    <div class="ibox-footer">
        {{ Form::panelButton() }}
    </div>
    {!! Form::close() !!}
    <script>
        $('.move_up').on('click', function () {
            var tr = $(this).closest('tr');
            var orderby = $(this).attr('roworder');
            var idrow = $(this).attr('rowid');
            var smsprovider = $(this).attr('sms_provider');
            $(this).attr('roworder', orderby-1);
            tr.prev().find('a').attr('roworder', orderby);
            $.ajax({
                url: '{{ route('admin.settings.smsconfig.ajaxup') }}',
                type: "GET",
                data: {
                    orderby: orderby,
                    idrow: idrow,
                    smsprovider:smsprovider
                },
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(data) {
                    console.log(data);
                    tr.after(tr.prev());
                }
            });
        });

        $('.move_down').on('click', function () {
            var tr = $(this).closest('tr');
            var orderby = $(this).attr('roworder');
            var idrow = $(this).attr('rowid');
            var smsprovider = $(this).attr('sms_provider');
            $(this).attr('roworder', orderby+1);
            tr.next().find('a').attr('roworder', orderby);
            $.ajax({
                url: '{{ route('admin.settings.smsconfig.ajaxdown') }}',
                type: "GET",
                data: {
                    orderby: orderby,
                    idrow: idrow,
                    smsprovider:smsprovider
                },
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(data) {
                    console.log(data);
                    tr.before(tr.next());
                }
            });
        });
    </script>
@endsection
