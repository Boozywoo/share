@php($template = $tour->bus->template)
@php($width = (214 + $template->columns*48))
@php($height = (64 + $template->ranks*35))
<div class="js_bus-wrap">
    <div style="width: {{$width}}px; height:{{$height}}px" class="busBodey">
        <div class="seats">
            @php($i = 0)
            @php($reserved = $tour->reserved)
            @foreach($template->templatePlaces as $place)
                <div class="cell {{ trans('pretty.template_places.order.'. $place->type) }}
                            {{ $order->orderPlaces->contains('number', $place->number) && $place->number ? 'active' : (
                            $reserved->contains('number', $place->number ? $place->number : 'no_seat') ? 'reserved' : ''
                            ) }}
                        " data-number="{{ $place->number }}">
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
        <div class="mirror leftMirror"></div>
        <div class="mirror rightMirror"></div>
    </div>
</div>
<ul class="seatsClarification">
    <li>- {{trans('index.schedules.empty')}}</li>
    <li>- {{trans('index.schedules.selected')}}</li>
    <li>- {{trans('index.schedules.busy')}}</li>
</ul>
{!! Form::open(['route' => ['index.schedules.storePlaces', $tour], 'class' => 'js_ajax-form js_form-places bottomBusInfoBlock']) !!}
    <div class="js_form-places-inputs"></div>
    <div class="js_form-continue">
        @include('index.schedules.partials.bus.continue')
    </div>
    <br style="clear:both"/>
{!! Form::close()!!}
<div class="js_bus-overlay"></div>

<script>var limit_order_by_place        = '{!! $setting->limit_order_by_place !!}'; </script>
<script>var cnt_reserved_places_tour    = '{!! $order->count_places !!}'; </script>
