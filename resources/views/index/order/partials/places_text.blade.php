<div class="form-group">
    <label class="control-label" for="{{ $name }}-{{ $id }}">{{ trans('admin_labels.'. $name)}}</label>
    <input name="{{$name}}[{{$id}}]" maxlength="100" type="text" id="{{ $name }}-{{ $id }}"
           required="required" class="form-control {{ $class ?? '' }}" placeholder="{{ trans('admin_labels.'. $name)}}">
</div>