<div>
@if(!empty($review_act_templates))
    @foreach($review_act_templates as $review_act_template)

        <button type="button" class="btn btn-sm btn-default" data-review_act_template_id="{{ $review_act_template->id }}">{{ $review_act_template->name }}</button>
        
    @endforeach
@endif
</div>
