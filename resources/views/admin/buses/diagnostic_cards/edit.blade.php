@extends('panel::layouts.main')

@section('title', $diagnosticCard->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create'))

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i> {{
    trans('admin.filter.back') }}</a>
@endsection

@section('main')
    @if(request('user_taken_bus_id'))
        @php $method = 'js_form-ajax-redirect' @endphp
    @else
        @php $method = 'js_form-ajax-reset' @endphp
    @endif

    @if(!$diagnosticCard->id)
        {!! Form::model($diagnosticCard, ['route' => ['admin.'. $entity . '.store', $bus], 'method' =>'post',
            'class' => ' form-horizontal js_form-ajax '.$method])  !!}
    @else
        {!! Form::model($diagnosticCard, ['route' => ['admin.'. $entity . '.update',$bus,$diagnosticCard],
            'method' => 'put', 'class' => ' form-horizontal js_form-ajax '.$method])  !!}
    @endif

    {!! Form::hidden('id') !!}

    {!! Form::hidden('user_taken_bus_id', request('user_taken_bus_id')) !!}

    <div class="ibox {{ $wrapperColor }}">
        <div class="ibox-content diagnostic_card">
            <h2>{{ $diagnosticCard->id ? trans('admin.'. $entity . '.edit') : trans('admin.'. $entity . '.create') }}</h2>
            <div class="hr-line-dashed"></div>

            <div class="row ">
                @if(empty($busTemplate))
                    <div class="hr-line-dashed"></div>

                    <div class="col-md-6 ">

                        <div class="row form-group">
                            <label for="type_id" class="col-sm-4">{{__('admin.'.$entity.'.card_list')}}</label>
                            <div class="col-sm-8">
                                @if($diagnosticCard->id)
                                    {!! Form::hidden('diagnostic_card_template_id', $diagnosticCard->diagnostic_card_template_id) !!}
                                    <select name="diagnostic_card_template_id" class="form-control "
                                            id="template_id" disabled>
                                        {{--                                    <option></option>--}}
                                        <option value="" disabled
                                        >{{__('admin.'.$entity.'.choose_card_list')}}</option>
                                        @foreach($templates as $key=>$template)
                                            <option value="{{$key}}">{{$template}}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <select name="diagnostic_card_template_id" class="form-control "
                                            id="template_id">
                                        <option value="" disabled
                                                selected>{{__('admin.'.$entity.'.choose_card_list')}}</option>
                                        @foreach($templates as $key=>$template)
                                            <option value="{{$key}}">{{$template}}</option>
                                        @endforeach
                                    </select>

                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    {!! Form::hidden('diagnostic_card_template_id', $busTemplate->id) !!}

                    <script>
                        $(document).ready(function () {

                            let template_id = "{{$busTemplate->id}}";
                            getContent(template_id);
                        });
                    </script>
                @endif
            </div>
            <div class="hr-line-dashed"></div>

            <div class="row" id="card-area">

            </div>
            <button type="submit" id="card-save" class="btn btn-primary ibox-content-item center-block">
                <i class="fa fa-dot-circle-o"></i> {{trans('admin.filter.save') }} </button>

        </div>
    </div>
    {!! Form::close() !!}
    <script>
        @if(!empty(request()->all()))
            var request = JSON.parse('{!! json_encode(request()->all()) !!}');
            @else
            var request = {};
        @endif

        $(document).ready(function () {
            $("#template_id").select2();

            $("#template_id").on('select2:select', function (e) {
                let selected_item = e.params.data;
                getContent(selected_item.id);
            });
        });
        function getContent(id, card_id = null) {
            let data = request;
            data.template_id = id;
            data.card_id = card_id;

            $.ajax({
                url: "{{route('admin.'.$entity.'.itemsOfTemplate', $bus)}}",
                method: 'get',
                data: data,
                success: function (res) {
                    if (res.view) {
                        $('#card-area').html(res.view);
                    }
                }
            });

        }
    </script>
    @if($diagnosticCard->id)
        <script>
            $(document).ready(function () {
                let selectedValue = {{$diagnosticCard->diagnostic_card_template_id}};
                let card_id = {{$diagnosticCard->id}};
                $("#template_id").val(selectedValue).trigger({
                    type: 'change', data: {
                        id: selectedValue
                    }
                });
                getContent(selectedValue, card_id);
            });
        </script>
    @endif

@endsection
