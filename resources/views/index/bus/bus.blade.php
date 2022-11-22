@extends('index.root')

@section('main')
    @php($template = $bus->template)
    @php($width = (214 + $template->columns*48))
    @php($height = (42 + $template->ranks*35))
    <div style="width: 100%;overflow-y: hidden;">
        <div class="busLayoutBlock">
            <div class="busSettingsPanel"></div>
            <div class="mainBusBlock">
                <div style="padding-left: 5%;" class="busBodeyWrapp">
                    <div style="width: {{$width}}px;height:{{$height}}px" class="busBodey">
                        <div class="seats">
                            @php($i = 0)
                            @foreach($template->templatePlaces as $place)
                                <div class="cell {{ trans('pretty.template_places.order.'. $place->type) }}"
                                     data-number="{{ $place->number }}">
                                    @if($place->type == \App\Models\TemplatePlace::TYPE_NUMBER)
                                        <div class="numElement">{{ $place->number }}</div>
                                    @endif
                                </div>
                                @php($i++)
                                @if ($i == $template->columns)
                                    @php($i = 0)
                                    <div style="clear: both"></div>
                                @endif
                            @endforeach
                        </div>
                        <div class="frontPart"></div>
                        <div class="backPart"></div>
                        <div class="centralPart"></div>
                        <div class="mirror leftMittor"></div>
                        <div class="mirror rightMirror"></div>
                    </div>
                </div>
                <br style="clear:both">
            </div>
        </div>
    </div>
@stop





