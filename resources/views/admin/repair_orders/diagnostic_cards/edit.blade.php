@extends('panel::layouts.main')

@section('title',  __('admin.'.$entity.'.title').$repairOrder->order_outfit->id)

@section('actions')
    <a href="{{ url()->previous() }}" class="btn btn-default js_form-ajax-back pjax-link"><i
                class="fa fa-chevron-left"></i>
        {{ trans('admin.filter.back') }}
    </a>
@endsection

@section('main')

    {{--    <div class="">--}}
    @if($diagnosticCard->id)
        {!! Form::model($diagnosticCard,['route' => ['admin.' . $entity . '.update', $repairOrder,$diagnosticCard], 'method' => 'put','class' => 'ibox form-horizontal js_form-ajax js_form-ajax-redirect']) !!}

    @else
        {!! Form::model($diagnosticCard,['route' => ['admin.' . $entity . '.store', $repairOrder], 'method' => 'post','class' => 'ibox form-horizontal js_form-ajax js_form-ajax-redirect']) !!}

    @endif
    <div class="ibox {{ $wrapperColor }}">
        <div class="ibox-content">
            <div class="row">
                @include('admin.repair_orders.diagnostic_cards.templates.order-outfit-template')
            </div>
            <div class="row ">
                @if(empty($repairTemplate))
                    <div class="hr-line-dashed"></div>

                    <div class="col-md-6 ">

                        <div class="row form-group">
                            <label for="type_id" class="col-sm-4">{{__('admin.'.$entity.'.card_list')}}</label>
                            <div class="col-sm-8">
                                @if($diagnosticCard->id)
                                    {!! Form::hidden('repair_card_type_id', $diagnosticCard->repair_card_type_id) !!}
                                    <select name="repair_card_type_id" class="form-control " id="type_id" disabled>
                                        {{--                                    <option></option>--}}
                                        <option value="" disabled
                                        >{{__('admin.'.$entity.'.choose_card_list')}}</option>
                                        @foreach($repairCardTypes as $key=>$type)
                                            <option value="{{$key}}">{{$type}}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <select name="repair_card_type_id" class="form-control " id="type_id">
                                        {{--                                    <option></option>--}}
                                        <option value="" disabled
                                                selected>{{__('admin.'.$entity.'.choose_card_list')}}</option>
                                        @foreach($repairCardTypes as $key=>$type)
                                            <option value="{{$key}}">{{$type}}</option>
                                        @endforeach
                                    </select>

                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    {!! Form::hidden('repair_card_type_id', $repairTemplate->id) !!}

                    <script>
                        $(document).ready(function () {

                            let template_id = "{{$repairTemplate->id}}";
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
        <div class="ibox-footer">
        </div>
    </div>
    {{--    </div>--}}
    {!! Form::close() !!}
    <script>
        $(document).ready(function () {
            $("#type_id").select2();

            $("#type_id").on('select2:select', function (e) {
                let selected_item = e.params.data;
                getContent(selected_item.id);
            });
        });

        function getContent(id, card_id = null) {
            $.ajax({
                url: "{{route('admin.'.$entity.'.getCardContent', $repairOrder->id)}}",
                method: 'get',
                data: {
                    type_id: id,
                    card_id: card_id
                },
                success: function (res) {
                    console.log(res);
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
                let selectedValue = {{$diagnosticCard->repair_card_type_id}};
                let card_id = {{$diagnosticCard->id}};
                $("#type_id").val(selectedValue).trigger({
                    type: 'change', data: {
                        id: selectedValue
                    }
                });
                getContent(selectedValue, card_id);
            });
        </script>
    @endif

@endsection
