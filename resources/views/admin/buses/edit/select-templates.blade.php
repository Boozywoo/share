{{ Form::panelSelect('template_id', $templates, null, 
    [
        'class' => "form-control js_template-change", 
        'data-url' => route('admin.buses.getTemplateCountPlaces'), 
        'data-wrapper' => 'js_template-input'
    ]
) }}

<div class="js_template">
    @if($template)
        <span data-url="{{route ('admin.buses.showPopup', $bus)}}" data-toggle="modal"
              data-target="#popup_tour-edit">
                            @include('admin.'. $entity . '.templates.partials.template', compact('template'))
        </span>
    @endif
</div>