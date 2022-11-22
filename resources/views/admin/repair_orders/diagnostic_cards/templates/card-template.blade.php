<div class="col-xs-12 card-template">
    <div class="">
        <div class="card-item selected" id="card-item-0" onclick="showCard(0)">
            <span>{{__('admin_labels.general')}}</span>
        </div>

        @foreach($items as $item)
            @if(in_array($item->id,$cardTemplates))
                <div class="card-item" style="border-color: #f8ac59" id="card-item-{{$item->id}}"
                     onclick="showCard({{$item->id}})">
                    <span>{{$item->name}} - <span id="count_{{$item->id}}"></span></span>
                </div>
            @else
                <div class="card-item" id="card-item-{{$item->id}}" onclick="showCard({{$item->id}})">
                    <span>{{$item->name}} - <span id="count_{{$item->id}}"></span></span>
                </div>
            @endif
        @endforeach
    </div>
    <div class="">
        <div id="item_0" class="all_card">
            <div class="row text-center">
                <span class="font-bold">{{__('admin_labels.car_id')}} </span> :
                <span>{{$repairOrder->bus->name}}</span>
            </div>
            <div class="row text-center">
                <span class="font-bold">{{__('admin_labels.cars_number')}} </span> :
                <span>{{$repairOrder->bus->number}}</span>
            </div>
            <div class="row text-center">
                <span class="font-bold"> {{__('admin_labels.garage_number')}} </span> :
                <span>{{$repairOrder->bus->garage_number}}</span>
            </div>
            <div class="row text-center">
                <span class="font-bold">{{__('admin_labels.odometer')}} </span> :
                <span>{{$repairOrder->bus->odometer}} km</span>
            </div>
                        <div class="row text-center">
                            <span class="font-bold">{{__('admin_labels.fuel')}} </span> :
                            <span>{{$repairOrder->order_outfit->fuel}} </span>
                        </div>
            <div class="row text-center">
                <span class="font-bold">{{__('admin_labels.repair_type')}} </span> :
                <span>{{__('admin.repair_orders.types.'.$repairOrder->type)}}</span>
            </div>
            <div class="row">
                {!! Form::panelTextarea('comment',false, null, null,[], $repairOrder->diagnostic_card ? $repairOrder->diagnostic_card->comment: null) !!}
            </div>
        </div>
    </div>

    @foreach($items as $item)
        <div id="item_{{$item->id}}" class="all_card" style="display: none">
            @foreach($item->childs as $child)
                @php
                     $selected = $selectedItems->where('repair_card_template_id', $child->id)->first();
                @endphp
                <div class="row">
                    <div class="col-md-4">
                        <div class="onoffswitch">
                            {!! Form::checkbox('childs[]', $child->id, $selected, ['class' => 'card-checkbox', 'data-item_id' => $item->id,
                                'style' => 'display: none', 'id' => 'child_'. $child->id])  !!}
                            {!! Form::labelHtml('child_'. $child->id, '<span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>',
                                ['class' => 'onoffswitch-label']
                            ) !!}
                        </div>
                        <span>{{$child->name}}</span>
                    </div>
                    <div class="col-md-8" id="item_additional_{{$child->id}}"
                         style="display: {{$selected ? 'block' :'none'}}">
                        @if($child->is_comment)
                            <div class="form-group">
                                <label for="{{$child->id.'[comment]'}}"
                                       class="col-md-4 control-label">{{__('admin_labels.comment')}}</label>
                                <div class="col-md-8">
                                    {!! Form::text($child->id.'[comment]', ($selected ? $selected->comment : ''),['class' => 'form-control ']) !!}
                                </div>
                            </div>
                        @else
                            {!! Form::hidden($child->id.'[comment]', '') !!}
                        @endif

                        @if($child->is_photo)
                            <div class="form-group">
                                @if(!empty($image) && empty($selected))
                                    {!! $image->getImagesView($image::IMAGE_TYPE_IMAGE,'image_'.$child->id) !!}
                                @else
                                    {!! $selected->getImagesView($selected::IMAGE_TYPE_IMAGE, 'image_'.$child->id) !!}

                                @endif
                            </div>
                        @endif
                    </div>

                </div>
            @endforeach

            @if($item->is_comment)
                @php
                    $selected = $selectedItems->where('repair_card_template_id', $item->id)->first();
                @endphp

                <div class="row">
                    <div class="form-group col-md-10">
                        <div style="padding: 5px 15px">
                            {!! Form::textarea($item->id.'[comment]', ($selected ? $selected->comment : ''),['placeholder'=> __('admin_labels.notes'),'class' => '  form-control ','rows' => '5', 'style' => "width: inherit"]) !!}
                            {!! Form::hidden('childs[]', $item->id) !!}
                        </div>
                    </div>

                </div>
            @endif
        </div>
    @endforeach
</div>
</div>
<script>
    function showCard(id) {
        $('.card-item').removeClass('selected');
        $('#card-item-' + id).addClass('selected');
        $('.all_card').hide();
        $('#item_' + id).show();
    }

    let all_items = {{json_encode($items->map(function ($item){ return $item->id;}))}}

    all_items.forEach(item => {
        calcCount(item)
    });

    $(document).ready(function () {

        $(".card-checkbox").on('change', function (e) {
            if (e.target.dataset && e.target.dataset.item_id) {
                calcCount(e.target.dataset.item_id);
                console.log(e.target.value);
                toogleContent(e.target.value, e.target.checked);
            }
        })
    });

    function calcCount(id) {
        let count = $('#item_' + id + ' .card-checkbox:checked').length;

        $('#count_' + id).html(count);
    }

    function toogleContent(id, value) {
        console.log($("#item_additional_" + id));
        if (value) {
            $("#item_additional_" + id).show()
        } else {
            $("#item_additional_" + id).hide()
        }
    }
</script>