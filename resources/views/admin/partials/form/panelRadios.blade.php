
<div class="form-group dib">
    <table>
        @foreach($values as $key => $value)
        <tr>
            <td class="pr5">{{ Form::label($name .'-'. $key, $value, ['class' => "control-label"]) }}</td>
            <td class="pl5">
                <div class="radio radio-warning radio-inline">
                    {{ Form::radio($name, $key, $def == $key ? true : false, ['id' => $name .'-'. $key]) }}
                    <label for="{{ $name }}-{{ $key }}"> </label>
                </div>
            </td>
        </tr>
        @endforeach
    </table>
</div>