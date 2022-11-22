@if($multiple)
    <div class="form-group" style="width: initial">

        <select name="{{$name}}[]" multiple="multiple" class="select2-block select2-once" id="select_{{$id}}"
                style="width: initial">
            {{--        <option selected value="">{{ $placeholder }}</option>--}}
            @foreach($values as $key=>$value)
                @if(is_array($selected))
                    <option {{in_array($key, $selected) ? 'selected' : false}} value="{{$key}}">{{$value}}</option>
                @else
                    <option {{$key == $selected ? 'selected' : false}} value="{{$key}}">{{$value}}</option>
                @endif
            @endforeach
        </select>
    </div>
    <script>

        $(document).ready(function () {
            $("#select_{{"$id"}}").select2({
                placeholder: "{{$placeholder}}",
                allowHtml: true,
                allowClear: true,
                tags: false,
                width: '100%',
                closeOnSelect: false
            });
        });
    </script>
@else
    <div class="form-group" style="width: initial">

        <select name="{{$name}}" class="select2-block select2-multiple" id="select_{{$id}}" style="width: initial">
            <option selected value="">{{ $placeholder }}</option>
            @foreach($values as $key=>$value)
                <option {{$key == $selected ? 'selected' : false}} value="{{$key}}">{{$value}}</option>
            @endforeach
        </select>
    </div>
    <script>
        $(document).ready(function () {

            $("#select_{{"$id"}}").select2({width: '100%'});
        });
    </script>
@endif


