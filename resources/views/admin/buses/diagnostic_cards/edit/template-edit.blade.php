<div class="diagnostic_card">
@if(!empty($review_acts))

    <div class="row buttons">
        <div class="col-md-12">
            <button type="button" class="btn btn-sm btn-success button0" data-review_act_template_id="0">{{ trans('admin.settings.amenities.not_assigned') }}</button>
            
            <div class="js_template_buttons" style="display: inline-block;">
                @foreach($review_acts as $review_act)
                    <button type="button" class="btn btn-sm btn-default" data-review_act_template_id="{{ $review_act->review_act_template_id }}">{{ $review_act->template->name }}</button>
                @endforeach
            </div>
            
            {{ Form::panelButton() }}
        </div>
    </div>
    
    <div class="row">
        <div class="col-xs-12">
            <hr />
        </div>
    </div>
    
    <div class="row act_panels">
        <div class="col-md-12">
            <div class="act_panel act_panel_0" data-review_act_template_id="0">
                {{ Form::panelText('km', $diagnostic_card->km ? $diagnostic_card->km : '') }}
                {{ Form::panelText('fuel', $diagnostic_card->fuel ? $diagnostic_card->fuel : '') }}
            </div>
            
            @foreach($review_acts as $review_act)
                <div class="act_panel review_act pace-active" data-review_act_template_id="{{ $review_act->review_act_template_id }}">
                
                
                @foreach($review_act->items as $item)
                    <div class="row">
                        <div class="col-md-6">
                            {!! Form::hidden("body.$item->id", false) !!}
                            <label style="font-weight: 600; text-align: right;" for="body.{{$item->id}}" class="col-md-7">
                                {{$item->template_item->name}}
                            </label>
                            <div class="col-md-5">
                                {{ Form::onOffCheckbox("body.$item->id", $item->status ? $item->status : '') }}
                            </div>
                        </div>
        
                        <div class="col-md-6 review_act_item_img{{ $item->status ? '' : ' invisible' }}">
                            <div class="col-md-12">
                                {!! $item->getImagesView($item::IMAGE_TYPE_IMAGE, 'image_'.$item->id) !!}
                            </div>
                        </div>
                    </div>
                    <hr>

                @endforeach
                
                
                </div>
            @endforeach
        </div>
    </div>
    
@endif
</div>
