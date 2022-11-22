@php($template = $tour->bus->template)
@php($width = (214 + $template->columns * 48))
@php($height = (64 + $template->ranks * 35))
@php($reserved =  $tour->reserved)
@if(config('app.FRAGMENTATION_RESERVED') && isset($cityFromId) && isset($cityToId))
    @php($freePlaces =  \App\Services\Order\FragmentationOrder::searchCityFreePlaces($tour, $cityFromId, $cityToId,'places'))
    @php($reserved = $reserved->whereNotIn('number', $freePlaces))
@endif
<div id="BusLayout" style="overflow-y: hidden;">
    <br><br><br><br>
    <div class="busLayoutBlock">
        <div style="width: {{$width}}px;height:{{$height}}px" class="busBodey">
            <div class="seats">
                @php($i = 0)
                @foreach($template->templatePlaces as $place)
                    <div
                            class="cell
                                        {{ trans('pretty.template_places.order.'. $place->type) }}
                            {{
                            ($order && $order->orderPlaces && $order->orderPlaces->contains('number', $place->number) && !$order->pull && (($order->tour_id == $tour->id) || (($changeTour ?? false) && !$reserved->contains('number', $place->number)))) ? 'active' : (
                            $reserved->contains('number', $place->number) ? 'reserved' : ''
                            ) }}
                                    "
                            data-number="{{ $place->number }}"
                    >
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
        <br style="clear:both">
    </div>
</div>
<script>var cnt_reserved_places_tour    = '0'; </script>
<script>var limit_order_by_place    = '0'; </script>

