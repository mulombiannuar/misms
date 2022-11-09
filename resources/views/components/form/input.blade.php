@props(['class', 'label', 'type', 'name', 'placeholder', 'value'])
<div class="{{ $class }}">
    <div class="form-group">
        <label for="{{ $name }}">{{ $label }}</label>
        <input type="{{ $type }}" name="{{ $name }}" class="form-control" id="{{ $name }}"
            placeholder="{{ $placeholder }}" autocomplete="off" value="{{ empty($value) ? old($name) : $value }}"
            {{ $attributes }} required>
    </div>
</div>
