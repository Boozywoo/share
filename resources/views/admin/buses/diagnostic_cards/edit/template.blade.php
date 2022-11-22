<div>
    @if(!empty($review_act_templates))
        @foreach($review_act_templates as $review_act_template)
            <div class="act_panel review_act" data-review_act_template_id="{{ $review_act_template->id }}">

                @foreach($review_act_template->items as $item)
                    <div class="row">
                        @php
                            $selected = collect([]);//$selected_items->where('repair_card_template_id', $child->id)->first();
                        @endphp

                        <div class="col-md-4">
                                <div class="onoffswitch">
                                    {!! Form::checkbox('childs[]', $item->id, $selected, ['class' => 'card-checkbox', 'data-item_id' => $review_act_template->id,
                'style' => 'display: none', 'id' => 'child_'. $item->id])  !!}
                                    {!! Form::labelHtml('child_'. $item->id, '<span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>',
                                        ['class' => 'onoffswitch-label']
                                    ) !!}
                                </div>
                                <span style="vertical-align: top;">{{$item->name}}</span>

                        </div>

                        <div class="col-md-8" id="item_additional_{{$item->id}}"
                             style="display: {{$selected ? 'block' :'none'}}">
                            @if($item->is_comment)
                                <div class="form-group">
                                    <label for="{{$item->id.'[comment]'}}"
                                           class="col-md-4 control-label">{{__('admin_labels.comment')}}</label>
                                    <div class="col-md-8">
                                        {{--                                {!! Form::text($item->id.'[comment]', ($selected ? $selected->comment : ''),['class' => 'form-control ']) !!}--}}
                                    </div>
                                </div>
                            @else
                                {!! Form::hidden($item->id.'[comment]', '') !!}
                            @endif

                            @if($item->is_photo)
                                <div class="form-group">
                                    {{--                            @if(!empty($image) && empty($selected))--}}
                                    {{--                                {!! $image->getImagesView($image::IMAGE_TYPE_IMAGE,'image_'.$child->id) !!}--}}
                                    {{--                            @else--}}
                                    {{--                                {!! $selected->getImagesView($selected::IMAGE_TYPE_IMAGE, 'image_'.$child->id) !!}--}}

                                    {{--                            @endif--}}
                                </div>
                            @endif
                        </div>
                    </div>
                    <hr>
                @endforeach

            </div>
        @endforeach
    @endif
</div>
