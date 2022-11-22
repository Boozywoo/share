<div class="col-xs-12 card-template">
    <div class="">
        <div class="card-item selected" id="card-item-0" onclick="showCard(0)">
            <span>{{__('admin_labels.general')}}</span>
        </div>

        @foreach($reviewActTemplates as $template)
            <div class="card-item" id="card-item-{{$template->id}}" onclick="showCard({{$template->id}})">
                <span>{{$template->name}} - <span id="count_{{$template->id}}"></span></span>
            </div>
        @endforeach
    </div>
    <div class="">
        <div id="item_0" class="all_card">
            <div class="row text-center">
                <span class="font-bold">{{__('admin_labels.car_id')}} </span> :
                <span>{{$bus->name}}</span>
            </div>
            <div class="row text-center">
                <span class="font-bold">{{__('admin_labels.cars_number')}} </span> :
                <span>{{$bus->number}}</span>
            </div>
            <div class="row text-center">
                <span class="font-bold"> {{__('admin_labels.garage_number')}} </span> :
                <span>{{$bus->garage_number}}</span>
            </div>
            {!! Form::hidden('min_odometer', $bus->odometer) !!}
            @if(request()->has('odometer'))
                <div class="row text-center">
                    <span class="font-bold">{{__('admin_labels.odometer')}} </span> :
                    <span>{{request()->get('odometer')}} km</span>
                    {!! Form::hidden('odometer',request()->query('odometer')) !!}

                </div>
            @else
                <div class="row text-center">
                    {!! Form::panelText('odometer', $diagnosticCard && $diagnosticCard->bus_variable ? $diagnosticCard->bus_variable->odometer: $bus->odometer) !!}
                </div>
            @endif
            @if(request()->query('fuel'))
                <div class="row text-center">
                    <span class="font-bold">{{__('admin_labels.fuel')}} </span> :
                    <span>{{request()->query('fuel')}} </span>
                    {!! Form::hidden('fuel',request()->query('fuel')) !!}
                </div>
            @else
                <div class="row text-center">
                    {!! Form::panelText('fuel', $diagnosticCard && $diagnosticCard->bus_variable ? $diagnosticCard->bus_variable->fuel: $bus->fuel) !!}
                </div>
            @endif

            <div class="row">
                {!! Form::panelTextarea('notes',false, null, null,[], $diagnosticCard && $diagnosticCard->notes ? $diagnosticCard->notes: null) !!}
            </div>

        </div>
    </div>

    @foreach($reviewActTemplates as $template)
        <div id="item_{{$template->id}}" class="all_card" style="display: none">
            @foreach($template->items as $child)
                @php
                     $selected = $selectedItems->where('review_act_template_item_id', $child->id)->first();
                @endphp
                <div class="row">
                    <div class="col-md-4">
                        <div class="onoffswitch">
                            {!! Form::checkbox('childs[]', $child->id, $selected, ['class' => 'card-checkbox', 'data-item_id' => $template->id,
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
                                    {!! $image->getImagesView($image::IMAGE_TYPE_IMAGE,$child->id.'[image]') !!}
                                @else
                                    {!! $selected->getImagesView($selected::IMAGE_TYPE_IMAGE, $child->id.'[image]') !!}

                                @endif
                            </div>
                        @endif
                    </div>

                </div>
            @endforeach

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

    let all_items = {{json_encode($reviewActTemplates->map(function ($item){ return $item->id;}))}}

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