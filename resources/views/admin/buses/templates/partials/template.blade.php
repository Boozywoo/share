<div class="templates">
    @php($i = 0)
    @foreach($template->templatePlaces as $place)
        <div class="templates__box templates__box-{{ $place->type }}"></div>
        @php($i++)
        @if($i == $template->columns)
            <br>
            @php($i = 0)
        @endif
    @endforeach
</div>