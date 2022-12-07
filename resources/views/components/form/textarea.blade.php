@props(['class', 'label', 'name', 'placeholder', 'value'])
<div class="{{ $class }}">
    <div class="form-group">
        <label for="{{ $name }}">{{ $label }}</label>
        <textarea name="{{ $name }}" id="{{ $name }}" class="form-control" placeholder="{{ $placeholder }}"
            cols="4" rows="3" required {{ $attributes }}>{{ empty($value) ? old($name) : $value }}</textarea>
    </div>
</div>
